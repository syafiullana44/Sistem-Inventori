<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use App\Models\BatchStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StokController extends Controller
{
    /**
     * Menampilkan semua bahan dan total stok
     */
    public function index()
    {
        // 🔥 PAKAI CACHE 5 MENIT
        $bahan = Cache::remember('stok_all', 300, function() {
            return MasterBahan::with(['stokBatch' => function($query) {
                $query->where('sisa_stok', '>', 0)
                      ->orderBy('tanggal_masuk', 'asc');
            }])->get();
        });

        // Hitung total stok per bahan
        foreach ($bahan as $item) {
            $item->total_stok = $item->stokBatch->sum('sisa_stok');
        }

        return view('gudang.stok.index', compact('bahan'));
    }

    /**
     * Menampilkan detail batch dari suatu bahan
     */
    public function detailBatch($id)
    {
        // 🔥 PAKAI CACHE PER ID
        $cacheKey = 'stok_batch_' . $id;
        
        $data = Cache::remember($cacheKey, 300, function() use ($id) {
            $bahan = MasterBahan::findOrFail($id);
            
            $batches = BatchStok::where('id_bahan', $id)
                ->where('sisa_stok', '>', 0)
                ->orderBy('tanggal_masuk', 'asc')
                ->get();

            $totalStok = $batches->sum('sisa_stok');

            return compact('bahan', 'batches', 'totalStok');
        });

        return view('gudang.stok.detail', $data);
    }

    /**
     * Clear cache stok (panggil setelah update stok)
     */
    public function clearCache()
    {
        Cache::forget('stok_all');
        return response()->json(['message' => 'Cache cleared']);
    }
}