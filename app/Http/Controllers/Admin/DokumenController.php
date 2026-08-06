<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $dokumen = Dokumen::query()
            ->when($request->filled('klasifikasi'), fn ($q) => $q->where('klasifikasi', $request->klasifikasi))
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.dokumen.index', compact('dokumen'));
    }

    public function create()
    {
        return view('admin.dokumen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'klasifikasi' => ['required', \Illuminate\Validation\Rule::in(array_keys(\App\Models\Dokumen::KLASIFIKASI))],
            'kategori' => 'required|string|max:30',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ], [
            'file.mimes' => 'Berkas harus berformat PDF, Word, atau Excel.',
            'file.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        $this->pastikanJenisCocok($validated);

        Dokumen::create([
            'nama' => $validated['nama'],
            'klasifikasi' => $validated['klasifikasi'],
            'kategori' => $validated['kategori'],
            'file_path' => $request->file('file')->store('dokumen-desa', 'public'),
        ]);

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function edit(Dokumen $dokumen)
    {
        return view('admin.dokumen.edit', compact('dokumen'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'klasifikasi' => ['required', \Illuminate\Validation\Rule::in(array_keys(\App\Models\Dokumen::KLASIFIKASI))],
            'kategori' => 'required|string|max:30',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ], [
            'file.mimes' => 'Berkas harus berformat PDF, Word, atau Excel.',
            'file.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        if ($request->hasFile('file')) {
            // hapus berkas lama supaya tidak menumpuk di storage
            if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            $dokumen->file_path = $request->file('file')->store('dokumen-desa', 'public');
        }

        $this->pastikanJenisCocok($validated);

        $dokumen->nama = $validated['nama'];
        $dokumen->klasifikasi = $validated['klasifikasi'];
        $dokumen->kategori = $validated['kategori'];
        $dokumen->save();

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Dokumen $dokumen)
    {
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    /** Jenis dokumen harus termasuk daftar pada klasifikasi yang dipilih */
    private function pastikanJenisCocok(array $data): void
    {
        $jenisSah = array_keys(\App\Models\Dokumen::jenisUntuk($data['klasifikasi']));

        if (!in_array($data['kategori'], $jenisSah, true)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'kategori' => 'Jenis dokumen tidak sesuai dengan klasifikasi yang dipilih.',
            ]);
        }
    }
}