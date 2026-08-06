<?php

namespace App\Http\Controllers;

use App\Models\ProfilDesa;
use App\Models\PerangkatDesa;
use App\Models\Dusun;
use App\Models\PendudukRingkasan;

class ProfilDesaController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::first();
        $dusun = Dusun::orderBy('nama')->get();
        $perangkat = PerangkatDesa::with('dusun')->orderBy('urutan')->get();
        $ringkasan = PendudukRingkasan::orderByDesc('tahun')->first();

        return view('profil.index', compact('profil', 'dusun', 'perangkat', 'ringkasan'));
    }
}