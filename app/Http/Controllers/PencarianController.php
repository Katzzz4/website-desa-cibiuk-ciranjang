<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\PotensiDesa;
use App\Models\Agenda;
use Illuminate\Http\Request;

class PencarianController extends Controller
{
    /** Panjang minimal kata kunci agar hasilnya tidak terlalu melebar */
    private const MINIMAL_HURUF = 3;

    public function index(Request $request)
    {
        $kata = trim((string) $request->get('q', ''));
        $terlaluPendek = $kata !== '' && mb_strlen($kata) < self::MINIMAL_HURUF;

        $berita = collect();
        $dokumen = collect();
        $potensi = collect();
        $agenda = collect();

        if ($kata !== '' && !$terlaluPendek) {
            $cari = '%' . str_replace(['%', '_'], ['\%', '\_'], $kata) . '%';

            $berita = Berita::whereNotNull('tanggal_publish')
                ->where('tanggal_publish', '<=', now())
                ->where(fn ($q) => $q->where('judul', 'like', $cari)->orWhere('konten', 'like', $cari))
                ->latest('tanggal_publish')
                ->take(10)
                ->get();

            $dokumen = Dokumen::where('nama', 'like', $cari)
                ->latest()
                ->take(10)
                ->get();

            $potensi = PotensiDesa::where(fn ($q) => $q->where('nama', 'like', $cari)->orWhere('deskripsi', 'like', $cari))
                ->latest()
                ->take(10)
                ->get();

            $agenda = Agenda::where(fn ($q) => $q->where('judul', 'like', $cari)->orWhere('deskripsi', 'like', $cari))
                ->orderByDesc('tanggal_mulai')
                ->take(10)
                ->get();
        }

        $jumlah = $berita->count() + $dokumen->count() + $potensi->count() + $agenda->count();

        return view('pencarian.index', compact(
            'kata', 'terlaluPendek', 'berita', 'dokumen', 'potensi', 'agenda', 'jumlah'
        ) + ['minimalHuruf' => self::MINIMAL_HURUF]);
    }
}