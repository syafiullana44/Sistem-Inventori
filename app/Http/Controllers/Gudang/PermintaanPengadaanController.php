<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use App\Models\PermintaanGudangHeader;
use App\Models\PermintaanGudangDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermintaanPengadaanController extends Controller
{
    public function create()
    {
        $bahan = MasterBahan::whereRaw('stok_saat_ini <= stok_minimum')->select('id', 'nama_bahan', 'kode_bahan', 'satuan', 'stok_saat_ini', 'stok_minimum', 'deskripsi')->get();
        $semuaBahan = MasterBahan::select('id', 'nama_bahan', 'kode_bahan', 'satuan', 'stok_saat_ini', 'stok_minimum', 'deskripsi')->orderBy('nama_bahan')->get();

        return view('gudang.permintaan-pengadaan.create', compact('bahan', 'semuaBahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan'          => 'required|array|min:1',
            'bahan.*.id'     => 'required|exists:master_bahan,id',
            'bahan.*.jumlah' => 'required|integer|min:1',
            'keterangan'     => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // ── IDEMPOTENCY GUARD ──────────────────────────────────────────────
            // Cegah double-submit: tolak jika user yang sama sudah membuat
            // permintaan dalam 60 detik terakhir (masih berstatus Diproses).
            $sudahAda = PermintaanGudangHeader::where('id_user_gudang', Auth::id())
                ->where('status', 'Diproses')
                ->where('created_at', '>=', now()->subSeconds(60))
                ->lockForUpdate()
                ->exists();

            if ($sudahAda) {
                DB::rollBack();
                return redirect()->route('gudang.dashboard')
                    ->with('info', 'Permintaan pengadaan baru saja sudah dikirim. Mohon tunggu sebentar sebelum mengirim lagi.');
            }

            // ── KODE UNIK AMAN DARI RACE CONDITION ────────────────────────────
            $lastRecord = PermintaanGudangHeader::whereDate('created_at', today())
                ->lockForUpdate()
                ->orderBy('kode_pg', 'desc')
                ->first();

            $nextSequence = 1;
            if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->kode_pg, $matches)) {
                $nextSequence = (int) $matches[1] + 1;
            }

            $kode = 'PG-' . date('Ymd') . '-' . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            $header = PermintaanGudangHeader::create([
                'kode_pg'        => $kode,
                'id_user_gudang' => Auth::id(),
                'status'         => 'Diproses',
                'keterangan'     => $request->keterangan,
            ]);

            foreach ($request->bahan as $item) {
                PermintaanGudangDetail::create([
                    'id_header'      => $header->id,
                    'id_bahan'       => $item['id'],
                    'jumlah_diminta' => $item['jumlah'],
                    'jumlah_datang'  => 0,
                    'status_item'    => 'Menunggu',
                ]);
            }

            DB::commit();
            return redirect()->route('gudang.dashboard')
                ->with('success', 'Permintaan pengadaan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat permintaan: ' . $e->getMessage());
        }
    }
}