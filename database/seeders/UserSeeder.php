<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'nama_lengkap' => 'Admin Utama',
                'email' => 'admin@srwood.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'produksi',
                'nama_lengkap' => 'Budi Santoso',
                'email' => 'produksi@srwood.com',
                'password' => Hash::make('produksi'),
                'role' => 'produksi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'gudang',
                'nama_lengkap' => 'Siti Rahayu',
                'email' => 'gudang@srwood.com',
                'password' => Hash::make('gudang'),
                'role' => 'gudang',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'pengadaan',
                'nama_lengkap' => 'Ahmad Fauzi',
                'email' => 'pengadaan@srwood.com',
                'password' => Hash::make('pengadaan'),
                'role' => 'pengadaan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}