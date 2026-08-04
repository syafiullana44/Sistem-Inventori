<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'nama_lengkap',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin() { return $this->role === 'admin'; }
    public function isProduksi() { return $this->role === 'produksi'; }
    public function isGudang() { return $this->role === 'gudang'; }
    public function isPengadaan() { return $this->role === 'pengadaan'; }
}