<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_detail';

    protected $fillable = [
        'id_barang_masuk',
        'id_bahan',
        'jumlah_diterima',
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk');
    }

    public function bahan()
    {
        return $this->belongsTo(MasterBahan::class, 'id_bahan');
    }
}