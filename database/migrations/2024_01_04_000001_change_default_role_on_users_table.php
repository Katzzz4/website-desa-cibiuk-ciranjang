<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sebelumnya kolom role bernilai bawaan 'admin', sehingga akun apa pun
     * yang dibuat tanpa menyebut peran langsung memperoleh akses dashboard.
     * Nilai bawaan diubah menjadi 'warga' yang tidak punya hak akses apa pun.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('warga')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->change();
        });
    }
};
