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
                'nama_bahan' => 'Palet Jati Belanda Papan - 120x14x1.5 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-120-9',
                'nama_bahan' => 'Palet Jati Belanda Papan - 120x9x1.5 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-113-8',
                'nama_bahan' => 'Palet Jati Belanda Papan - 113x8x1.5 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-85-8',
                'nama_bahan' => 'Palet Jati Belanda Papan - 85x8x1.5 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Palet Jati Belanda - Balok/Usuk
            [
                'kode_bahan' => 'PJB-4x6-113',
                'nama_bahan' => 'Palet Jati Belanda Balok - 113x6x4 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PJB-4x9-113',
                'nama_bahan' => 'Palet Jati Belanda Balok - 113x9x4 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Palet Kayu Keras - Papan
            [
                'kode_bahan' => 'PKK-113-9',
                'nama_bahan' => 'Palet Kayu Keras Papan - 113x9x1.5 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'PKK-85-9',
                'nama_bahan' => 'Palet Kayu Keras Papan - 85x9x1.5 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Palet Kayu Keras - Balok/Usuk
            [
                'kode_bahan' => 'PKK-4x9-113',
                'nama_bahan' => 'Palet Kayu Keras Balok - 113x9x4 cm',
                'satuan' => 'Unit',
                'stok_saat_ini' => 0,
                'stok_minimum' => 10,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Lem
            [
                'kode_bahan' => 'LEM-600',
                'nama_bahan' => 'Lem Presto 600 Gram',
                'satuan' => 'Botol',
                'stok_saat_ini' => 0,
                'stok_minimum' => 5,
                'deskripsi' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}