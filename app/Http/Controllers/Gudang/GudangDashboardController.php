<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use App\Models\PermintaanProduksiHeader;
use App\Models\BarangMasuk;
use App\Models\PermintaanGudangHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GudangDashboardController extends Controller
{
    public function index()
    {
        // Data real-time (Tanpa Cache)
        $data = [
            'total_bahan' => MasterBahan::count(),
            'stok_menipis' => MasterBahan::whereRaw('stok_saat_ini <= stok_minimum')
                ->limit(10)
                ->get(),
            'permintaan_masuk' => PermintaanProduksiHeader::where('status', 'Menunggu')->count(),
            'pending_verifikasi' => BarangMasuk::where('status', 'Draft')->count(),
            'permintaan_produksi' => PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->orderBy('created_at', 'asc')
                ->limit(20)
                ->get(),
            'permintaan_pengadaan' => PermintaanGudangHeader::with(['details.bahan'])
                ->whereIn('status', ['Diproses', 'Sebagian'])
                ->orderBy('created_at', 'asc')
                ->limit(20)
                ->get(),
        ];

        return view('gudang.dashboard', $data);
    }
}