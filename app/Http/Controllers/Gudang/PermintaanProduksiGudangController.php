<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\PermintaanProduksiHeader;
use App\Models\BatchStok;
use App\Models\MasterBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermintaanProduksiGudangController extends Controller
{
    public function index()
    {
        $permintaan = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])
            ->whereIn('status', ['Menunggu', 'Diproses'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('gudang.permintaan.index', compact('permintaan'));
    }

    public function proses($id)
    {
        $permintaan = PermintaanProduksiHeader::with(['userProduksi', 'details.bahan'])->findOrFail($id);

        if ($permintaan->status == 'Menunggu') {
            $permintaan->status = 'Diproses';
            $permintaan->tanggal_diproses = now();
            $permintaan->save();
        }

        $cekStok = [];
        $stokCukup = true;

        foreach ($permintaan->details as $detail) {
            $stok = MasterBahan::find($detail->id_bahan);
            $tersedia = $stok ? $stok->stok_saat_ini : 0;
            $cukup = $tersedia >= $detail->jumlah_diminta;

            $cekStok[] = [
                'bahan' => $detail->bahan->nama_bahan ?? '-',
                'diminta' => $detail->jumlah_diminta,
                'tersedia' => $tersedia,
                'cukup' => $cukup,
                'satuan' => $detail->bahan->satuan ?? '',
            ];

            if (!$cukup) {
                $stokCukup = false;
            }
        }

        return view('gudang.permintaan.proses', compact('permintaan', 'cekStok', 'stokCukup'));
    }

    public function prosesFIFO($id)
    {
        $permintaan = PermintaanProduksiHeader::with('details.bahan')->findOrFail($id);

        DB::beginTransaction();
        try {
            $semuaSelesai = true;
            $adaDikeluarkan = false;

            foreach ($permintaan->details as $detail) {
                $jumlahDiminta = $detail->jumlah_diminta;
                $sisaDiminta   = $jumlahDiminta;
                $dikeluarkan   = 0;

                // FIFO: ambil dari batch paling lama masuk
                $batches = BatchStok::where('id_bahan', $detail->id_bahan)
                    ->where('sisa_stok', '>', 0)
                    ->orderBy('tanggal_masuk', 'asc')
                    ->orderBy('id', 'asc')  // tiebreaker agar urutan konsisten
                    ->lockForUpdate()
                    ->get();

                foreach ($batches as $batch) {
                    if ($sisaDiminta <= 0) break;

                    $ambil = min($batch->sisa_stok, $sisaDiminta);
                    $batch->sisa_stok -= $ambil;
                    $batch->save();

                    $sisaDiminta -= $ambil;
                    $dikeluarkan += $ambil;
                    $adaDikeluarkan = true;
                }

                // Set nilai langsung (bukan +=) untuk menghindari double-count
                $detail->jumlah_dikeluarkan = $dikeluarkan;

                if ($dikeluarkan >= $jumlahDiminta) {
                    $detail->status_item = 'Dikeluarkan';
                } else {
                    // Baik dikeluarkan sebagian maupun nol, tetap Tidak Tersedia
                    $detail->status_item = 'Tidak Tersedia';
                    $semuaSelesai = false;
                }
                $detail->save();

                // Kurangi stok master bahan
                $bahan = MasterBahan::find($detail->id_bahan);
                if ($bahan) {
                    $bahan->stok_saat_ini -= $dikeluarkan;
                    $bahan->save();
                }
            }

            if ($semuaSelesai && $adaDikeluarkan) {
                $permintaan->status = 'Selesai';
                $permintaan->tanggal_selesai = now();
                $message = 'Semua bahan berhasil dikeluarkan!';
            } elseif ($adaDikeluarkan && !$semuaSelesai) {
                $permintaan->status = 'Sebagian';
                $message = 'Sebagian bahan berhasil dikeluarkan!';
            } else {
                $permintaan->status = 'Ditolak';
                $message = 'Tidak ada bahan yang bisa dikeluarkan!';
            }
            $permintaan->save();

            DB::commit();

            // Clear stok cache agar monitoring stok langsung update
            Cache::forget('stok_all');
            foreach ($permintaan->details as $detail) {
                Cache::forget('stok_batch_' . $detail->id_bahan);
            }

            return redirect()->route('gudang.permintaan-produksi.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);

        $permintaan = PermintaanProduksiHeader::findOrFail($id);
        $permintaan->status = 'Ditolak';
        $permintaan->keterangan = $request->alasan;
        $permintaan->save();

        return redirect()->route('gudang.permintaan-produksi.index')->with('success', 'Permintaan ditolak!');
    }
}