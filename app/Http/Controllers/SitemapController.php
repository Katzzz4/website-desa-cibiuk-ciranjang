<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\ProfilDesa;
use Illuminate\Support\Carbon;

/**
 * Menyusun peta situs (sitemap) agar mesin pencari mengetahui
 * seluruh halaman yang tersedia beserta kapan terakhir diperbarui.
 *
 * Dibuat langsung dari database, sehingga berita baru otomatis masuk
 * tanpa perlu diperbarui secara manual.
 */
class SitemapController extends Controller
{
    public function index()
    {
        $halaman = [];

        // ---------- Halaman tetap ----------
        $tetap = [
            ['beranda',              '1.0', 'weekly'],
            ['profil.index',         '0.9', 'monthly'],
            ['infografis.penduduk',  '0.8', 'monthly'],
            ['potensi.index',        '0.8', 'monthly'],
            ['peta.index',           '0.7', 'yearly'],
            ['berita.index',         '0.9', 'daily'],
            ['agenda.index',         '0.8', 'weekly'],
            ['galeri.index',         '0.6', 'monthly'],
            ['dokumen.index',        '0.7', 'monthly'],
            ['transparansi.index',   '0.7', 'weekly'],
            ['pengaduan.create',     '0.9', 'monthly'],
            ['pengaduan.lacak.form', '0.6', 'yearly'],
        ];

        foreach ($tetap as [$nama, $prioritas, $frekuensi]) {
            $halaman[] = [
                'url'      => route($nama),
                'ubah'     => now()->toAtomString(),
                'frekuensi'=> $frekuensi,
                'prioritas'=> $prioritas,
            ];
        }

        // ---------- Tiap berita ----------
        Berita::whereNotNull('tanggal_publish')
            ->where('tanggal_publish', '<=', now())
            ->latest('tanggal_publish')
            ->get()
            ->each(function ($b) use (&$halaman) {
                $halaman[] = [
                    'url'       => route('berita.show', $b->slug),
                    'ubah'      => ($b->updated_at ?? $b->tanggal_publish)->toAtomString(),
                    'frekuensi' => 'monthly',
                    'prioritas' => '0.7',
                ];
            });

        return response()
            ->view('sitemap', compact('halaman'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Berkas robots.txt — memberi tahu mesin pencari halaman mana yang
     * boleh ditelusuri dan di mana letak peta situsnya.
     */
    public function robots()
    {
        $isi = implode("\n", [
            'User-agent: *',
            '',
            '# Halaman pengelolaan tidak perlu masuk hasil pencarian',
            'Disallow: /admin/',
            'Disallow: /login',
            'Disallow: /profile',
            'Disallow: /pengaduan/berhasil/',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ]);

        return response($isi, 200)
            ->header('Content-Type', 'text/plain');
    }
}