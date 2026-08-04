<?php

namespace App\Http\Controllers\Pengadaan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanGudangHeader;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function create($id)
    {
        $permintaan = PermintaanGudangHeader::with(['details.bahan'])->findOrFail($id);
        return view('pengadaan.barang-masuk.create', compact('permintaan'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'jumlah.*' => 'required|integer|min:0',
            'catatan'  => 'nullable|string',
        ]);

        $permintaan = PermintaanGudangHeader::with('details')->findOrFail($id);

        // Cek apakah ada yang diinput
        $adaInput = false;
        foreach ($request->jumlah as $jml) {
            if ($jml > 0) {
                $adaInput = true;
                break;
            }
        }

        if (!$adaInput) {
            return back()->with('warning', 'Minimal input 1 barang!');
        }

        DB::beginTransaction();
        try {
            // ── IDEMPOTENCY GUARD ──────────────────────────────────────────────
            // Cegah double-submit: jika sudah ada barang masuk Draft untuk
            // permintaan ini, abaikan request dan kembalikan pesan informatif.
            $sudahAda = BarangMasuk::where('id_permintaan_gudang', $permintaan->id)
                ->where('status', 'Draft')
                ->lockForUpdate()
                ->exists();

            if ($sudahAda) {
                DB::rollBack();
                return redirect()->route('pengadaan.dashboard')
                    ->with('info', 'Barang masuk untuk permintaan ini sedang menunggu verifikasi Gudang.');
            }

            // ── KODE UNIK AMAN DARI RACE CONDITION ────────────────────────────
            $lastRecord = BarangMasuk::whereDate('created_at', today())
                ->lockForUpdate()
                ->orderBy('kode_brg_masuk', 'desc')
                ->first();

            $nextSequence = 1;
            if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->kode_brg_masuk, $matches)) {
                $nextSequence = (int) $matches[1] + 1;
            }

            $kode = 'BM-' . date('Ymd') . '-' . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            $barangMasuk = BarangMasuk::create([
                'kode_brg_masuk'       => $kode,
                'id_permintaan_gudang' => $permintaan->id,
                'id_user_pengadaan'    => Auth::id(),
                'status'               => 'Draft',
                'catatan'              => $request->catatan,
            ]);

            foreach ($permintaan->details as $detail) {
                $jumlah = $request->jumlah[$detail->id] ?? 0;

                if ($jumlah > 0) {
                    BarangMasukDetail::create([
                        'id_barang_masuk' => $barangMasuk->id,
                        'id_bahan'        => $detail->id_bahan,
                        'jumlah_diterima' => $jumlah,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('pengadaan.dashboard')
                ->with('success', 'Barang masuk berhasil diinput! Menunggu verifikasi Gudang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal input barang: ' . $e->getMessage());
        }
    }
}