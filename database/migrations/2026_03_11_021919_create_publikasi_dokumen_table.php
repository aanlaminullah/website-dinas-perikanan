<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasi_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->nullable();
            $table->string('file');
            $table->string('tipe_file')->nullable();
            $table->unsignedBigInteger('ukuran_file')->nullable();
            $table->year('tahun');
            $table->date('tanggal');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi_dokumen');
    }
};
