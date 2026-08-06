<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peta_layer', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                       // contoh: Batas Desa, Lahan Sawah
            $table->string('keterangan')->nullable();
            $table->string('file_path');                  // berkas GeoJSON di storage
            $table->string('warna', 7)->default('#0E5C3A'); // warna garis & isian
            $table->unsignedTinyInteger('opasitas')->default(25); // kepekatan isian, 0–100
            $table->boolean('tampil_di_pengaduan')->default(false); // ikut tampil di peta laporan
            $table->boolean('aktif')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peta_layer');
    }
};
