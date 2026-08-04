<?php

namespace App\Http\Controllers\Pengadaan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanGudangHeader;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PermintaanPengadaanHistoryController extends Controller
{
    /**
     * Menampilkan history permintaan pengadaan yang sudah diproses oleh pengadaan
     */
    public function index(Request $request)
    {
        $query = PermintaanGudangHeader::with(['userGudang', 'details.bahan'])
            ->whereIn('status', ['Selesai', 'Sebagian', 'Ditolak']);

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

        return view('pengadaan.permintaan-history', compact('permintaan'));
    }

    // [TAMBAH] Export PDF
    public function exportPdf(Request $request)
    {
        $query = PermintaanGudangHeader::with(['userGudang', 'details.bahan'])
            ->whereIn('status', ['Selesai', 'Sebagian', 'Ditolak']);

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

        $pdf = Pdf::loadView('exports.history-pengadaan-permintaan-pdf', compact('permintaan', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('history-permintaan-pengadaan.pdf');
    }
}