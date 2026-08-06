<?php

namespace App\Http\Controllers;

use App\Models\PendudukKategori;
use App\Models\PendudukRingkasan;
use App\Models\Dusun;

class InfografisController extends Controller
{
    public function penduduk()
    {
        $ringkasan = PendudukRingkasan::orderByDesc('tahun')->first();
        $dusun = Dusun::orderBy('nama')->get();

        $kategori = PendudukKategori::with(['data' => function ($q) use ($ringkasan) {
            $q->when($ringkasan, fn ($q) => $q->where('tahun', $ringkasan->tahun))
              ->orderByDesc('jumlah_laki');
        }])->orderBy('urutan')->get();

        return view('infografis.penduduk', compact('ringkasan', 'dusun', 'kategori'));
    }
}
