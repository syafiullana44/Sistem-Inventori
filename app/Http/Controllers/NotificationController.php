<?php

namespace App\Http\Controllers;

use App\Models\PermintaanProduksiHeader;
use App\Models\BarangMasuk;
use App\Models\MasterBahan;
use App\Models\PermintaanGudangHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifikasi(Request $request)
    {
        // 🔥 Lepaskan session lock secepatnya agar tidak nge-block loading halaman lain
        $request->session()->save();

        $user = Auth::user();
        $role = $user->role;
        $notifikasi = [];

        if ($role == 'gudang') {
            $prCount = PermintaanProduksiHeader::where('status', 'Menunggu')->count();
            if ($prCount > 0) {
                $notifikasi[] = [
                    'icon' => 'fa-clipboard-list',
                    'color' => 'warning',
                    'message' => $prCount . ' permintaan produksi menunggu',
                    'link' => route('gudang.permintaan-produksi.index'), // FIXED ROUTE
                ];
            }

            $bmCount = BarangMasuk::where('status', 'Draft')->count();
            if ($bmCount > 0) {
                $notifikasi[] = [
                    'icon' => 'fa-box',
                    'color' => 'info',
                    'message' => $bmCount . ' barang masuk menunggu verifikasi',
                    'link' => route('gudang.barang-masuk.index'),
                ];
            }

            $stokCount = MasterBahan::whereRaw('stok_saat_ini <= stok_minimum')->count();
            if ($stokCount > 0) {
                $notifikasi[] = [
                    'icon' => 'fa-exclamation-triangle',
                    'color' => 'danger',
                    'message' => $stokCount . ' bahan stok menipis!',
                    'link' => route('gudang.stok.index'), // FIXED ROUTE
                ];
            }
        }

        if ($role == 'pengadaan') {
            $pgCount = PermintaanGudangHeader::whereIn('status', ['Diproses', 'Sebagian'])
                ->whereDoesntHave('barangMasuk', function($q) {
                    $q->where('status', 'Draft');
                })->count();
            if ($pgCount > 0) {
                $notifikasi[] = [
                    'icon' => 'fa-shopping-cart',
                    'color' => 'warning',
                    'message' => $pgCount . ' permintaan pengadaan menunggu',
                    'link' => route('pengadaan.permintaan.index'),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'count' => count($notifikasi),
            'data' => $notifikasi,
        ]);
    }
}