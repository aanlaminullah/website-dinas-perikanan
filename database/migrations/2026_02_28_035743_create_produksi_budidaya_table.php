<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksi_budidaya', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kecamatan', 20);   // contoh: 71.08.01
            $table->string('kecamatan', 100);        // contoh: Sangkub
            $table->string('komoditas', 100);        // contoh: Udang Vaname
            $table->decimal('januari',   8, 3)->nullable()->default(0);
            $table->decimal('februari',  8, 3)->nullable()->default(0);
            $table->decimal('maret',     8, 3)->nullable()->default(0);
            $table->decimal('april',     8, 3)->nullable()->default(0);
            $table->decimal('mei',       8, 3)->nullable()->default(0);
            $table->decimal('juni',      8, 3)->nullable()->default(0);
            $table->decimal('juli',      8, 3)->nullable()->default(0);
            $table->decimal('agustus',   8, 3)->nullable()->default(0);
            $table->decimal('september', 8, 3)->nullable()->default(0);
            $table->decimal('oktober',   8, 3)->nullable()->default(0);
            $table->decimal('november',  8, 3)->nullable()->default(0);
            $table->decimal('desember',  8, 3)->nullable()->default(0);
            $table->decimal('jumlah',    10, 3)->nullable()->default(0);
            $table->year('tahun')->default(2025);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produksi_budidaya');
    }
};
