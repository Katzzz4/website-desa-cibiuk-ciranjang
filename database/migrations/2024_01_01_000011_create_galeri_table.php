<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('tipe', ['foto', 'video']);
            $table->string('file_path')->nullable();   // untuk foto (upload lokal)
            $table->string('url_video')->nullable();   // untuk video (embed YouTube dll)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
