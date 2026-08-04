<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_gudang_header', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pg', 20)->unique();
            $table->foreignId('id_user_gudang')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Diproses', 'Sebagian', 'Selesai', 'Ditolak'])->default('Diproses');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_gudang_header');
    }
};