<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menghapus tabel lapisan peta.
 *
 * Peta wilayah kini ditampilkan sebagai gambar yang diunggah lewat
 * Profil Desa, sehingga penyimpanan berkas GeoJSON tidak diperlukan lagi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('peta_layer');
    }

    public function down(): void
    {
        Schema::create('peta_layer', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('keterangan')->nullable();
            $table->string('file_path');
            $table->string('warna', 7)->default('#0E5C3A');
            $table->unsignedTinyInteger('opasitas')->default(25);
            $table->boolean('tampil_di_pengaduan')->default(false);
            $table->boolean('aktif')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }
};
