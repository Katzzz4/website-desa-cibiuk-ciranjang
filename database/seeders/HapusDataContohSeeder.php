<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ====================================================================
 * MENGHAPUS DATA CONTOH
 * ====================================================================
 *
 *     php artisan db:seed --class=HapusDataContohSeeder
 *
 * Hanya menghapus data yang dibuat oleh DataContohSeeder, dikenali dari
 * judul dan nomor tiketnya. Data asli yang Anda masukkan sendiri lewat
 * dashboard tidak akan tersentuh.
 *
 * Jalankan ini sebelum situs diumumkan ke warga, terutama untuk laporan
 * pengaduan — laporan contoh yang tertinggal akan ikut terhitung di
 * statistik dashboard dan membingungkan warga yang melacak laporannya.
 */
class HapusDataContohSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Laporan beserta riwayat tanggapannya ----------
        $idLaporan = DB::table('laporan')
            ->whereIn('no_tiket', DataContohSeeder::TIKET_CONTOH)
            ->pluck('id');

        if ($idLaporan->isNotEmpty()) {
            DB::table('laporan_tanggapan')->whereIn('laporan_id', $idLaporan)->delete();
            DB::table('laporan_foto')->whereIn('laporan_id', $idLaporan)->delete();
            DB::table('laporan')->whereIn('id', $idLaporan)->delete();
        }
        $this->command->info("Laporan contoh dihapus: {$idLaporan->count()}");

        // ---------- Berita ----------
        $judulBerita = [
            'Musyawarah Desa Penetapan APBDes Tahun Anggaran Berjalan',
            'Kerja Bakti Pembersihan Saluran Irigasi di Dusun Sukamaju',
            'Jadwal Posyandu Balita Bulan Ini Dimajukan',
            'Panen Raya Padi di Blok Persawahan Pasir Honje',
            'Pendataan Ulang Penerima Bantuan Langsung Tunai Dana Desa',
        ];
        $n = DB::table('berita')->whereIn('judul', $judulBerita)->delete();
        $this->command->info("Berita contoh dihapus: {$n}");

        // ---------- Agenda ----------
        $judulAgenda = [
            'Musyawarah Desa Penetapan APBDes',
            'Posyandu Balita Dusun Kepuh',
            'Kerja Bakti Bulanan Tiga Dusun',
            'Rapat Koordinasi Ketua RT dan RW',
            'Pelatihan Pengolahan Hasil Pertanian',
            'Peringatan Hari Kemerdekaan Republik Indonesia',
        ];
        $n = DB::table('agenda')->whereIn('judul', $judulAgenda)->delete();
        $this->command->info("Agenda contoh dihapus: {$n}");

        // ---------- Potensi desa ----------
        $namaPotensi = [
            'Keripik Singkong Bu Imas',
            'Warung Nasi Ibu Eem',
            'Anyaman Bambu Pak Endang',
            'Padi Sawah Desa Cibiuk',
            'Peternakan Domba Warga',
            'Budidaya Ayam Kampung',
            'Kolam Ikan Air Tawar',
        ];
        $n = DB::table('potensi_desa')->whereIn('nama', $namaPotensi)->delete();
        $this->command->info("Potensi desa contoh dihapus: {$n}");

        $this->command->info('Selesai. Data contoh sudah dibersihkan.');
    }
}