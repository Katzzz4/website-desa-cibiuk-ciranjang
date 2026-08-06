<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ====================================================================
 * DATA UMKM DESA CIBIUK
 * ====================================================================
 *
 * Sumber: dokumen "Data Produk Usaha Mikro Kecil dan Menengah
 * Desa Cibiuk Kecamatan Ciranjang" dari pemerintah desa.
 *
 * Menjalankan:
 *     php artisan db:seed --class=UmkmSeeder
 *
 * ---------------------------------------------------------------
 * CATATAN PENTING SEBELUM DIJALANKAN
 * ---------------------------------------------------------------
 * Berkas ini memuat nomor telepon pribadi warga. Dokumen sumbernya
 * memang berasal dari desa, tetapi mencatat nomor di dokumen internal
 * berbeda dengan menayangkannya di situs yang dapat dibuka siapa saja.
 *
 * Pastikan pemilik usaha sudah menyetujui nomornya ditampilkan.
 * Bila belum, ubah nilai TAMPILKAN_KONTAK menjadi false. Data usaha
 * tetap tayang, hanya nomor teleponnya yang tidak disertakan.
 */
class UmkmSeeder extends Seeder
{
    /** Ubah ke false bila persetujuan pemilik usaha belum diperoleh */
    private const TAMPILKAN_KONTAK = true;

    public function run(): void
    {
        // [jenis, nama produk, pengelola, nomor telepon, alamat]
        $daftar = [
            ['kerajinan', 'Keramik dan Guci', 'Nuni', '085798788848',
             'Kp. Pasir Gudang RT 01 RW 07'],

            ['umkm', 'Keripik Singkong', 'Nonoh', null,
             'Kp. Babakan Kepuh RT 01 RW 06'],

            ['umkm', 'Mochi', 'Dede', '081351834059',
             'Kp. Kepuh RT 02 RW 13'],

            ['umkm', 'Keripik Pisang', 'Ida Rohaeni', null,
             'Kp. Kepuh RT 02 RW 13'],

            ['umkm', 'Kue Ali', 'Imas', '087778910184',
             'Kp. Pasir Batu RT 03 RW 08'],

            ['umkm', 'Tempe dan Tahu', 'Hayun', '081221726415',
             'Kp. Sukaluyu RT 01 RW 10'],

            ['umkm', 'Telur Asin', 'Mamat', '082316877684',
             'Kp. Kebon Jeruk RT 01 RW 07'],

            ['umkm', 'Rangginang', 'Juariah', '087720160222',
             'Kp. Pasir Sembung RT 02 RW 06'],

            ['umkm', 'Kue Basah', 'Ruli', '081802468659',
             'Kp. Pasir Honje RT 02 RW 08'],

            ['umkm', 'Bahari Snack', 'Ade Bahri', null,
             'Kp. Sinargalih RT 03 RW 03'],

            ['umkm', 'Basreng Tuna Satu', 'Dais Siti Halimah', null,
             'Kp. Singkup RT 03 RW 03'],
        ];

        $dibuat = 0;
        $dilewati = 0;

        foreach ($daftar as [$jenis, $nama, $pengelola, $telepon, $alamat]) {
            // Hindari data ganda bila seeder dijalankan lebih dari sekali
            if (DB::table('potensi_desa')->where('nama', $nama)->exists()) {
                $dilewati++;
                continue;
            }

            DB::table('potensi_desa')->insert([
                'jenis' => $jenis,
                'nama' => $nama,
                'deskripsi' => "Usaha rumahan milik {$pengelola}, berlokasi di {$alamat}, Desa Cibiuk.",
                'kontak' => self::TAMPILKAN_KONTAK ? $telepon : null,
                'foto_path' => null,   // diunggah sendiri lewat dashboard
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $dibuat++;
        }

        $this->command->info("Data UMKM ditambahkan: {$dibuat}" . ($dilewati ? ", dilewati karena sudah ada: {$dilewati}" : ''));

        if (!self::TAMPILKAN_KONTAK) {
            $this->command->warn('Nomor telepon tidak disertakan. Ubah TAMPILKAN_KONTAK di dalam seeder bila sudah ada persetujuan pemilik usaha.');
        }

        $this->command->warn('Foto produk belum ada. Unggah lewat Dashboard > Potensi Desa agar halaman tidak tampil kosong.');
    }
}