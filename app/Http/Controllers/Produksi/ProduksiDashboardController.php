<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use App\Models\PermintaanProduksiHeader;
use Illuminate\Support\Facades\Auth;

class ProduksiDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'bahan' => MasterBahan::paginate(10),
            'permintaan' => PermintaanProduksiHeader::with(['details.bahan'])
                ->where('id_user_produksi', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];
        return view('produksi.dashboard', $data);
    }
}