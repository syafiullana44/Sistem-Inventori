<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_gudang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_header')->constrained('permintaan_gudang_header')->onDelete('cascade');
            $table->foreignId('id_bahan')->constrained('master_bahan')->onDelete('cascade');
            $table->integer('jumlah_diminta');
            $table->integer('jumlah_datang')->default(0);
            $table->enum('status_item', ['Menunggu', 'Datang', 'Tidak Datang'])->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_gudang_detail');
    }
};