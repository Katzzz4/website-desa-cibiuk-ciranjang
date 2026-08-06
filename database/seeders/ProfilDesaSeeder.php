<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilDesaSeeder extends Seeder
{
    public function run(): void
    {
        // ================= PROFIL DESA =================
        DB::table('profil_desa')->insert([
            'nama_desa' => 'Cibiuk',
            'kecamatan' => 'Ciranjang',
            'kabupaten' => 'Cianjur',
            'provinsi' => 'Jawa Barat',
            'sejarah' => 'Desa Cibiuk merupakan salah satu Desa dari 9 (sembilan) Desa yang berada di wilayah Kecamatan Ciranjang Kabupaten Cianjur Provinsi Jawa Barat. Desa Cibiuk merupakan Desa yang dibentuk dari pemekaran Desa Ciranjang pada tanggal 17 Oktober 1977. Kemudian pada tanggal 12 April 1996 Desa Cibiuk dimekarkan menjadi Desa Cibiuk dan Desa Mekargalih.',
            'visi' => 'TERWUJUDNYA DESA CIBIUK MAJU, SEJAHTERA DAN BERAKHLAKUL KARIMAH',
            'misi' => json_encode([
                'Meningkatkan pelayanan terhadap masyarakat yang membutuhkan dengan baik, mudah, ramah, dan cepat.',
                'Meningkatkan kerja sama dengan mitra kerja antar Lembaga Masyarakat Desa dan masyarakat.',
                'Meningkatkan dan menyukseskan pendidikan, kesehatan masyarakat, kebersihan lingkungan, hasil pertanian, perekonomian, dan kesejahteraan masyarakat serta pembinaan kehidupan masyarakat berbangsa dan bernegara dengan nilai-nilai Pancasila dan Agama.',
                'Meningkatkan infrastruktur di seluruh pelosok Desa.',
            ]),
            'luas_wilayah_ha' => 292.079,
            'batas_barat' => 'Desa Ciranjang',
            'batas_utara' => 'Desa Sindangsari',
            'batas_timur' => 'Desa Sindangjaya dan Desa Karangwangi',
            'batas_selatan' => 'Desa Mekargalih',
            'jarak_ke_kabupaten_km' => 14,
            'jarak_ke_kecamatan_km' => 0.4,
            'nama_kepala_desa' => 'Dahlan Ripa\'i',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ================= DUSUN =================
        $dusunIds = [];
        $dusunIds['Sukamaju'] = DB::table('dusun')->insertGetId([
            'nama' => 'Sukamaju', 'jarak_ke_desa_km' => 1.5, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $dusunIds['Pasir Honje'] = DB::table('dusun')->insertGetId([
            'nama' => 'Pasir Honje', 'jarak_ke_desa_km' => 1.2, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $dusunIds['Kepuh'] = DB::table('dusun')->insertGetId([
            'nama' => 'Kepuh', 'jarak_ke_desa_km' => 1.4, 'created_at' => now(), 'updated_at' => now(),
        ]);

        // ================= RINGKASAN PENDUDUK 2024 =================
        DB::table('penduduk_ringkasan')->insert([
            'tahun' => 2024,
            'total_kk' => null, // tidak tersedia di dokumen sumber
            'total_laki' => 5406,
            'total_perempuan' => 5347,
            'lahir_laki' => 7,
            'lahir_perempuan' => 10,
            'mati_laki' => 17,
            'mati_perempuan' => 2,
            'datang_laki' => 4,
            'datang_perempuan' => 6,
            'pergi_laki' => 6,
            'pergi_perempuan' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ================= KATEGORI =================
        $kategori = [
            'mata-pencaharian' => 'Mata Pencaharian',
            'pendidikan' => 'Tingkat Pendidikan',
            'agama' => 'Agama',
        ];
        $katIds = [];
        $urutan = 1;
        foreach ($kategori as $slug => $nama) {
            $katIds[$slug] = DB::table('penduduk_kategori')->insertGetId([
                'nama' => $nama, 'slug' => $slug, 'urutan' => $urutan++,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ================= DATA: MATA PENCAHARIAN (total, tanpa breakdown L/P di dokumen) =================
        $pekerjaan = [
            'Belum/Tidak Bekerja' => 1311,
            'Petani/Pekebun' => 728,
            'Pelajar/Mahasiswa' => 2620,
            'Wiraswasta' => 800,
            'Mengurus Rumah Tangga' => 1749,
            'Pensiunan' => 78,
            'Guru' => 205,
            'Pembantu Rumah Tangga' => 90,
            'Karyawan Swasta' => 570,
            'Karyawan Honorer' => 122,
            'Buruh Harian Lepas' => 786,
            'Tukang Kayu' => 6,
            'Tukang Las/Pandai Besi' => 4,
            'Kepala Desa' => 1,
            'Perangkat Desa' => 11,
            'Pedagang' => 457,
            'Sopir' => 70,
            'Pegawai Negeri Sipil (PNS)' => 180,
            'Buruh Tani/Perkebunan' => 870,
            'Imam Masjid' => 57,
            'Ustadz/Mubaligh' => 38,
        ];
        foreach ($pekerjaan as $label => $jumlah) {
            DB::table('penduduk_data')->insert([
                'penduduk_kategori_id' => $katIds['mata-pencaharian'],
                'label' => $label,
                'jumlah_laki' => $jumlah, // dokumen sumber tidak memisah L/P per pekerjaan
                'jumlah_perempuan' => 0,
                'tahun' => 2024,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ================= DATA: PENDIDIKAN =================
        $pendidikan = [
            'PT / Akademi' => 658,
            'SLTA' => 2190,
            'SLTP' => 1474,
            'SD' => 3884,
            'Belum Tamat SD' => 1245,
            'Tidak/Belum Sekolah' => 1302,
        ];
        foreach ($pendidikan as $label => $jumlah) {
            DB::table('penduduk_data')->insert([
                'penduduk_kategori_id' => $katIds['pendidikan'],
                'label' => $label,
                'jumlah_laki' => $jumlah,
                'jumlah_perempuan' => 0,
                'tahun' => 2024,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ================= DATA: AGAMA =================
        $agama = [
            'Islam' => 10709,
            'Kristen' => 44,
            'Katolik' => 0,
            'Budha' => 0,
            'Hindu' => 0,
        ];
        foreach ($agama as $label => $jumlah) {
            DB::table('penduduk_data')->insert([
                'penduduk_kategori_id' => $katIds['agama'],
                'label' => $label,
                'jumlah_laki' => $jumlah,
                'jumlah_perempuan' => 0,
                'tahun' => 2024,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ================= PERANGKAT DESA (dari struktur organisasi) =================
        $perangkat = [
            ['nama' => 'Dahlan Ripa\'i', 'jabatan' => 'Kepala Desa', 'atasan_jabatan' => null],
            ['nama' => 'Jeni Muldyanto', 'jabatan' => 'Sekretaris', 'atasan_jabatan' => 'Kepala Desa'],
            ['nama' => 'Dikdik S', 'jabatan' => 'Kasi Pemerintahan', 'atasan_jabatan' => 'Sekretaris'],
            ['nama' => 'Agus Hakim', 'jabatan' => 'Kasi Kesra', 'atasan_jabatan' => 'Sekretaris'],
            ['nama' => 'Nugraha A', 'jabatan' => 'Kasi Pelayanan', 'atasan_jabatan' => 'Sekretaris'],
            ['nama' => 'Endang S. Novianti', 'jabatan' => 'Kaur Perencanaan', 'atasan_jabatan' => 'Sekretaris'],
            ['nama' => 'A Rian R', 'jabatan' => 'Kaur Umum', 'atasan_jabatan' => 'Sekretaris'],
            ['nama' => 'Adinda Maulana Y', 'jabatan' => 'Kaur Keuangan', 'atasan_jabatan' => 'Sekretaris'],
            ['nama' => 'Dias Nugraha', 'jabatan' => 'Kepala Dusun I', 'atasan_jabatan' => 'Kepala Desa', 'dusun' => 'Sukamaju'],
            ['nama' => 'Mitha A', 'jabatan' => 'Kepala Dusun II', 'atasan_jabatan' => 'Kepala Desa', 'dusun' => 'Pasir Honje'],
            ['nama' => 'Ruslan T', 'jabatan' => 'Kepala Dusun III', 'atasan_jabatan' => 'Kepala Desa', 'dusun' => 'Kepuh'],
        ];
        foreach ($perangkat as $i => $p) {
            DB::table('perangkat_desa')->insert([
                'nama' => $p['nama'],
                'jabatan' => $p['jabatan'],
                'atasan_jabatan' => $p['atasan_jabatan'],
                'dusun_id' => isset($p['dusun']) ? $dusunIds[$p['dusun']] : null,
                'urutan' => $i + 1,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
