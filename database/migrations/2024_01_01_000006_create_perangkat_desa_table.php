<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perangkat_desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan'); // Kepala Desa, Sekretaris, Kaur Keuangan, Kasi Kesra, Kadus I, dst
            $table->string('atasan_jabatan')->nullable(); // untuk bikin garis struktur organisasi (parent jabatan)
            $table->foreignId('dusun_id')->nullable()->constrained('dusun')->nullOnDelete(); // khusus jabatan Kepala Dusun
            $table->string('foto_path')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkat_desa');
    }
};
