<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanProduksiDetail extends Model
{
    use HasFactory;

    protected $table = 'permintaan_produksi_detail';

    protected $fillable = [
        'id_header',
        'id_bahan',
        'jumlah_diminta',
        'jumlah_dikeluarkan',
        'status_item',
    ];

    public function header()
    {
        return $this->belongsTo(PermintaanProduksiHeader::class, 'id_header');
    }

    public function bahan()
    {
        return $this->belongsTo(MasterBahan::class, 'id_bahan');
    }
}