<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->string('no_tiket', 30)->unique(); // format: LPR-20260727-0001

            $table->foreignId('kategori_laporan_id')->constrained('kategori_laporan');
            $table->foreignId('dusun_id')->nullable()->constrained('dusun')->nullOnDelete();

            // pelapor
            $table->boolean('anonim')->default(false);
            $table->string('nama_pelapor')->nullable();
            $table->string('no_hp')->nullable();

            // isi laporan
            $table->string('judul');
            $table->text('deskripsi');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('alamat_lokasi')->nullable();
            $table->date('tanggal_kejadian');

            // status
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->text('alasan_tolak')->nullable();
            $table->string('dokumentasi_selesai_path')->nullable();
            $table->timestamp('selesai_at')->nullable();

            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
