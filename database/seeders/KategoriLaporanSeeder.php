<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriLaporanSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Jalan Rusak',
            'Lampu Jalan Mati',
            'Sampah Menumpuk',
            'Saluran Air Tersumbat',
            'Bencana',
            'Keamanan',
            'Pelayanan Administrasi',
        ];

        foreach ($kategori as $nama) {
            DB::table('kategori_laporan')->insert([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
