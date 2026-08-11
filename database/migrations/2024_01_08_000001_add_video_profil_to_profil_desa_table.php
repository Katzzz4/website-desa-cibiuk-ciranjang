<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_desa', function (Blueprint $table) {
            // Alamat video YouTube pengenalan desa
            $table->string('video_profil_url')->nullable()->after('foto_hero_path');
            $table->string('video_profil_judul')->nullable()->after('video_profil_url');
            $table->text('video_profil_keterangan')->nullable()->after('video_profil_judul');
        });
    }

    public function down(): void
    {
        Schema::table('profil_desa', function (Blueprint $table) {
            $table->dropColumn([
                'video_profil_url',
                'video_profil_judul',
                'video_profil_keterangan',
            ]);
        });
    }
};
