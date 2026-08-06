<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Menyusun rekapitulasi laporan pengaduan dalam tata letak dokumen resmi,
 * untuk dicetak atau disimpan sebagai PDF lewat fitur cetak browser.
 */
class RekapLaporanController extends Controller
{
    public function index(Request $request)
    {
        $profil = ProfilDesa::first();

        // Bawaan: bulan berjalan
        $dari = $this->tanggalAman($request->get('dari'), now()->startOfMonth());
        $sampai = $this->tanggalAman($request->get('sampai'), now()->endOfMonth());

        // Bila terbalik, tukar agar rentangnya tetap masuk akal
        if ($dari->gt($sampai)) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        $dari = $dari->startOfDay();
        $sampai = $sampai->endOfDay();

        $laporan = Laporan::with(['kategori:id,nama', 'dusun:id,nama'])
            ->untukPengguna($request->user())
            ->whereBetween('created_at', [$dari, $sampai])
            ->orderBy('created_at')
            ->get();

        // ---------- Ringkasan ----------
        $perStatus = [
            'menunggu' => $laporan->where('status', 'menunggu')->count(),
            'diproses' => $laporan->where('status', 'diproses')->count(),
            'selesai'  => $laporan->where('status', 'selesai')->count(),
            'ditolak'  => $laporan->where('status', 'ditolak')->count(),
        ];

        $total = $laporan->count();
        $persenSelesai = $total > 0 ? round($perStatus['selesai'] / $total * 100, 1) : 0;

        $sudahSelesai = $laporan->whereNotNull('selesai_at');
        $rataRataHari = $sudahSelesai->count() > 0
            ? round($sudahSelesai->avg(fn ($l) => $l->created_at->diffInDays($l->selesai_at)), 1)
            : null;

        // ---------- Rincian ----------
        $perKategori = $laporan
            ->groupBy(fn ($l) => $l->kategori->nama ?? 'Tanpa kategori')
            ->map(fn ($baris, $nama) => [
                'nama'    => $nama,
                'jumlah'  => $baris->count(),
                'selesai' => $baris->where('status', 'selesai')->count(),
            ])
            ->sortByDesc('jumlah')
            ->values();

        $perDusun = $laporan
            ->groupBy(fn ($l) => $l->dusun->nama ?? 'Tidak disebutkan')
            ->map(fn ($baris, $nama) => [
                'nama'    => $nama,
                'jumlah'  => $baris->count(),
                'selesai' => $baris->where('status', 'selesai')->count(),
            ])
            ->sortByDesc('jumlah')
            ->values();

        return view('admin.laporan.rekap', compact(
            'profil', 'laporan', 'dari', 'sampai', 'total',
            'perStatus', 'persenSelesai', 'rataRataHari', 'perKategori', 'perDusun'
        ));
    }

    /** Menerima tanggal dari isian pengguna, kembali ke nilai bawaan bila tidak sah */
    private function tanggalAman(?string $nilai, Carbon $bawaan): Carbon
    {
        if (blank($nilai)) {
            return $bawaan->copy();
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $nilai);
        } catch (\Throwable $e) {
            return $bawaan->copy();
        }
    }
}