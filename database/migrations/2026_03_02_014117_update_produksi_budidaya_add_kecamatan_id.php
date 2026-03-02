<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produksi_budidaya', function (Blueprint $table) {
            // Tambah foreign key
            $table->foreignId('kecamatan_id')
                ->after('id')
                ->constrained('kecamatan')
                ->cascadeOnDelete();

            // Hapus kolom lama yang sudah tidak diperlukan
            $table->dropColumn(['kode_kecamatan', 'kecamatan']);
        });
    }

    public function down(): void
    {
        Schema::table('produksi_budidaya', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn('kecamatan_id');
            $table->string('kode_kecamatan', 10)->after('id');
            $table->string('kecamatan', 100)->after('kode_kecamatan');
        });
    }
};
