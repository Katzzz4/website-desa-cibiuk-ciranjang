<?php

namespace App\Http\Controllers;

use App\Models\ProfilDesa;
use App\Models\PendudukRingkasan;
use App\Models\Dusun;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\Laporan;
use App\Models\PotensiDesa;

class BerandaController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::first();
        $ringkasan = PendudukRingkasan::orderByDesc('tahun')->first();

        $beritaTerbaru = Berita::whereNotNull('tanggal_publish')
            ->where('tanggal_publish', '<=', now())
            ->latest('tanggal_publish')
            ->take(3)
            ->get();

        $agendaTerdekat = Agenda::where('tanggal_mulai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        $potensiSorotan = PotensiDesa::latest()->take(3)->get();

        $totalLaporan = Laporan::count();
        $laporanSelesai = Laporan::where('status', 'selesai')->count();

        return view('beranda', [
            'profil' => $profil,
            'ringkasan' => $ringkasan,
            'jumlahDusun' => Dusun::count(),
            'beritaTerbaru' => $beritaTerbaru,
            'agendaTerdekat' => $agendaTerdekat,
            'potensiSorotan' => $potensiSorotan,
            'totalLaporan' => $totalLaporan,
            'laporanSelesai' => $laporanSelesai,
            'persenSelesai' => $totalLaporan > 0 ? round($laporanSelesai / $totalLaporan * 100) : null,
        ]);
    }
}