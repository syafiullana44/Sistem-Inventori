<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanProduksiHeader extends Model
{
    use HasFactory;

    protected $table = 'permintaan_produksi_header';

    protected $fillable = [
        'kode_pr',
        'id_user_produksi',
        'status',
        'keterangan',
        'tanggal_diproses',
        'tanggal_selesai',
    ];

    // [BARU] Timestamp otomatis untuk created_at, updated_at
    public $timestamps = true;

    public function userProduksi()
    {
        return $this->belongsTo(User::class, 'id_user_produksi');
    }

    public function details()
    {
        return $this->hasMany(PermintaanProduksiDetail::class, 'id_header');
    }
}