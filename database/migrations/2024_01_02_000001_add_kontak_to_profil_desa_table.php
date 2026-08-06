<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_desa', function (Blueprint $table) {
            $table->string('alamat_kantor')->nullable()->after('nama_kepala_desa');
            $table->string('telepon', 30)->nullable()->after('alamat_kantor');
            $table->string('email')->nullable()->after('telepon');
            $table->text('jam_pelayanan')->nullable()->after('email');   // satu baris per hari
            $table->string('foto_hero_path')->nullable()->after('logo_path'); // gambar utama beranda
        });
    }

    public function down(): void
    {
        Schema::table('profil_desa', function (Blueprint $table) {
            $table->dropColumn(['alamat_kantor', 'telepon', 'email', 'jam_pelayanan', 'foto_hero_path']);
        });
    }
};
