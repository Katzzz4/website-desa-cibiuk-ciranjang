<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->get('tipe');

        $galeri = Galeri::query()
            ->when($tipe, fn ($q) => $q->where('tipe', $tipe))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('galeri.index', compact('galeri', 'tipe'));
    }
}