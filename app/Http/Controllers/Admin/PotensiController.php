<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PotensiDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PotensiController extends Controller
{
    public function index(Request $request)
    {
        $potensi = PotensiDesa::query()
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis', $request->jenis))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.potensi.index', compact('potensi'));
    }

    public function create()
    {
        return view('admin.potensi.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request, wajibFoto: false);

        PotensiDesa::create([
            'jenis' => $validated['jenis'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kontak' => $validated['kontak'] ?? null,
            'foto_path' => $request->hasFile('foto')
                ? $request->file('foto')->store('potensi-desa', 'public')
                : null,
        ]);

        return redirect()->route('admin.potensi.index')->with('success', 'Potensi desa berhasil ditambahkan.');
    }

    public function edit(PotensiDesa $potensi)
    {
        return view('admin.potensi.edit', compact('potensi'));
    }

    public function update(Request $request, PotensiDesa $potensi)
    {
        $validated = $this->validasi($request, wajibFoto: false);

        if ($request->hasFile('foto')) {
            // hapus foto lama supaya tidak menumpuk di storage
            if ($potensi->foto_path && Storage::disk('public')->exists($potensi->foto_path)) {
                Storage::disk('public')->delete($potensi->foto_path);
            }
            $potensi->foto_path = $request->file('foto')->store('potensi-desa', 'public');
        }

        $potensi->jenis = $validated['jenis'];
        $potensi->nama = $validated['nama'];
        $potensi->deskripsi = $validated['deskripsi'] ?? null;
        $potensi->kontak = $validated['kontak'] ?? null;
        $potensi->save();

        return redirect()->route('admin.potensi.index')->with('success', 'Potensi desa berhasil diperbarui.');
    }

    public function destroy(PotensiDesa $potensi)
    {
        if ($potensi->foto_path && Storage::disk('public')->exists($potensi->foto_path)) {
            Storage::disk('public')->delete($potensi->foto_path);
        }

        $potensi->delete();

        return back()->with('success', 'Potensi desa berhasil dihapus.');
    }

    private function validasi(Request $request, bool $wajibFoto): array
    {
        return $request->validate([
            'jenis' => ['required', Rule::in(array_keys(PotensiDesa::JENIS))],
            'nama' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:2000',
            'kontak' => 'nullable|string|max:30',
            'foto' => ($wajibFoto ? 'required' : 'nullable') . '|image|max:4096',
        ], [
            'foto.image' => 'Berkas harus berupa gambar.',
            'foto.max' => 'Ukuran gambar maksimal 4 MB.',
        ]);
    }
}