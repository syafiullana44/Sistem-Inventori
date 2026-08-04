<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\PermintaanGudangHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PermintaanPengadaanHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanGudangHeader::with(['userGudang', 'details.bahan'])
            ->where('id_user_gudang', Auth::id());

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

        return view('gudang.permintaan-pengadaan-history', compact('permintaan'));
    }

    // [TAMBAH] Export PDF
    public function exportPdf(Request $request)
    {
        $query = PermintaanGudangHeader::with(['userGudang', 'details.bahan'])
            ->where('id_user_gudang', Auth::id());

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
        $title = 'History Permintaan Pengadaan';

        $pdf = Pdf::loadView('exports.history-permintaan-pengadaan-pdf', compact('permintaan', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('history-permintaan-pengadaan.pdf');
    }
}