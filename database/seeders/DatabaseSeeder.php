<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun superadmin default untuk login pertama kali
        User::firstOrCreate(
            ['email' => 'admin@desacibiuk.id'],
            [
                'name' => 'Admin Desa Cibiuk',
                'password' => Hash::make('password'), // WAJIB diganti setelah login pertama
                'role' => 'superadmin',
            ]
        );

        $this->call([
            ProfilDesaSeeder::class,
            KategoriLaporanSeeder::class,
        ]);
    }
}
