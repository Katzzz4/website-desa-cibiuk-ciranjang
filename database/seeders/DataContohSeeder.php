<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * ====================================================================
 * DATA CONTOH — UNTUK DEMO DAN PRESENTASI
 * ====================================================================
 *
 * Mengisi berita, agenda, potensi desa, dan laporan pengaduan agar
 * situs tidak terlihat kosong saat didemokan.
 *
 * Menjalankan:
 *     php artisan db:seed --class=DataContohSeeder
 *
 * Menghapus kembali (setelah presentasi, sebelum situs dipakai warga):
 *     php artisan db:seed --class=HapusDataContohSeeder
 *
 * CATATAN PENTING
 * Data kependudukan sengaja TIDAK diisi di sini. Angka demografi mudah
 * dikira data resmi bila tertinggal di situs yang sudah online. Data
 * penduduk yang ada berasal dari dokumen Profil Desa Cibiuk 2024.
 *
 * Laporan pengaduan di bawah ini adalah contoh, bukan laporan warga
 * sungguhan. Hapus sebelum situs diumumkan ke masyarakat.
 */
class DataContohSeeder extends Seeder
{
    /** Nomor tiket laporan contoh, dipakai juga oleh seeder penghapus */
    public const TIKET_CONTOH = [
        'LPR-20260302-0001', 'LPR-20260318-0001', 'LPR-20260405-0001',
        'LPR-20260419-0001', 'LPR-20260507-0001', 'LPR-20260524-0001',
        'LPR-20260611-0001', 'LPR-20260628-0001', 'LPR-20260712-0001',
        'LPR-20260720-0001',
    ];

    public function run(): void
    {
        $this->beritaContoh();
        $this->agendaContoh();
        $this->potensiContoh();
        $this->laporanContoh();

        $this->command->info('Data contoh berhasil dibuat.');
        $this->command->warn('Ingat: hapus data contoh sebelum situs dipakai warga.');
        $this->command->warn('Perintahnya: php artisan db:seed --class=HapusDataContohSeeder');
    }

