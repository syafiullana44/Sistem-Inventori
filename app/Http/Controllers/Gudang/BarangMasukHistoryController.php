<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangMasukHistoryController extends Controller
{
    /**
     * Menampilkan history barang masuk yang sudah diverifikasi oleh gudang
     */
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['permintaanGudang', 'userPengadaan', 'details.bahan'])
            ->where('id_user_gudang', Auth::id())
            ->where('status', 'Diverifikasi');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_diverifikasi', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_diverifikasi', '<=', $request->tanggal_akhir);
        }

        $barangMasuk = $query->orderBy('tanggal_diverifikasi', 'desc')->paginate(20);

        return view('gudang.barang-masuk-history', compact('barangMasuk'));
    }

    // [TAMBAH] Export PDF
    public function exportPdf(Request $request)
    {
        $query = BarangMasuk::with(['permintaanGudang', 'userPengadaan', 'details.bahan'])
            ->where('id_user_gudang', Auth::id())
            ->where('status', 'Diverifikasi');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_diverifikasi', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_diverifikasi', '<=', $request->tanggal_akhir);
        }

        $barangMasuk = $query->orderBy('tanggal_diverifikasi', 'desc')->get();
        $title = 'History Barang Masuk';

        $pdf = Pdf::loadView('exports.history-barang-masuk-pdf', compact('barangMasuk', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('history-barang-masuk.pdf');
    }
}