<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('potensi_desa', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['umkm', 'pertanian', 'wisata', 'peternakan', 'kerajinan']);
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('kontak')->nullable(); // no HP/WA pemilik UMKM misalnya
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('potensi_desa');
    }
};
