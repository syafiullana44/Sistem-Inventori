<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'kode_brg_masuk',
        'id_permintaan_gudang',
        'id_user_pengadaan',
        'id_user_gudang',
        'status',
        'catatan',
        'tanggal_diverifikasi',
    ];

    public function permintaanGudang()
    {
        return $this->belongsTo(PermintaanGudangHeader::class, 'id_permintaan_gudang');
    }

    public function userPengadaan()
    {
        return $this->belongsTo(User::class, 'id_user_pengadaan');
    }

    public function userGudang()
    {
        return $this->belongsTo(User::class, 'id_user_gudang');
    }

    public function details()
    {
        return $this->hasMany(BarangMasukDetail::class, 'id_barang_masuk');
    }
}