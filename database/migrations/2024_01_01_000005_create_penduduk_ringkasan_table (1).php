<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk_ringkasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun')->unique();
            $table->unsignedInteger('total_kk')->nullable();
            $table->unsignedInteger('total_laki')->default(0);
            $table->unsignedInteger('total_perempuan')->default(0);
            // lahir/mati/datang/pergi -> mutasi penduduk tahunan
            $table->unsignedInteger('lahir_laki')->default(0);
            $table->unsignedInteger('lahir_perempuan')->default(0);
            $table->unsignedInteger('mati_laki')->default(0);
            $table->unsignedInteger('mati_perempuan')->default(0);
            $table->unsignedInteger('datang_laki')->default(0);
            $table->unsignedInteger('datang_perempuan')->default(0);
            $table->unsignedInteger('pergi_laki')->default(0);
            $table->unsignedInteger('pergi_perempuan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk_ringkasan');
    }
};
