<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            // Pengelompokan utama yang dipilih warga di halaman awal
            $table->string('klasifikasi', 30)->default('produk_hukum')->after('nama');
        });

        // Kolom kategori diubah dari enum menjadi teks biasa agar jenis
        // dokumen dapat bertambah tanpa perlu mengubah struktur tabel lagi.
        DB::statement("ALTER TABLE dokumen MODIFY kategori VARCHAR(30) NOT NULL DEFAULT 'lainnya'");

        // Dokumen yang sudah ada dianggap produk hukum, sesuai kategori awalnya
        DB::table('dokumen')->whereIn('kategori', ['perdes', 'sk'])
            ->update(['klasifikasi' => 'produk_hukum']);
    }

    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn('klasifikasi');
        });

        DB::statement("ALTER TABLE dokumen MODIFY kategori ENUM('perdes','sk','lainnya') NOT NULL DEFAULT 'lainnya'");
    }
};
