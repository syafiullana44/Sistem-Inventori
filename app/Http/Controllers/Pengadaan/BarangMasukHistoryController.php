<?php

namespace App\Http\Controllers\Pengadaan;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangMasukHistoryController extends Controller
{
    /**
     * Menampilkan history input barang yang sudah diinput oleh pengadaan
     */
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['permintaanGudang', 'userGudang', 'details.bahan'])
            ->where('id_user_pengadaan', Auth::id());

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $barangMasuk = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('pengadaan.barang-masuk-history', compact('barangMasuk'));
    }

    // [TAMBAH] Export PDF
    public function exportPdf(Request $request)
    {
        $query = BarangMasuk::with(['permintaanGudang', 'userGudang', 'details.bahan'])
            ->where('id_user_pengadaan', Auth::id());

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $barangMasuk = $query->orderBy('created_at', 'desc')->get();
        $title = 'History Input Barang';

        $pdf = Pdf::loadView('exports.history-pengadaan-barang-masuk-pdf', compact('barangMasuk', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('history-input-barang.pdf');
    }
}