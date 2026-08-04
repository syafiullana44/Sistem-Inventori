<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bahan')->constrained('master_bahan')->onDelete('cascade');
            $table->string('kode_batch', 50)->unique();
            $table->integer('jumlah_masuk');
            $table->integer('sisa_stok');
            $table->date('tanggal_masuk');
            
            // Foreign key ke barang_masuk (tabel sudah ada)
            $table->foreignId('id_barang_masuk')->nullable()->constrained('barang_masuk')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_stok');
    }
};