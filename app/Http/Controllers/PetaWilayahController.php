<?php

namespace App\Http\Controllers;

use App\Models\PetaLayer;
use App\Models\ProfilDesa;
use App\Models\Dusun;

class PetaWilayahController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::first();
        $dusun = Dusun::orderBy('nama')->get();

        $layer = PetaLayer::aktif()->get()->map(fn ($l) => $l->untukPeta() + [
            'keterangan' => $l->keterangan,
        ]);

        $titikPeta = ($profil?->titik_peta) ?? ProfilDesa::KOORDINAT_CADANGAN;

        return view('peta.index', compact('profil', 'dusun', 'layer', 'titikPeta'));
    }
}