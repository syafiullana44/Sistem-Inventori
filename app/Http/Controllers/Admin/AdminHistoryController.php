<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use App\Models\PermintaanProduksiHeader;
use App\Models\PermintaanGudangHeader;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminHistoryController extends Controller
{
    public function index(Request $request)
    {
        $bahanList = MasterBahan::select('id', 'nama_bahan', 'kode_bahan')->orderBy('nama_bahan')->get();
        
        // 🔥 AMBIL DENGAN LIMIT DULU!
        $limit = 500; // Batasi data
        
        $permintaanProduksi = $this->getPermintaanProduksi($request, $limit);
        $pengeluaran = $this->getPengeluaran($request, $limit);
        $permintaanPengadaan = $this->getPermintaanPengadaan($request, $limit);
        $barangMasuk = $this->getBarangMasuk($request, $limit);

        $collection = collect()
            ->merge($permintaanProduksi)
            ->merge($pengeluaran)
            ->merge($permintaanPengadaan)
            ->merge($barangMasuk)
            ->sortByDesc('tanggal')
            ->values();

        if ($request->filled('jenis')) {
            $collection = $collection->where('jenis_transaksi', $request->jenis);
        }
        if ($request->filled('jenis_mutasi')) {
            $collection = $collection->where('jenis_mutasi', $request->jenis_mutasi);
        }

        // Paginasi
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $history = new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('admin.history', compact('history', 'bahanList'));
    }

    private function getPermintaanProduksi($request, $limit = 500)
    {
        $query = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
            ->select('permintaan_produksi_header.*')
            ->addSelect(DB::raw("'Permintaan Produksi' as jenis_transaksi"));

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('bahan_id')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('id_bahan', $request->bahan_id);
            });
        }

        // 🔥 BATASI DATA!
        return $query->limit($limit)->get()->map(function($item) {
            return (object) [
                'id' => $item->id,
                'no_transaksi' => $item->kode_pr,
                'tanggal' => $item->created_at,
                'dari' => $item->userProduksi->nama_lengkap ?? '-',
                'untuk' => 'Gudang',
                'status' => $item->status,
                'jenis_transaksi' => 'Permintaan Produksi',
                'jenis_mutasi' => 'Keluar',
                'details' => $item->details->map(function($detail) {
                    return (object) [
                        'nama_bahan' => $detail->bahan->nama_bahan ?? '-',
                        'satuan' => $detail->bahan->satuan ?? '-',
                        'jumlah_diminta' => $detail->jumlah_diminta,
                        'jumlah_dikeluarkan' => $detail->jumlah_dikeluarkan,
                        'status_item' => $detail->status_item,
                    ];
                }),
                'tanggal_diproses' => $item->tanggal_diproses,
                'tanggal_selesai' => $item->tanggal_selesai,
                'keterangan' => $item->keterangan,
            ];
        });
    }

    private function getPengeluaran($request, $limit = 500)
    {
        $query = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
            ->where('status', 'Selesai')
            ->select('permintaan_produksi_header.*')
            ->addSelect(DB::raw("'Pengeluaran' as jenis_transaksi"));

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_selesai', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('bahan_id')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('id_bahan', $request->bahan_id);
            });
        }

        return $query->limit($limit)->get()->map(function($item) {
            return (object) [
                'id' => $item->id,
                'no_transaksi' => $item->kode_pr,
                'tanggal' => $item->tanggal_selesai ?? $item->created_at,
                'dari' => 'Gudang',
                'untuk' => $item->userProduksi->nama_lengkap ?? '-',
                'status' => 'Selesai',
                'jenis_transaksi' => 'Pengeluaran',
                'jenis_mutasi' => 'Keluar',
                'details' => $item->details->map(function($detail) {
                    return (object) [
                        'nama_bahan' => $detail->bahan->nama_bahan ?? '-',
                        'satuan' => $detail->bahan->satuan ?? '-',
                        'jumlah_diminta' => $detail->jumlah_diminta,
                        'jumlah_dikeluarkan' => $detail->jumlah_dikeluarkan,
                        'status_item' => $detail->status_item,
                    ];
                }),
                'tanggal_diproses' => $item->tanggal_diproses,
                'tanggal_selesai' => $item->tanggal_selesai,
                'keterangan' => $item->keterangan,
            ];
        });
    }

    private function getPermintaanPengadaan($request, $limit = 500)
    {
        $query = PermintaanGudangHeader::with(['userGudang', 'details.bahan'])
            ->select('permintaan_gudang_header.*')
            ->addSelect(DB::raw("'Permintaan Pengadaan' as jenis_transaksi"));

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('bahan_id')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('id_bahan', $request->bahan_id);
            });
        }

        return $query->limit($limit)->get()->map(function($item) {
            return (object) [
                'id' => $item->id,
                'no_transaksi' => $item->kode_pg,
                'tanggal' => $item->created_at,
                'dari' => $item->userGudang->nama_lengkap ?? '-',
                'untuk' => 'Pengadaan',
                'status' => $item->status,
                'jenis_transaksi' => 'Permintaan Pengadaan',
                'jenis_mutasi' => 'Masuk',
                'details' => $item->details->map(function($detail) {
                    return (object) [
                        'nama_bahan' => $detail->bahan->nama_bahan ?? '-',
                        'satuan' => $detail->bahan->satuan ?? '-',
                        'jumlah_diminta' => $detail->jumlah_diminta,
                        'jumlah_datang' => $detail->jumlah_datang,
                        'status_item' => $detail->status_item,
                    ];
                }),
                'tanggal_diproses' => null,
                'tanggal_selesai' => $item->tanggal_selesai,
                'keterangan' => $item->keterangan,
            ];
        });
    }

    private function getBarangMasuk($request, $limit = 500)
    {
        $query = BarangMasuk::with(['permintaanGudang', 'userPengadaan', 'userGudang', 'details.bahan'])
            ->select('barang_masuk.*')
            ->addSelect(DB::raw("'Barang Masuk' as jenis_transaksi"));

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('bahan_id')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('id_bahan', $request->bahan_id);
            });
        }

        return $query->limit($limit)->get()->map(function($item) {
            return (object) [
                'id' => $item->id,
                'no_transaksi' => $item->kode_brg_masuk,
                'tanggal' => $item->created_at,
                'dari' => $item->userPengadaan->nama_lengkap ?? '-',
                'untuk' => $item->userGudang->nama_lengkap ?? 'Gudang',
                'status' => $item->status,
                'jenis_transaksi' => 'Barang Masuk',
                'jenis_mutasi' => 'Masuk',
                'details' => $item->details->map(function($detail) {
                    return (object) [
                        'nama_bahan' => $detail->bahan->nama_bahan ?? '-',
                        'satuan' => $detail->bahan->satuan ?? '-',
                        'jumlah_diterima' => $detail->jumlah_diterima,
                    ];
                }),
                'tanggal_diproses' => null,
                'tanggal_selesai' => $item->tanggal_diverifikasi,
                'keterangan' => $item->catatan,
            ];
        });
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $bahanList = MasterBahan::select('id', 'nama_bahan', 'kode_bahan')->get();
        
        $limit = 1000; // Untuk PDF boleh lebih banyak
        
        $permintaanProduksi = $this->getPermintaanProduksi($request, $limit);
        $pengeluaran = $this->getPengeluaran($request, $limit);
        $permintaanPengadaan = $this->getPermintaanPengadaan($request, $limit);
        $barangMasuk = $this->getBarangMasuk($request, $limit);

        $history = collect()
            ->merge($permintaanProduksi)
            ->merge($pengeluaran)
            ->merge($permintaanPengadaan)
            ->merge($barangMasuk)
            ->sortByDesc('tanggal')
            ->values();

        if ($request->filled('jenis')) {
            $history = $history->where('jenis_transaksi', $request->jenis);
        }
        if ($request->filled('jenis_mutasi')) {
            $history = $history->where('jenis_mutasi', $request->jenis_mutasi);
        }

        $title = 'History Semua Transaksi';

        $pdf = Pdf::loadView('exports.history-admin-pdf', compact('history', 'bahanList', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('history-semua-transaksi.pdf');
    }
}