    // ================================================================
    // BERITA & PENGUMUMAN
    // ================================================================
    private function beritaContoh(): void
    {
        $penulis = DB::table('users')->orderBy('id')->value('id');

        if (!$penulis) {
            $this->command->warn('Belum ada akun pengguna, berita contoh dilewati.');
            return;
        }

        $daftar = [
            [
                'judul' => 'Musyawarah Desa Penetapan APBDes Tahun Anggaran Berjalan',
                'kategori' => 'pengumuman',
                'hari_lalu' => 3,
                'konten' => "Pemerintah Desa Cibiuk mengundang seluruh perwakilan lembaga desa, ketua RT dan RW, serta tokoh masyarakat untuk menghadiri Musyawarah Desa dalam rangka penetapan Anggaran Pendapatan dan Belanja Desa.\n\nMusyawarah akan membahas prioritas pembangunan desa, terutama di bidang infrastruktur jalan lingkungan dan perbaikan saluran irigasi yang menjadi kebutuhan mendesak di tiga dusun.\n\nKehadiran seluruh undangan sangat diharapkan karena hasil musyawarah akan menjadi dasar pelaksanaan pembangunan sepanjang tahun ini.",
            ],
            [
                'judul' => 'Kerja Bakti Pembersihan Saluran Irigasi di Dusun Sukamaju',
                'kategori' => 'berita',
                'hari_lalu' => 9,
                'konten' => "Warga Dusun Sukamaju bersama perangkat desa melaksanakan kerja bakti membersihkan saluran irigasi yang mengalami pendangkalan akibat endapan lumpur dan sampah.\n\nKegiatan diikuti sekitar tujuh puluh warga dan berlangsung sejak pagi hingga menjelang siang. Saluran yang dibersihkan mengairi lahan sawah yang menjadi tumpuan utama mata pencaharian warga setempat.\n\nKepala Desa menyampaikan apresiasi atas gotong royong warga dan berharap kegiatan serupa dapat dilakukan secara berkala menjelang musim tanam.",
            ],
            [
                'judul' => 'Jadwal Posyandu Balita Bulan Ini Dimajukan',
                'kategori' => 'pengumuman',
                'hari_lalu' => 14,
                'konten' => "Diberitahukan kepada seluruh orang tua balita bahwa jadwal Posyandu bulan ini dimajukan dari jadwal semula karena bertepatan dengan agenda kegiatan desa.\n\nOrang tua dimohon membawa buku Kesehatan Ibu dan Anak serta datang tepat waktu agar pelayanan penimbangan dan pemberian vitamin dapat berjalan lancar.\n\nInformasi lebih lanjut dapat ditanyakan kepada kader Posyandu di masing-masing dusun.",
            ],
            [
                'judul' => 'Panen Raya Padi di Blok Persawahan Pasir Honje',
                'kategori' => 'berita',
                'hari_lalu' => 26,
                'konten' => "Petani di wilayah Dusun Pasir Honje memasuki masa panen raya padi. Hasil panen tahun ini dilaporkan lebih baik dibanding musim sebelumnya berkat ketersediaan air irigasi yang lebih terjaga.\n\nPemerintah desa mendampingi petani dalam proses pendataan hasil panen sekaligus menampung keluhan mengenai kondisi jalan usaha tani yang menyulitkan pengangkutan gabah saat musim hujan.\n\nMasukan tersebut akan dibahas dalam musyawarah desa sebagai bahan usulan pembangunan.",
            ],
            [
                'judul' => 'Pendataan Ulang Penerima Bantuan Langsung Tunai Dana Desa',
                'kategori' => 'pengumuman',
                'hari_lalu' => 38,
                'konten' => "Pemerintah Desa Cibiuk melaksanakan pendataan ulang calon penerima Bantuan Langsung Tunai Dana Desa. Pendataan dilakukan oleh petugas bersama ketua RT di masing-masing wilayah.\n\nWarga diimbau menyiapkan Kartu Keluarga dan Kartu Tanda Penduduk yang masih berlaku. Bagi warga yang merasa memenuhi kriteria namun belum terdata, dapat melapor ke kantor desa pada jam pelayanan.\n\nSeluruh proses pendataan tidak dipungut biaya apa pun.",
            ],
        ];

        foreach ($daftar as $b) {
            $tanggal = now()->subDays($b['hari_lalu'])->setTime(8, 30);

            DB::table('berita')->insert([
                'user_id' => $penulis,
                'judul' => $b['judul'],
                'slug' => Str::slug($b['judul']),
                'konten' => $b['konten'],
                'kategori' => $b['kategori'],
                'tanggal_publish' => $tanggal,
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);
        }
    }

    // ================================================================
    // AGENDA KEGIATAN
    // ================================================================
    private function agendaContoh(): void
    {
        $daftar = [
            ['Musyawarah Desa Penetapan APBDes', 'Aula Kantor Desa Cibiuk', 4, '09:00',
             'Membahas prioritas pembangunan desa dan penetapan anggaran tahun berjalan.'],
            ['Posyandu Balita Dusun Kepuh', 'Posyandu Melati, Dusun Kepuh', 7, '08:00',
             'Penimbangan balita, pemberian vitamin A, dan penyuluhan gizi bagi orang tua.'],
            ['Kerja Bakti Bulanan Tiga Dusun', 'Titik kumpul di masing-masing dusun', 12, '07:00',
             'Pembersihan saluran air dan lingkungan permukiman. Warga diimbau membawa alat sendiri.'],
            ['Rapat Koordinasi Ketua RT dan RW', 'Aula Kantor Desa Cibiuk', 19, '13:30',
             'Evaluasi penanganan laporan warga dan persiapan kegiatan bulan berikutnya.'],
            ['Pelatihan Pengolahan Hasil Pertanian', 'Balai Dusun Sukamaju', 26, '09:00',
             'Pelatihan bagi kelompok tani dan pelaku UMKM mengenai pengolahan dan pengemasan produk.'],
            // satu kegiatan yang sudah lewat, agar bagian "Kegiatan Sebelumnya" terisi
            ['Peringatan Hari Kemerdekaan Republik Indonesia', 'Lapangan Desa Cibiuk', -21, '07:30',
             'Upacara bendera dilanjutkan perlombaan warga antar dusun.'],
        ];

        foreach ($daftar as [$judul, $lokasi, $hari, $jam, $deskripsi]) {
            [$j, $m] = explode(':', $jam);
            $mulai = now()->addDays($hari)->setTime((int) $j, (int) $m);

            DB::table('agenda')->insert([
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'tanggal_mulai' => $mulai,
                'tanggal_selesai' => $mulai->copy()->addHours(3),
                'lokasi' => $lokasi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // ================================================================
    // POTENSI DESA
    // ================================================================
    private function potensiContoh(): void
    {
        $daftar = [
            ['umkm', 'Keripik Singkong Bu Imas', '081234567801',
             'Usaha rumahan pembuatan keripik singkong dengan beberapa pilihan rasa. Produksi dilakukan setiap hari dan melayani pesanan dalam jumlah besar untuk acara desa maupun penjualan kembali.'],
            ['umkm', 'Warung Nasi Ibu Eem', '081234567802',
             'Warung makan yang menyediakan masakan rumahan khas Sunda. Melayani pesanan nasi kotak untuk kegiatan desa dan hajatan warga.'],
            ['kerajinan', 'Anyaman Bambu Pak Endang', '081234567803',
             'Kerajinan anyaman bambu berupa keranjang, tampah, dan wadah serbaguna. Dikerjakan secara manual menggunakan bahan bambu dari kebun warga sekitar.'],
            ['pertanian', 'Padi Sawah Desa Cibiuk', null,
             'Lahan sawah seluas kurang lebih 214 hektare menjadi komoditas utama Desa Cibiuk dan sumber mata pencaharian sebagian besar warga. Panen dilakukan mengikuti ketersediaan air irigasi.'],
            ['peternakan', 'Peternakan Domba Warga', null,
             'Populasi domba di Desa Cibiuk tercatat sekitar 245 ekor yang dipelihara secara mandiri oleh warga. Umumnya dipasarkan menjelang hari raya kurban.'],
            ['peternakan', 'Budidaya Ayam Kampung', null,
             'Terdapat sekitar 1.456 ekor ayam kampung yang dipelihara warga, sebagian untuk konsumsi sendiri dan sebagian dijual ke pasar sekitar.'],
            ['peternakan', 'Kolam Ikan Air Tawar', null,
             'Tercatat 17 kolam ikan air tawar yang dikelola warga. Jenis ikan yang dibudidayakan antara lain nila dan lele, dipasarkan di lingkungan desa dan sekitarnya.'],
        ];

        foreach ($daftar as [$jenis, $nama, $kontak, $deskripsi]) {
            DB::table('potensi_desa')->insert([
                'jenis' => $jenis,
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'kontak' => $kontak,
                'foto_path' => null, // sengaja kosong, foto diunggah sendiri lewat dashboard
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // ================================================================
    // LAPORAN PENGADUAN
    // ================================================================
    private function laporanContoh(): void
    {
        $kategori = DB::table('kategori_laporan')->pluck('id', 'nama');
        $dusun = DB::table('dusun')->pluck('id', 'nama');
        $petugas = DB::table('users')->orderBy('id')->value('id');

        if ($kategori->isEmpty() || $dusun->isEmpty()) {
            $this->command->warn('Kategori laporan atau dusun belum ada, laporan contoh dilewati.');
            return;
        }

        // titik-titik di sekitar wilayah Ciranjang, digeser sedikit agar menyebar di peta
        $daftar = [
            ['Jalan Rusak', 'Sukamaju', 'Jalan berlubang di depan Masjid Al-Ikhlas',
             'Terdapat lubang cukup dalam di badan jalan tepat di depan masjid. Sudah beberapa kali pengendara motor terjatuh, terutama saat malam hari karena tidak terlihat.',
             'selesai', -6.8340, 107.2455, 'Depan Masjid Al-Ikhlas RT 02/RW 05', 'Asep Suryana', '081234560001', false],

            ['Lampu Jalan Mati', 'Pasir Honje', 'Lampu penerangan jalan mati di gang utama',
             'Lampu jalan di gang utama sudah mati kurang lebih dua minggu. Warga yang pulang malam kesulitan karena jalan menjadi sangat gelap.',
             'selesai', -6.8382, 107.2503, 'Gang utama RT 01/RW 03', null, null, true],

            ['Saluran Air Tersumbat', 'Kepuh', 'Saluran air tersumbat menyebabkan genangan',
             'Saluran air di belakang permukiman tersumbat sampah dan endapan lumpur. Setiap hujan deras air meluap ke halaman rumah warga.',
             'selesai', -6.8311, 107.2521, 'Belakang permukiman RT 03/RW 07', 'Dedi Mulyana', '081234560002', false],

            ['Sampah Menumpuk', 'Sukamaju', 'Tumpukan sampah di pinggir jalan desa',
             'Sampah menumpuk di pinggir jalan dan sudah menimbulkan bau tidak sedap. Warga sekitar mengeluhkan banyaknya lalat.',
             'diproses', -6.8358, 107.2472, 'Pinggir jalan desa dekat jembatan', 'Siti Aminah', '081234560003', false],

            ['Jalan Rusak', 'Pasir Honje', 'Jalan usaha tani rusak menyulitkan angkut gabah',
             'Jalan menuju blok persawahan rusak parah. Saat musim hujan sulit dilewati kendaraan pengangkut hasil panen sehingga biaya angkut menjadi lebih mahal.',
             'diproses', -6.8395, 107.2488, 'Jalan usaha tani blok sawah', 'Ujang Rohman', '081234560004', false],

            ['Keamanan', 'Kepuh', 'Permintaan penambahan jadwal ronda malam',
             'Beberapa waktu terakhir ada kejadian kehilangan unggas di lingkungan kami. Warga mengusulkan penambahan jadwal ronda malam.',
             'diproses', -6.8325, 107.2534, 'Lingkungan RT 02/RW 08', 'Hendra Gunawan', '081234560005', false],

            ['Lampu Jalan Mati', 'Sukamaju', 'Lampu jalan padam di pertigaan desa',
             'Lampu di pertigaan menuju kantor desa padam sejak beberapa hari lalu. Pertigaan ini cukup ramai sehingga rawan bila gelap.',
             'menunggu', -6.8349, 107.2464, 'Pertigaan menuju kantor desa', 'Rina Marlina', '081234560006', false],

            ['Pelayanan Administrasi', 'Pasir Honje', 'Menanyakan proses pembuatan surat keterangan domisili',
             'Saya sudah mengajukan surat keterangan domisili minggu lalu namun belum ada kabar. Mohon informasi mengenai berapa lama prosesnya.',
             'menunggu', -6.8377, 107.2497, 'Kantor Desa Cibiuk', 'Yayan Sopyan', '081234560007', false],

            ['Sampah Menumpuk', 'Kepuh', 'Usulan pengadaan tempat sampah di area posyandu',
             'Di sekitar posyandu belum ada tempat sampah sehingga sampah sering berserakan setelah kegiatan. Mohon dipertimbangkan pengadaannya.',
             'menunggu', -6.8318, 107.2515, 'Area Posyandu Melati', null, null, true],

            ['Bencana', 'Sukamaju', 'Pohon tumbang menutupi sebagian badan jalan',
             'Pohon di pinggir jalan tumbang setelah hujan disertai angin kencang. Sebagian badan jalan tertutup sehingga kendaraan harus bergantian melintas.',
             'ditolak', -6.8365, 107.2449, 'Jalan desa dekat kebun warga', 'Bambang Setiawan', '081234560008', false],
        ];

        foreach ($daftar as $i => [$namaKategori, $namaDusun, $judul, $deskripsi, $status,
                                   $lat, $lng, $alamat, $pelapor, $hp, $anonim]) {

            $tiket = self::TIKET_CONTOH[$i];
            // tanggal diambil dari nomor tiket agar konsisten dan mudah ditelusuri
            $dibuat = Carbon::createFromFormat('Ymd', substr($tiket, 4, 8))->setTime(9 + ($i % 8), 15);

            $selesaiAt = null;
            if ($status === 'selesai') {
                $selesaiAt = $dibuat->copy()->addDays(3 + ($i % 5));
            }

            $laporanId = DB::table('laporan')->insertGetId([
                'no_tiket' => $tiket,
                'kategori_laporan_id' => $kategori[$namaKategori] ?? $kategori->first(),
                'dusun_id' => $dusun[$namaDusun] ?? null,
                'anonim' => $anonim,
                'nama_pelapor' => $anonim ? null : $pelapor,
                'no_hp' => $anonim ? null : $hp,
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'latitude' => $lat,
                'longitude' => $lng,
                'alamat_lokasi' => $alamat,
                'tanggal_kejadian' => $dibuat->copy()->subDay()->toDateString(),
                'status' => $status,
                'alasan_tolak' => $status === 'ditolak'
                    ? 'Penanganan pohon tumbang di jalan desa merupakan kewenangan dinas terkait. Laporan sudah kami teruskan, mohon menunggu tindak lanjut dari pihak tersebut.'
                    : null,
                'selesai_at' => $selesaiAt,
                'ditangani_oleh' => $status === 'menunggu' ? null : $petugas,
                'created_at' => $dibuat,
                'updated_at' => $selesaiAt ?? $dibuat,
            ]);

            $this->riwayatTanggapan($laporanId, $status, $dibuat, $petugas);
        }
    }

    /** Membuat riwayat tanggapan sesuai perjalanan status laporan */
    private function riwayatTanggapan(int $laporanId, string $status, Carbon $dibuat, ?int $petugas): void
    {
        $riwayat = [[
            'status' => 'menunggu',
            'isi' => 'Laporan diterima, menunggu verifikasi petugas.',
            'waktu' => $dibuat,
            'oleh' => null,
        ]];

        if (in_array($status, ['diproses', 'selesai'], true)) {
            $riwayat[] = [
                'status' => 'diproses',
                'isi' => 'Laporan telah diverifikasi dan sedang ditindaklanjuti oleh perangkat desa.',
                'waktu' => $dibuat->copy()->addDays(1),
                'oleh' => $petugas,
            ];
        }

        if ($status === 'selesai') {
            $riwayat[] = [
                'status' => 'selesai',
                'isi' => 'Penanganan telah selesai dilaksanakan. Terima kasih atas laporan yang disampaikan.',
                'waktu' => $dibuat->copy()->addDays(4),
                'oleh' => $petugas,
            ];
        }

        if ($status === 'ditolak') {
            $riwayat[] = [
                'status' => 'ditolak',
                'isi' => 'Laporan tidak dapat ditindaklanjuti oleh desa karena berada di luar kewenangan. Sudah diteruskan ke dinas terkait.',
                'waktu' => $dibuat->copy()->addDays(2),
                'oleh' => $petugas,
            ];
        }

        foreach ($riwayat as $r) {
            DB::table('laporan_tanggapan')->insert([
                'laporan_id' => $laporanId,
                'user_id' => $r['oleh'],
                'status_baru' => $r['status'],
                'isi_tanggapan' => $r['isi'],
                'created_at' => $r['waktu'],
                'updated_at' => $r['waktu'],
            ]);
        }
    }
}