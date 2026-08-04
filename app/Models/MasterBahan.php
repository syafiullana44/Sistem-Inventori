<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBahan extends Model
{
    use HasFactory;

    protected $table = 'master_bahan';

    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'satuan',
        'stok_saat_ini',
        'stok_minimum',
        'deskripsi',
    ];

    // TAMBAHKAN RELASI INI
    public function stokBatch()
    {
        return $this->hasMany(BatchStok::class, 'id_bahan');
    }

    public function stokMenipis()
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }
}