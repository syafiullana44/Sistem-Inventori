<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\PermintaanProduksiHeader;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
            ->where('status', 'Selesai');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_selesai', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_akhir);
        }

        $pengeluaran = $query->orderBy('tanggal_selesai', 'desc')->paginate(20);

        return view('gudang.pengeluaran-history', compact('pengeluaran'));
    }

        // [TAMBAH] Export PDF
    public function exportPdf(Request $request)
    {
        $query = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
            ->where('status', 'Selesai');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_selesai', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_akhir);
        }

        $pengeluaran = $query->orderBy('tanggal_selesai', 'desc')->get();
        $title = 'History Pengeluaran Barang';

        $pdf = Pdf::loadView('exports.history-pengeluaran-pdf', compact('pengeluaran', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('history-pengeluaran-barang.pdf');
    }
}