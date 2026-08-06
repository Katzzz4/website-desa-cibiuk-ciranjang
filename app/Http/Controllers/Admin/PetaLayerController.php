<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetaLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetaLayerController extends Controller
{
    /** Batas ukuran berkas dalam kilobyte */
    private const BATAS_KB = 2048;

    public function index()
    {
        $layer = PetaLayer::orderBy('urutan')->orderBy('nama')->get();

        return view('admin.peta-layer.index', compact('layer'));
    }

    public function create()
    {
        return view('admin.peta-layer.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request, wajibBerkas: true);

        PetaLayer::create([
            'nama' => $validated['nama'],
            'keterangan' => $validated['keterangan'] ?? null,
            'file_path' => $this->simpanBerkas($request),
            'warna' => $validated['warna'],
            'opasitas' => $validated['opasitas'],
            'tampil_di_pengaduan' => $request->boolean('tampil_di_pengaduan'),
            'aktif' => $request->boolean('aktif'),
            'urutan' => $validated['urutan'] ?? (PetaLayer::max('urutan') + 1),
        ]);

        return redirect()->route('admin.peta-layer.index')
            ->with('success', "Lapisan peta \"{$validated['nama']}\" berhasil ditambahkan.");
    }

    public function edit(PetaLayer $peta_layer)
    {
        return view('admin.peta-layer.edit', ['layer' => $peta_layer]);
    }

    public function update(Request $request, PetaLayer $peta_layer)
    {
        $validated = $this->validasi($request, wajibBerkas: false);

        if ($request->hasFile('berkas')) {
            // hapus berkas lama agar tidak menumpuk di server
            if ($peta_layer->file_path && Storage::disk('public')->exists($peta_layer->file_path)) {
                Storage::disk('public')->delete($peta_layer->file_path);
            }
            $peta_layer->file_path = $this->simpanBerkas($request);
        }

        $peta_layer->nama = $validated['nama'];
        $peta_layer->keterangan = $validated['keterangan'] ?? null;
        $peta_layer->warna = $validated['warna'];
        $peta_layer->opasitas = $validated['opasitas'];
        $peta_layer->tampil_di_pengaduan = $request->boolean('tampil_di_pengaduan');
        $peta_layer->aktif = $request->boolean('aktif');
        $peta_layer->urutan = $validated['urutan'] ?? $peta_layer->urutan;
        $peta_layer->save();

        return redirect()->route('admin.peta-layer.index')
            ->with('success', 'Lapisan peta berhasil diperbarui.');
    }

    public function destroy(PetaLayer $peta_layer)
    {
        if ($peta_layer->file_path && Storage::disk('public')->exists($peta_layer->file_path)) {
            Storage::disk('public')->delete($peta_layer->file_path);
        }

        $nama = $peta_layer->nama;
        $peta_layer->delete();

        return back()->with('success', "Lapisan peta \"{$nama}\" berhasil dihapus.");
    }

    // ================================================================

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validasi(Request $request, bool $wajibBerkas): array
    {
        $aturanBerkas = [$wajibBerkas ? 'required' : 'nullable', 'file', 'max:' . self::BATAS_KB];

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:200',
            'warna' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'opasitas' => 'required|integer|between:0,100',
            'urutan' => 'nullable|integer|min:0',
            'berkas' => $aturanBerkas,
        ], [
            'berkas.max' => 'Ukuran berkas maksimal 2 MB. Sederhanakan dulu di mapshaper.org bila terlalu besar.',
            'warna.regex' => 'Warna harus berupa kode heksadesimal, contoh #0E5C3A.',
        ]);

        if ($request->hasFile('berkas')) {
            $berkas = $request->file('berkas');

            // Kunci ekstensinya. Folder penyimpanan tersambung ke folder publik,
            // jadi berkas berekstensi lain (misalnya .php) tidak boleh sampai tersimpan
            // meski isinya kebetulan berupa JSON.
            $ekstensi = strtolower($berkas->getClientOriginalExtension());
            if (!in_array($ekstensi, ['geojson', 'json'], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'berkas' => 'Berkas harus berekstensi .geojson atau .json.',
                ]);
            }

            // Pastikan isinya benar-benar GeoJSON, bukan sekadar berekstensi yang cocok
            $isi = file_get_contents($berkas->getRealPath());
            $data = json_decode($isi, true);

            $tipeSah = ['FeatureCollection', 'Feature', 'GeometryCollection',
                        'Polygon', 'MultiPolygon', 'LineString', 'MultiLineString',
                        'Point', 'MultiPoint'];

            if (!is_array($data) || !in_array($data['type'] ?? '', $tipeSah, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'berkas' => 'Berkas ini bukan GeoJSON yang sah. Pastikan diekspor sebagai GeoJSON, '
                        . 'bukan shapefile atau KML.',
                ]);
            }
        }

        return $validated;
    }

    private function simpanBerkas(Request $request): string
    {
        // Nama berkas ditentukan sistem, bukan mengikuti nama unggahan,
        // agar ekstensinya dipastikan .geojson apa pun berkas aslinya.
        $nama = 'layer-' . \Illuminate\Support\Str::random(24) . '.geojson';

        return $request->file('berkas')->storeAs('peta-layer', $nama, 'public');
    }
}