<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendudukRingkasan;
use App\Models\PendudukKategori;
use App\Models\PendudukData;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InfografisController extends Controller
{
    // ================= RINGKASAN =================
    public function editRingkasan()
    {
        $ringkasan = PendudukRingkasan::orderByDesc('tahun')->first();

        return view('admin.infografis.ringkasan', compact('ringkasan'));
    }

    public function updateRingkasan(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2000|max:2100',
            'total_kk' => 'nullable|integer|min:0',
            'total_laki' => 'required|integer|min:0',
            'total_perempuan' => 'required|integer|min:0',
            'lahir_laki' => 'nullable|integer|min:0',
            'lahir_perempuan' => 'nullable|integer|min:0',
            'mati_laki' => 'nullable|integer|min:0',
            'mati_perempuan' => 'nullable|integer|min:0',
            'datang_laki' => 'nullable|integer|min:0',
            'datang_perempuan' => 'nullable|integer|min:0',
            'pergi_laki' => 'nullable|integer|min:0',
            'pergi_perempuan' => 'nullable|integer|min:0',
        ]);

        PendudukRingkasan::updateOrCreate(
            ['tahun' => $validated['tahun']],
            collect($validated)->except('tahun')->map(fn ($v) => $v ?? 0)->all()
        );

        return back()->with('success', 'Ringkasan penduduk berhasil disimpan.');
    }

    // ================= DATA PER KATEGORI =================
    public function dataIndex(Request $request)
    {
        $kategoriAktif = $request->get('kategori_id');
        $kategoriList = PendudukKategori::orderBy('urutan')->get();

        $data = PendudukData::with(['kategori', 'dusun'])
            ->when($kategoriAktif, fn ($q) => $q->where('penduduk_kategori_id', $kategoriAktif))
            ->orderByDesc('tahun')
            ->orderBy('label')
            ->paginate(20)
            ->withQueryString();

        return view('admin.infografis.index', compact('data', 'kategoriList', 'kategoriAktif'));
    }

    public function dataCreate()
    {
        $kategoriList = PendudukKategori::orderBy('urutan')->get();
        $dusun = Dusun::orderBy('nama')->get();

        return view('admin.infografis.create', compact('kategoriList', 'dusun'));
    }

    public function dataStore(Request $request)
    {
        $validated = $request->validate([
            'penduduk_kategori_id' => 'required|exists:penduduk_kategori,id',
            'dusun_id' => 'nullable|exists:dusun,id',
            'label' => 'required|string|max:100',
            'jumlah_laki' => 'required|integer|min:0',
            'jumlah_perempuan' => 'required|integer|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        PendudukData::create($validated);

        return redirect()->route('admin.infografis.data.index')->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function dataEdit(PendudukData $datum)
    {
        $kategoriList = PendudukKategori::orderBy('urutan')->get();
        $dusun = Dusun::orderBy('nama')->get();

        return view('admin.infografis.edit', ['data' => $datum, 'kategoriList' => $kategoriList, 'dusun' => $dusun]);
    }

    public function dataUpdate(Request $request, PendudukData $datum)
    {
        $validated = $request->validate([
            'penduduk_kategori_id' => 'required|exists:penduduk_kategori,id',
            'dusun_id' => 'nullable|exists:dusun,id',
            'label' => 'required|string|max:100',
            'jumlah_laki' => 'required|integer|min:0',
            'jumlah_perempuan' => 'required|integer|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        $datum->update($validated);

        return redirect()->route('admin.infografis.data.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function dataDestroy(PendudukData $datum)
    {
        $datum->delete();

        return back()->with('success', 'Data penduduk berhasil dihapus.');
    }

    // ================= KATEGORI =================

    public function kategoriIndex()
    {
        $kategoriList = PendudukKategori::withCount('data')
            ->orderBy('urutan')
            ->get();

        return view('admin.infografis.kategori.index', compact('kategoriList'));
    }

    public function kategoriCreate()
    {
        return view('admin.infografis.kategori.create');
    }

    public function kategoriStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'urutan' => 'nullable|integer|min:0',
        ]);

        PendudukKategori::create([
            'nama' => $validated['nama'],
            'slug' => $this->slugUnik($validated['nama']),
            'urutan' => $validated['urutan'] ?? (PendudukKategori::max('urutan') + 1),
        ]);

        return redirect()->route('admin.infografis.kategori.index')
            ->with('success', "Kategori \"{$validated['nama']}\" berhasil ditambahkan.");
    }

    public function kategoriEdit(PendudukKategori $kategori)
    {
        return view('admin.infografis.kategori.edit', compact('kategori'));
    }

    public function kategoriUpdate(Request $request, PendudukKategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'urutan' => 'nullable|integer|min:0',
        ]);

        // slug hanya dibuat ulang bila namanya benar-benar berubah
        if ($kategori->nama !== $validated['nama']) {
            $kategori->slug = $this->slugUnik($validated['nama'], $kategori->id);
        }

        $kategori->nama = $validated['nama'];
        $kategori->urutan = $validated['urutan'] ?? $kategori->urutan;
        $kategori->save();

        return redirect()->route('admin.infografis.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function kategoriDestroy(PendudukKategori $kategori)
    {
        // menghapus kategori ikut menghapus seluruh data di dalamnya
        $jumlahData = $kategori->data()->count();

        $nama = $kategori->nama;
        $kategori->delete();

        $pesan = $jumlahData > 0
            ? "Kategori \"{$nama}\" beserta {$jumlahData} baris datanya berhasil dihapus."
            : "Kategori \"{$nama}\" berhasil dihapus.";

        return redirect()->route('admin.infografis.kategori.index')->with('success', $pesan);
    }

    /** Membuat slug yang tidak bentrok dengan kategori lain */
    private function slugUnik(string $nama, ?int $kecualiId = null): string
    {
        $dasar = Str::slug($nama);
        $slug = $dasar;
        $i = 1;

        while (PendudukKategori::where('slug', $slug)
            ->when($kecualiId, fn ($q) => $q->where('id', '!=', $kecualiId))
            ->exists()) {
            $slug = "{$dasar}-{$i}";
            $i++;
        }

        return $slug;
    }
}