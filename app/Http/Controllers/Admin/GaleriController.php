<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Galeri::latest()->paginate(15);

        return view('admin.galeri.index', compact('galeri'));
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'tipe' => 'required|in:foto,video',
            'file' => 'required_if:tipe,foto|nullable|image|max:5120',
            'url_video' => 'required_if:tipe,video|nullable|url|max:255',
        ]);

        $filePath = null;
        if ($validated['tipe'] === 'foto' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('galeri', 'public');
        }

        Galeri::create([
            'judul' => $validated['judul'],
            'tipe' => $validated['tipe'],
            'file_path' => $filePath,
            'url_video' => $validated['tipe'] === 'video' ? $validated['url_video'] : null,
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'tipe' => 'required|in:foto,video',
            'file' => 'nullable|image|max:5120',
            'url_video' => 'required_if:tipe,video|nullable|url|max:255',
        ]);

        if ($validated['tipe'] === 'foto' && $request->hasFile('file')) {
            $galeri->file_path = $request->file('file')->store('galeri', 'public');
        }

        $galeri->judul = $validated['judul'];
        $galeri->tipe = $validated['tipe'];
        $galeri->url_video = $validated['tipe'] === 'video' ? $validated['url_video'] : null;
        $galeri->save();

        return redirect()->route('admin.galeri.index')->with('success', 'Item galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri)
    {
        $galeri->delete();

        return back()->with('success', 'Item galeri berhasil dihapus.');
    }
}