<?php

namespace App\Http\Controllers\Pengadaan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanGudangHeader;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class PengadaanDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'menunggu' => PermintaanGudangHeader::where('status', 'Diproses')
                ->whereDoesntHave('barangMasuk', function($q) {
                    $q->where('status', 'Draft');
                })->count(),
            'sebagian' => PermintaanGudangHeader::where('status', 'Sebagian')
                ->whereDoesntHave('barangMasuk', function($q) {
                    $q->where('status', 'Draft');
                })->count(),
            'selesai' => PermintaanGudangHeader::where('status', 'Selesai')->count(),
            'permintaan' => PermintaanGudangHeader::with(['userGudang', 'details.bahan'])
                ->whereIn('status', ['Diproses', 'Sebagian'])
                ->whereDoesntHave('barangMasuk', function($q) {
                    $q->where('status', 'Draft');
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'barang_masuk_draft' => BarangMasuk::where('status', 'Draft')->count(),
        ];

        return view('pengadaan.dashboard', $data);
    }
}