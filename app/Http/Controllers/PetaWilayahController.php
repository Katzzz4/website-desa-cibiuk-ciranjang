<?php

namespace App\Http\Controllers;

use App\Models\ProfilDesa;
use App\Models\Dusun;

class PetaWilayahController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::first();
        $dusun = Dusun::orderBy('nama')->get();

        return view('peta.index', compact('profil', 'dusun'));
    }
}