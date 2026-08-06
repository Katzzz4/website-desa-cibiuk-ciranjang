<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');

        $berita = Berita::query()
            ->whereNotNull('tanggal_publish')
            ->where('tanggal_publish', '<=', now())
            ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
            ->latest('tanggal_publish')
            ->paginate(9)
            ->withQueryString();

        return view('berita.index', compact('berita', 'kategori'));
    }

    public function show(string $slug)
    {
        $berita = Berita::where('slug', $slug)
            ->whereNotNull('tanggal_publish')
            ->where('tanggal_publish', '<=', now())
            ->firstOrFail();

        $lainnya = Berita::where('id', '!=', $berita->id)
            ->whereNotNull('tanggal_publish')
            ->where('tanggal_publish', '<=', now())
            ->latest('tanggal_publish')
            ->take(3)
            ->get();

        return view('berita.show', compact('berita', 'lainnya'));
    }
}
