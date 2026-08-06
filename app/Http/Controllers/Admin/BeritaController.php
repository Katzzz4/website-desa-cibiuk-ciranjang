<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $berita = Berita::with('penulis')
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'konten' => 'required|string',
            'kategori' => 'required|in:berita,pengumuman',
            'thumbnail' => 'nullable|image|max:4096',
            'tanggal_publish' => 'nullable|date',
            'status_publish' => 'required|in:draft,publish',
        ]);

        $slug = Str::slug($validated['judul']);
        $asliSlug = $slug;
        $i = 1;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = "{$asliSlug}-{$i}";
            $i++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('berita-thumbnail', 'public');
        }

        Berita::create([
            'user_id' => $request->user()->id,
            'judul' => $validated['judul'],
            'slug' => $slug,
            'konten' => $validated['konten'],
            'kategori' => $validated['kategori'],
            'thumbnail_path' => $thumbnailPath,
            'tanggal_publish' => $validated['status_publish'] === 'publish'
                ? ($validated['tanggal_publish'] ?? now())
                : null,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'konten' => 'required|string',
            'kategori' => 'required|in:berita,pengumuman',
            'thumbnail' => 'nullable|image|max:4096',
            'tanggal_publish' => 'nullable|date',
            'status_publish' => 'required|in:draft,publish',
        ]);

        if ($request->hasFile('thumbnail')) {
            $berita->thumbnail_path = $request->file('thumbnail')->store('berita-thumbnail', 'public');
        }

        $berita->judul = $validated['judul'];
        $berita->konten = $validated['konten'];
        $berita->kategori = $validated['kategori'];
        $berita->tanggal_publish = $validated['status_publish'] === 'publish'
            ? ($validated['tanggal_publish'] ?? $berita->tanggal_publish ?? now())
            : null;
        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
