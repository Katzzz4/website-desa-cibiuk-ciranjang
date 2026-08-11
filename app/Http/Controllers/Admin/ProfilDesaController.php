<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilDesaController extends Controller
{
    public function edit()
    {
        $profil = ProfilDesa::first();

        return view('admin.profil.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'sejarah' => 'nullable|string',
            'visi' => 'nullable|string|max:500',
            'misi' => 'nullable|string', // dikirim sebagai textarea, satu poin per baris
            'luas_wilayah_ha' => 'nullable|numeric',
            'batas_utara' => 'nullable|string|max:150',
            'batas_selatan' => 'nullable|string|max:150',
            'batas_timur' => 'nullable|string|max:150',
            'batas_barat' => 'nullable|string|max:150',
            'jarak_ke_kabupaten_km' => 'nullable|numeric',
            'jarak_ke_kecamatan_km' => 'nullable|numeric',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'zoom_peta' => 'nullable|integer|between:5,19',
            'nama_kepala_desa' => 'nullable|string|max:100',
            'alamat_kantor' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'jam_pelayanan' => 'nullable|string|max:500',
            'peta_wilayah' => 'nullable|image|max:5120',
            'logo' => 'nullable|image|max:2048',
            'foto_hero' => 'nullable|image|max:6144',
            'video_profil_url' => 'nullable|url|max:255',
            'video_profil_judul' => 'nullable|string|max:120',
            'video_profil_keterangan' => 'nullable|string|max:300',
        ]);

        $profil = ProfilDesa::first() ?? new ProfilDesa();

        $profil->fill([
            'nama_desa' => $validated['nama_desa'],
            'kecamatan' => $validated['kecamatan'],
            'kabupaten' => $validated['kabupaten'],
            'provinsi' => $validated['provinsi'],
            'sejarah' => $validated['sejarah'] ?? null,
            'visi' => $validated['visi'] ?? null,
            'misi' => collect(explode("\n", $validated['misi'] ?? ''))
                ->map(fn ($p) => trim($p))
                ->filter()
                ->values()
                ->all(),
            'luas_wilayah_ha' => $validated['luas_wilayah_ha'] ?? null,
            'batas_utara' => $validated['batas_utara'] ?? null,
            'batas_selatan' => $validated['batas_selatan'] ?? null,
            'batas_timur' => $validated['batas_timur'] ?? null,
            'batas_barat' => $validated['batas_barat'] ?? null,
            'jarak_ke_kabupaten_km' => $validated['jarak_ke_kabupaten_km'] ?? null,
            'jarak_ke_kecamatan_km' => $validated['jarak_ke_kecamatan_km'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'zoom_peta' => $validated['zoom_peta'] ?? 15,
            'nama_kepala_desa' => $validated['nama_kepala_desa'] ?? null,
            'alamat_kantor' => $validated['alamat_kantor'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'jam_pelayanan' => $validated['jam_pelayanan'] ?? null,
            'video_profil_url' => $validated['video_profil_url'] ?? null,
            'video_profil_judul' => $validated['video_profil_judul'] ?? null,
            'video_profil_keterangan' => $validated['video_profil_keterangan'] ?? null,
        ]);

        // Berkas lama dihapus lebih dulu agar tidak menumpuk di server
        if ($request->hasFile('peta_wilayah')) {
            $this->hapusBerkasLama($profil->peta_wilayah_path);
            $profil->peta_wilayah_path = $request->file('peta_wilayah')->store('profil-desa', 'public');
        }
        if ($request->hasFile('logo')) {
            $this->hapusBerkasLama($profil->logo_path);
            $profil->logo_path = $request->file('logo')->store('profil-desa', 'public');
        }
        if ($request->hasFile('foto_hero')) {
            $this->hapusBerkasLama($profil->foto_hero_path);
            $profil->foto_hero_path = $request->file('foto_hero')->store('profil-desa', 'public');
        }

        $profil->save();

        return back()->with('success', 'Profil desa berhasil diperbarui.');
    }

    /** Menghapus berkas lama bila ada, agar tidak menjadi berkas yatim */
    private function hapusBerkasLama(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}