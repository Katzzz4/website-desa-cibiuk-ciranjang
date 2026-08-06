<?php

namespace App\Http\Controllers;

use App\Models\PotensiDesa;
use Illuminate\Http\Request;

class PotensiController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->get('jenis');

        $potensi = PotensiDesa::query()
            ->when($jenis, fn ($q) => $q->where('jenis', $jenis))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // hanya tampilkan tab jenis yang benar-benar ada datanya
        $jenisTersedia = PotensiDesa::select('jenis')->distinct()->pluck('jenis')->all();

        return view('potensi.index', compact('potensi', 'jenis', 'jenisTersedia'));
    }
}