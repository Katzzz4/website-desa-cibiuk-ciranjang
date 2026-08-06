<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\ProfilDesa;
use Illuminate\Support\Facades\DB;

/**
 * Halaman transparansi penanganan pengaduan untuk warga.
 *
 * CATATAN PRIVASI
 * Halaman ini dapat diakses siapa pun tanpa login, karena itu tidak
 * menampilkan nama pelapor, nomor telepon, titik lokasi, maupun judul
 * laporan yang ditulis bebas oleh warga. Yang ditampilkan hanya angka
 * ringkasan serta kategori, dusun, dan bulan penyelesaian.
 */
class TransparansiController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::first();

        $perStatus = Laporan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $menunggu = (int) ($perStatus['menunggu'] ?? 0);
        $diproses = (int) ($perStatus['diproses'] ?? 0);
        $selesai  = (int) ($perStatus['selesai'] ?? 0);
        $ditolak  = (int) ($perStatus['ditolak'] ?? 0);
        $total    = $menunggu + $diproses + $selesai + $ditolak;

        $persenSelesai = $total > 0 ? round($selesai / $total * 100, 1) : 0;

        $rataRataHari = Laporan::whereNotNull('selesai_at')
            ->selectRaw('AVG(DATEDIFF(selesai_at, created_at)) as rata')
            ->value('rata');

        // ---------- Tren 12 bulan terakhir ----------
        $mulai = now()->startOfMonth()->subMonths(11);

        $masukPerBulan = Laporan::where('created_at', '>=', $mulai)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')->pluck('total', 'bulan');

        $selesaiPerBulan = Laporan::whereNotNull('selesai_at')
            ->where('selesai_at', '>=', $mulai)
            ->selectRaw("DATE_FORMAT(selesai_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')->pluck('total', 'bulan');

        $tren = collect(range(0, 11))->map(function ($i) use ($mulai, $masukPerBulan, $selesaiPerBulan) {
            $bulan = $mulai->copy()->addMonths($i);
            $kunci = $bulan->format('Y-m');

            return [
                'label'   => $bulan->translatedFormat('M Y'),
                'masuk'   => (int) ($masukPerBulan[$kunci] ?? 0),
                'selesai' => (int) ($selesaiPerBulan[$kunci] ?? 0),
            ];
        });

        // ---------- Sebaran kategori & dusun ----------
        $perKategori = Laporan::selectRaw('kategori_laporan_id, COUNT(*) as total')
            ->with('kategori:id,nama')
            ->groupBy('kategori_laporan_id')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => [
                'label' => $r->kategori->nama ?? 'Lainnya',
                'total' => (int) $r->total,
            ]);

        $perDusun = Laporan::selectRaw('dusun_id, COUNT(*) as total')
            ->with('dusun:id,nama')
            ->groupBy('dusun_id')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => [
                'label' => $r->dusun->nama ?? 'Tidak disebutkan',
                'total' => (int) $r->total,
            ]);

        // ---------- Penanganan terakhir (tanpa data pribadi) ----------
        $penangananTerakhir = Laporan::where('status', 'selesai')
            ->whereNotNull('selesai_at')
            ->with(['kategori:id,nama', 'dusun:id,nama'])
            ->orderByDesc('selesai_at')
            ->take(8)
            ->get()
            ->map(fn ($l) => [
                'kategori' => $l->kategori->nama ?? '-',
                'dusun'    => $l->dusun->nama,
                'selesai'  => $l->selesai_at,
                'lama'     => $l->created_at->diffInDays($l->selesai_at),
            ]);

        return view('transparansi.index', compact(
            'profil', 'total', 'menunggu', 'diproses', 'selesai', 'ditolak',
            'persenSelesai', 'rataRataHari', 'tren', 'perKategori',
            'perDusun', 'penangananTerakhir'
        ));
    }
}