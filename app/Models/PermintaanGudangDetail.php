<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanGudangDetail extends Model
{
    use HasFactory;

    protected $table = 'permintaan_gudang_detail';

    protected $fillable = [
        'id_header',
        'id_bahan',
        'jumlah_diminta',
        'jumlah_datang',
        'status_item',
    ];

    public function header()
    {
        return $this->belongsTo(PermintaanGudangHeader::class, 'id_header');
    }

    public function bahan()
    {
        return $this->belongsTo(MasterBahan::class, 'id_bahan');
    }
}