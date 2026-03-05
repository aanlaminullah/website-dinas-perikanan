<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visi_misi', function (Blueprint $table) {
            $table->id();
            $table->text('visi');
            $table->timestamps();
        });

        Schema::create('misi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visi_misi_id')->constrained('visi_misi')->onDelete('cascade');
            $table->text('isi');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('misi');
        Schema::dropIfExists('visi_misi');
    }
};
