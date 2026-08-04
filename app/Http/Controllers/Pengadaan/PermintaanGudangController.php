<?php

namespace App\Http\Controllers\Pengadaan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanGudangHeader;
use Illuminate\Http\Request;

class PermintaanGudangController extends Controller
{
    public function index()
    {
        $permintaan = PermintaanGudangHeader::with(['userGudang', 'details.bahan'])
            ->whereIn('status', ['Diproses', 'Sebagian'])
            ->whereDoesntHave('barangMasuk', function($q) {
                $q->where('status', 'Draft');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pengadaan.permintaan.index', compact('permintaan'));
    }

    public function proses($id)
    {
        $permintaan = PermintaanGudangHeader::with(['userGudang', 'details.bahan'])->findOrFail($id);
        return view('pengadaan.permintaan.proses', compact('permintaan'));
    }
}