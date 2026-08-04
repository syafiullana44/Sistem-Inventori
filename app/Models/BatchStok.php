<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchStok extends Model
{
    use HasFactory;

    protected $table = 'batch_stok';

    protected $fillable = [
        'id_bahan',
        'kode_batch',
        'jumlah_masuk',
        'sisa_stok',
        'tanggal_masuk',
        'id_barang_masuk',
    ];

    public function bahan()
    {
        return $this->belongsTo(MasterBahan::class, 'id_bahan');
    }

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk');
    }
}