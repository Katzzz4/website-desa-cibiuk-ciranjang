<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use App\Models\Dusun;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    public function index()
    {
        $perangkat = PerangkatDesa::with('dusun')->orderBy('urutan')->get();

        return view('admin.perangkat.index', compact('perangkat'));
    }

    public function create()
    {
        $dusun = Dusun::orderBy('nama')->get();
        $jabatanList = PerangkatDesa::orderBy('urutan')->pluck('jabatan')->unique();

        return view('admin.perangkat.create', compact('dusun', 'jabatanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'atasan_jabatan' => 'nullable|string|max:100',
            'dusun_id' => 'nullable|exists:dusun,id',
            'foto' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('perangkat-desa', 'public')
            : null;

        PerangkatDesa::create([
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'atasan_jabatan' => $validated['atasan_jabatan'] ?: null,
            'dusun_id' => $validated['dusun_id'] ?? null,
            'foto_path' => $fotoPath,
            'urutan' => $validated['urutan'] ?? (PerangkatDesa::max('urutan') + 1),
        ]);

        return redirect()->route('admin.perangkat.index')->with('success', 'Perangkat desa berhasil ditambahkan.');
    }

    public function edit(PerangkatDesa $perangkat)
    {
        $dusun = Dusun::orderBy('nama')->get();
        $jabatanList = PerangkatDesa::where('id', '!=', $perangkat->id)->orderBy('urutan')->pluck('jabatan')->unique();

        return view('admin.perangkat.edit', compact('perangkat', 'dusun', 'jabatanList'));
    }

    public function update(Request $request, PerangkatDesa $perangkat)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'atasan_jabatan' => 'nullable|string|max:100',
            'dusun_id' => 'nullable|exists:dusun,id',
            'foto' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer',
        ]);

        if ($request->hasFile('foto')) {
            $perangkat->foto_path = $request->file('foto')->store('perangkat-desa', 'public');
        }

        $perangkat->nama = $validated['nama'];
        $perangkat->jabatan = $validated['jabatan'];
        $perangkat->atasan_jabatan = $validated['atasan_jabatan'] ?: null;
        $perangkat->dusun_id = $validated['dusun_id'] ?? null;
        $perangkat->urutan = $validated['urutan'] ?? $perangkat->urutan;
        $perangkat->save();

        return redirect()->route('admin.perangkat.index')->with('success', 'Data perangkat desa berhasil diperbarui.');
    }

    public function destroy(PerangkatDesa $perangkat)
    {
        $perangkat->delete();

        return back()->with('success', 'Perangkat desa berhasil dihapus.');
    }
}
