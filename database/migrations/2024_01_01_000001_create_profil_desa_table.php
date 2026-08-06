<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->text('sejarah')->nullable();
            $table->text('visi')->nullable();
            $table->longText('misi')->nullable(); // simpan sebagai JSON array poin misi
            $table->decimal('luas_wilayah_ha', 10, 3)->nullable();
            $table->string('batas_utara')->nullable();
            $table->string('batas_selatan')->nullable();
            $table->string('batas_timur')->nullable();
            $table->string('batas_barat')->nullable();
            $table->decimal('jarak_ke_kabupaten_km', 8, 2)->nullable();
            $table->decimal('jarak_ke_kecamatan_km', 8, 2)->nullable();
            $table->string('nama_kepala_desa')->nullable();
            $table->string('peta_wilayah_path')->nullable(); // path gambar peta sosial
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_desa');
    }
};
