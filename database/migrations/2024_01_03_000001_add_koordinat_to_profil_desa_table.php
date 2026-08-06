<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_desa', function (Blueprint $table) {
            // titik tengah desa, dipakai sebagai posisi awal peta
            // pada form pengaduan warga dan peta sebaran laporan
            $table->decimal('latitude', 10, 7)->nullable()->after('jarak_ke_kecamatan_km');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->unsignedTinyInteger('zoom_peta')->default(15)->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('profil_desa', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'zoom_peta']);
        });
    }
};
