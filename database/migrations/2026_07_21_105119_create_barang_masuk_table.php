<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_brg_masuk', 20)->unique();
            $table->foreignId('id_permintaan_gudang')->constrained('permintaan_gudang_header')->onDelete('cascade');
            $table->foreignId('id_user_pengadaan')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_user_gudang')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['Draft', 'Diverifikasi', 'Ditolak'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_diverifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};