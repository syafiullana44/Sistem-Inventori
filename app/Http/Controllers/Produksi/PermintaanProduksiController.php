<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use App\Models\PermintaanProduksiHeader;
use App\Models\PermintaanProduksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermintaanProduksiController extends Controller
{
    public function create()
    {
        return view('produksi.permintaan.create', [
            'bahan' => MasterBahan::select('id', 'nama_bahan', 'kode_bahan', 'satuan', 'stok_saat_ini', 'deskripsi')->orderBy('nama_bahan')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan' => 'required|array|min:1',
            'bahan.*.id' => 'required|exists:master_bahan,id',
            'bahan.*.jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $lastRecord = PermintaanProduksiHeader::whereDate('created_at', today())
                ->lockForUpdate()
                ->orderBy('kode_pr', 'desc')
                ->first();

            $nextSequence = 1;
            if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->kode_pr, $matches)) {
                $nextSequence = (int) $matches[1] + 1;
            }

            $kode = 'PR-' . date('Ymd') . '-' . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            $header = PermintaanProduksiHeader::create([
                'kode_pr' => $kode,
                'id_user_produksi' => Auth::id(),
                'status' => 'Menunggu',
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->bahan as $item) {
                PermintaanProduksiDetail::create([
                    'id_header' => $header->id,
                    'id_bahan' => $item['id'],
                    'jumlah_diminta' => $item['jumlah'],
                    'jumlah_dikeluarkan' => 0,
                    'status_item' => 'Menunggu',
                ]);
            }

            DB::commit();
            return redirect()->route('produksi.dashboard')->with('success', 'Permintaan berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat permintaan: ' . $e->getMessage());
        }
    }
}