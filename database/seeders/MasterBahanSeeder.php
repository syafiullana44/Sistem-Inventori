<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterBahanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_bahan')->insert([
            // Palet Jati Belanda - Papan
            [
                'kode_bahan' => 'PJB-120-14',
                'nama_bahan' => 'Palet Jati Belanda - Papan',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 120 X L: 14 X T: 1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-120-9',
                'nama_bahan' => 'Palet Jati Belanda - Papan',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 120 X L: 9 X T: 1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-113-8',
                'nama_bahan' => 'Palet Jati Belanda - Papan',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 113 X L: 8 X T: 1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-85-8',
                'nama_bahan' => 'Palet Jati Belanda - Papan',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 85 X L: 8 X T: 1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Palet Jati Belanda - Balok/Usuk
            [
                'kode_bahan' => 'PJB-4x6-113',
                'nama_bahan' => 'Palet Jati Belanda - Balok',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 113 X L: 6 X T: 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-4x9-113',
                'nama_bahan' => 'Palet Jati Belanda - Balok',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 113 X L: 9 X T: 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Palet Kayu Keras - Papan
            [
                'kode_bahan' => 'PKK-113-9',
                'nama_bahan' => 'Palet Kayu Keras - Papan',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 113 X L: 9 X T: 1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PKK-85-9',
                'nama_bahan' => 'Palet Kayu Keras - Papan',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 85 X L: 9 X T: 1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Palet Kayu Keras - Balok/Usuk
            [
                'kode_bahan' => 'PKK-4x9-113',
                'nama_bahan' => 'Palet Kayu Keras - Balok',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => 'P: 113 X L: 9 X T: 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Lem
            [
                'kode_bahan' => 'LEM-600',
                'nama_bahan' => 'Lem Presto',
                'satuan' => 'Botol',
                'stok_saat_ini' => 0,
                'stok_minimum' => 5,
                'deskripsi' => '600 Gram',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}