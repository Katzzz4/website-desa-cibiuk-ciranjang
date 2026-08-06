<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /** Laporan dianggap "menunggak" jika belum tuntas melewati batas hari ini */
    private const BATAS_TUNGGAKAN_HARI = 7;

    public function index()
    {
        // Kepala Dusun hanya melihat angka untuk dusunnya sendiri
        $pengguna = auth()->user();

        $perStatus = Laporan::untukPengguna($pengguna)->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $menunggu = (int) ($perStatus['menunggu'] ?? 0);
        $diproses = (int) ($perStatus['diproses'] ?? 0);
        $selesai  = (int) ($perStatus['selesai'] ?? 0);
        $ditolak  = (int) ($perStatus['ditolak'] ?? 0);
        $totalLaporan = $menunggu + $diproses + $selesai + $ditolak;

        // Persentase selesai dihitung dari laporan yang sudah diverifikasi saja
        // (laporan ditolak tidak dianggap "beban kerja", tapi tetap dihitung sebagai tertangani)
        $persenSelesai = $totalLaporan > 0
            ? round($selesai / $totalLaporan * 100, 1)
            : 0;

        // ================= TREN 12 BULAN TERAKHIR =================
        $mulai = now()->startOfMonth()->subMonths(11);

        $masukPerBulan = Laporan::untukPengguna($pengguna)->where('created_at', '>=', $mulai)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $selesaiPerBulan = Laporan::untukPengguna($pengguna)->whereNotNull('selesai_at')
            ->where('selesai_at', '>=', $mulai)
            ->selectRaw("DATE_FORMAT(selesai_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // bulan tanpa laporan tetap ditampilkan supaya garis tren tidak terputus
        $tren = collect(range(0, 11))->map(function ($i) use ($mulai, $masukPerBulan, $selesaiPerBulan) {
            $bulan = $mulai->copy()->addMonths($i);
            $kunci = $bulan->format('Y-m');

            return [
                'label' => $bulan->translatedFormat('M Y'),
                'masuk' => (int) ($masukPerBulan[$kunci] ?? 0),
                'selesai' => (int) ($selesaiPerBulan[$kunci] ?? 0),
            ];
        });

        // ================= PER KATEGORI =================
        $perKategori = Laporan::untukPengguna($pengguna)->selectRaw('kategori_laporan_id, COUNT(*) as total')
            ->with('kategori:id,nama')
            ->groupBy('kategori_laporan_id')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => [
                'label' => $r->kategori->nama ?? 'Tanpa kategori',
                'total' => (int) $r->total,
            ]);

        // ================= PER DUSUN =================
        $perDusun = Laporan::untukPengguna($pengguna)->selectRaw('dusun_id, COUNT(*) as total')
            ->with('dusun:id,nama')
            ->groupBy('dusun_id')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => [
                'label' => $r->dusun->nama ?? 'Tidak disebutkan',
                'total' => (int) $r->total,
            ]);

        // ================= WAKTU PENYELESAIAN =================
        $rataRataHari = Laporan::untukPengguna($pengguna)->whereNotNull('selesai_at')
            ->selectRaw('AVG(DATEDIFF(selesai_at, created_at)) as rata')
            ->value('rata');

        // ================= TUNGGAKAN =================
        $tunggakan = Laporan::untukPengguna($pengguna)->whereIn('status', ['menunggu', 'diproses'])
            ->where('created_at', '<=', now()->subDays(self::BATAS_TUNGGAKAN_HARI))
            ->count();

        $laporanTerbaru = Laporan::untukPengguna($pengguna)->with(['kategori', 'dusun'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'totalLaporan' => $totalLaporan,
            'menunggu' => $menunggu,
            'diproses' => $diproses,
            'selesai' => $selesai,
            'ditolak' => $ditolak,
            'persenSelesai' => $persenSelesai,
            'rataRataHari' => $rataRataHari,
            'tunggakan' => $tunggakan,
            'batasTunggakan' => self::BATAS_TUNGGAKAN_HARI,
            'tren' => $tren,
            'perKategori' => $perKategori,
            'perDusun' => $perDusun,
            'laporanTerbaru' => $laporanTerbaru,
        ]);
    }
}