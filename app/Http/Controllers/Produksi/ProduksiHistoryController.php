<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\PermintaanProduksiHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ProduksiHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
            ->where('id_user_produksi', Auth::id());

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $permintaan = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('produksi.history', compact('permintaan'));
    }

    // [TAMBAH] Export PDF
    public function exportPdf(Request $request)
    {
        $query = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
            ->where('id_user_produksi', Auth::id());

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $permintaan = $query->orderBy('created_at', 'desc')->get();
        $title = 'History Permintaan Produksi';

        $pdf = Pdf::loadView('exports.history-produksi-pdf', compact('permintaan', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('history-permintaan-produksi.pdf');
    }
}