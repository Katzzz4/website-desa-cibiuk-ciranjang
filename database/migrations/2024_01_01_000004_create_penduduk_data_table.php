<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_kategori_id')->constrained('penduduk_kategori')->cascadeOnDelete();
            $table->foreignId('dusun_id')->nullable()->constrained('dusun')->nullOnDelete(); // null = data desa keseluruhan
            $table->string('label');            // "Petani/Pekebun", "SLTA", "Islam", "0-4 Tahun"
            $table->unsignedInteger('jumlah_laki')->default(0);
            $table->unsignedInteger('jumlah_perempuan')->default(0);
            $table->unsignedSmallInteger('tahun');
            $table->timestamps();

            $table->index(['penduduk_kategori_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk_data');
    }
};
