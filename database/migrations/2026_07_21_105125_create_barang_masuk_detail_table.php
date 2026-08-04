<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_barang_masuk')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('id_bahan')->constrained('master_bahan')->onDelete('cascade');
            $table->integer('jumlah_diterima');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk_detail');
    }
};