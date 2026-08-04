<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanGudangHeader extends Model
{
    use HasFactory;

    protected $table = 'permintaan_gudang_header';

    protected $fillable = [
        'kode_pg',
        'id_user_gudang',
        'status',
        'keterangan',
        'tanggal_selesai',
    ];

    public function userGudang()
    {
        return $this->belongsTo(User::class, 'id_user_gudang');
    }

    public function details()
    {
        return $this->hasMany(PermintaanGudangDetail::class, 'id_header');
    }

    public function barangMasuk()
    {
        return $this->hasOne(BarangMasuk::class, 'id_permintaan_gudang');
    }
}