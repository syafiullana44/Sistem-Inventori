<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\BatchStok;
use App\Models\MasterBahan;
use App\Models\PermintaanGudangDetail;
use App\Models\PermintaanGudangHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BarangMasukGudangController extends Controller
{
    public function index()
    {
        $barangMasuk = BarangMasuk::with(['permintaanGudang', 'userPengadaan', 'details.bahan'])
            ->where('id_user_gudang', Auth::id())
            ->orWhere('status', 'Draft')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gudang.barang-masuk.index', compact('barangMasuk'));
    }

    public function verifikasi($id)
    {
        $barangMasuk = BarangMasuk::with(['permintaanGudang', 'details.bahan'])->findOrFail($id);
        return view('gudang.barang-masuk.verifikasi', compact('barangMasuk'));
    }

    public function konfirmasi($id)
    {
        $barangMasuk = BarangMasuk::with(['details.bahan'])->findOrFail($id);

        // ── IDEMPOTENCY GUARD ──────────────────────────────────────────────
        // Hanya proses jika status masih Draft
        if ($barangMasuk->status !== 'Draft') {
            return redirect()->route('gudang.barang-masuk.index')
                ->with('info', 'Barang masuk ini sudah pernah diproses sebelumnya (status: ' . $barangMasuk->status . ').');
        }

        DB::beginTransaction();
        try {
            // Ambil id terbesar sekali sebelum loop agar kode_batch unik setiap iterasi
            $batchCounter = BatchStok::max('id') ?? 0;

            foreach ($barangMasuk->details as $detail) {
                // Naikkan counter terlebih dahulu supaya setiap batch mendapat nomor berbeda
                $batchCounter++;
                $kodeBatch = 'BATCH-' . $detail->id_bahan . '-' . date('Ymd') . '-' . str_pad($batchCounter, 3, '0', STR_PAD_LEFT);

                BatchStok::create([
                    'id_bahan'        => $detail->id_bahan,
                    'kode_batch'      => $kodeBatch,
                    'jumlah_masuk'    => $detail->jumlah_diterima,
                    'sisa_stok'       => $detail->jumlah_diterima,
                    'tanggal_masuk'   => date('Y-m-d'),
                    'id_barang_masuk' => $barangMasuk->id,
                ]);

                // Update stok bahan
                $bahan = MasterBahan::find($detail->id_bahan);
                if ($bahan) {
                    $bahan->stok_saat_ini += $detail->jumlah_diterima;
                    $bahan->save();
                }

                // Update detail permintaan gudang – gunakan nilai fresh dari DB
                $pgDetail = PermintaanGudangDetail::where('id_header', $barangMasuk->id_permintaan_gudang)
                    ->where('id_bahan', $detail->id_bahan)
                    ->lockForUpdate()
                    ->first();

                if ($pgDetail) {
                    $pgDetail->jumlah_datang += $detail->jumlah_diterima;
                    $pgDetail->status_item = ($pgDetail->jumlah_datang >= $pgDetail->jumlah_diminta)
                        ? 'Datang'
                        : 'Menunggu';
                    $pgDetail->save();
                }
            }

            // Update status barang masuk
            $barangMasuk->status = 'Diverifikasi';
            $barangMasuk->id_user_gudang = Auth::id();
            $barangMasuk->tanggal_diverifikasi = now();
            $barangMasuk->save();

            // Update status permintaan gudang
            $pg = PermintaanGudangHeader::find($barangMasuk->id_permintaan_gudang);
            $totalDetail = $pg->details()->count();
            $detailDatang = $pg->details()->where('status_item', 'Datang')->count();

            if ($detailDatang == $totalDetail) {
                $pg->status = 'Selesai';
                $pg->tanggal_selesai = now();
            } else {
                $pg->status = 'Sebagian';
            }
            $pg->save();

            DB::commit();

            // Clear stok cache agar monitoring stok langsung update
            Cache::forget('stok_all');
            foreach ($barangMasuk->details as $detail) {
                Cache::forget('stok_batch_' . $detail->id_bahan);
            }

            return redirect()->route('gudang.barang-masuk.index')->with('success', 'Barang berhasil diverifikasi! Stok bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function tolak($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->status = 'Ditolak';
        $barangMasuk->save();

        return redirect()->route('gudang.barang-masuk.index')->with('success', 'Barang masuk ditolak!');
    }
}