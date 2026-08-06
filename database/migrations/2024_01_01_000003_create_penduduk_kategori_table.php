<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');   // "Mata Pencaharian", "Tingkat Pendidikan", "Agama", dst
            $table->string('slug')->unique(); // "mata-pencaharian", "pendidikan", "agama"
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk_kategori');
    }
};
