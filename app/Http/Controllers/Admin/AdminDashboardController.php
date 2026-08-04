<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use App\Models\PermintaanProduksiHeader;
use App\Models\PermintaanGudangHeader;
use App\Models\BarangMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Data real-time (Tanpa Cache)
        
        // Data real-time (Tanpa Cache & Disederhanakan)
        
        // 1. Total Transaksi Hari Ini
        $transaksiHariIni = PermintaanProduksiHeader::whereDate('created_at', today())->count()
            + PermintaanGudangHeader::whereDate('created_at', today())->count()
            + BarangMasuk::whereDate('created_at', today())->count();
            
        // 2. Transaksi Pending
        $pendingPR = PermintaanProduksiHeader::where('status', 'Menunggu')->count();
        $pendingPG = PermintaanGudangHeader::where('status', 'Diproses')->count();
        $pendingBM = BarangMasuk::where('status', 'Draft')->count();
        $totalPending = $pendingPR + $pendingPG + $pendingBM;
        
        // 3. Stok Kritis & Kosong
        $stokKosong = MasterBahan::where('stok_saat_ini', '<=', 0)->count();
        $stokMenipis = MasterBahan::whereRaw('stok_saat_ini <= stok_minimum')
            ->where('stok_saat_ini', '>', 0)
            ->orderByRaw('stok_saat_ini / stok_minimum ASC')
            ->limit(5)
            ->get();
            
        // 4. Recent Transactions (PAKAI UNION + LIMIT)
        $recentTransactions = $this->getRecentTransactionsOptimized();
        
        $data = compact(
            'transaksiHariIni',
            'totalPending',
            'pendingPR', 
            'pendingPG', 
            'pendingBM',
            'stokKosong',
            'stokMenipis',
            'recentTransactions'
        );
        
        return view('admin.dashboard', $data);
    }
    
    private function getMonthlyTransactions()
    {
        $bulanIni = now();
        $result = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanIni->copy()->subMonths($i);
            $namaBulan = $bulan->format('M Y');
            
            // 🔥 GABUNG QUERY
            $jumlah = PermintaanProduksiHeader::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();
            
            $jumlah += PermintaanProduksiHeader::whereMonth('tanggal_selesai', $bulan->month)
                ->whereYear('tanggal_selesai', $bulan->year)
                ->where('status', 'Selesai')
                ->count();
            
            $jumlah += PermintaanGudangHeader::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();
            
            $jumlah += BarangMasuk::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->where('status', 'Diverifikasi')
                ->count();
            
            $result[$namaBulan] = $jumlah;
        }
        
        return $result;
    }
    
    private function getRecentTransactionsOptimized()
    {
        // 🔥 PAKAI UNION + LIMIT (1 query, bukan 3!)
        $transactions = DB::table(function($query) {
            $query->select(
                    'kode_pr as no_transaksi',
                    'created_at as tanggal',
                    'status',
                    DB::raw("'Permintaan Produksi' as jenis"),
                    DB::raw("(SELECT nama_lengkap FROM users WHERE id = permintaan_produksi_header.id_user_produksi) as keterangan")
                )
                ->from('permintaan_produksi_header')
                ->unionAll(
                    DB::table('permintaan_gudang_header')
                        ->select(
                            'kode_pg as no_transaksi',
                            'created_at as tanggal',
                            'status',
                            DB::raw("'Permintaan Pengadaan' as jenis"),
                            DB::raw("(SELECT nama_lengkap FROM users WHERE id = permintaan_gudang_header.id_user_gudang) as keterangan")
                        )
                )
                ->unionAll(
                    DB::table('barang_masuk')
                        ->select(
                            'kode_brg_masuk as no_transaksi',
                            'created_at as tanggal',
                            'status',
                            DB::raw("'Barang Masuk' as jenis"),
                            DB::raw("(SELECT nama_lengkap FROM users WHERE id = barang_masuk.id_user_pengadaan) as keterangan")
                        )
                );
        }, 'all_transactions')
        ->orderBy('tanggal', 'desc')
        ->limit(10)
        ->get();
        
        return $transactions;
    }
}