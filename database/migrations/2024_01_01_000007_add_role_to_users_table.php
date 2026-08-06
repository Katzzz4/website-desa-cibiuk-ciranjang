<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->after('email'); // superadmin, admin, kadus
            $table->string('no_hp')->nullable()->after('role');
            $table->foreignId('dusun_id')->nullable()->after('no_hp')
                ->constrained('dusun')->nullOnDelete(); // kadus hanya lihat laporan dusunnya
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dusun_id']);
            $table->dropColumn(['role', 'no_hp', 'dusun_id']);
        });
    }
};
