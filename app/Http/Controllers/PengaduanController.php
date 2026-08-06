<?php

namespace App\Http\Controllers;

use App\Models\Dusun;
use App\Models\ProfilDesa;
use App\Models\PetaLayer;
use App\Models\KategoriLaporan;
use App\Models\Laporan;
use App\Models\LaporanFoto;
use App\Models\LaporanTanggapan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function create()
    {
        $kategori = KategoriLaporan::orderBy('nama')->get();
        $dusun = Dusun::orderBy('nama')->get();

        $titikPeta = (ProfilDesa::first()?->titik_peta) ?? ProfilDesa::KOORDINAT_CADANGAN;

        // lapisan batas wilayah membantu warga mengenali area desa saat menandai lokasi
        $layerPeta = PetaLayer::aktif()->where('tampil_di_pengaduan', true)
            ->get()->map(fn ($l) => $l->untukPeta());

        return view('pengaduan.create', compact('kategori', 'dusun', 'titikPeta', 'layerPeta'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anonim' => 'nullable|boolean',
            'nama_pelapor' => 'required_if:anonim,0|nullable|string|max:150',
            'no_hp' => 'required_if:anonim,0|nullable|string|max:20',
            'kategori_laporan_id' => 'required|exists:kategori_laporan,id',
            'dusun_id' => 'nullable|exists:dusun,id',
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string|max:2000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat_lokasi' => 'nullable|string|max:255',
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|max:4096',
        ]);

        $anonim = $request->boolean('anonim');

        $laporan = Laporan::create([
            'kategori_laporan_id' => $validated['kategori_laporan_id'],
            'dusun_id' => $validated['dusun_id'] ?? null,
            'anonim' => $anonim,
            'nama_pelapor' => $anonim ? null : $validated['nama_pelapor'],
            'no_hp' => $anonim ? null : $validated['no_hp'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'alamat_lokasi' => $validated['alamat_lokasi'] ?? null,
            'tanggal_kejadian' => $validated['tanggal_kejadian'],
            'status' => 'menunggu',
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                LaporanFoto::create([
                    'laporan_id' => $laporan->id,
                    'file_path' => $foto->store('laporan-foto', 'public'),
                ]);
            }
        }

        LaporanTanggapan::create([
            'laporan_id' => $laporan->id,
            'user_id' => null,
            'status_baru' => 'menunggu',
            'isi_tanggapan' => 'Laporan diterima, menunggu verifikasi petugas.',
        ]);

        return redirect()
            ->route('pengaduan.berhasil', $laporan->no_tiket);
    }

    public function berhasil(string $noTiket)
    {
        $laporan = Laporan::where('no_tiket', $noTiket)->firstOrFail();

        return view('pengaduan.berhasil', compact('laporan'));
    }

    public function formLacak()
    {
        return view('pengaduan.lacak');
    }

    public function lacak(Request $request)
    {
        $request->validate([
            'no_tiket' => 'required|string',
        ]);

        $laporan = Laporan::with(['kategori', 'dusun', 'tanggapan.user'])
            ->where('no_tiket', trim($request->no_tiket))
            ->first();

        if (!$laporan) {
            return back()
                ->withInput()
                ->withErrors(['no_tiket' => 'Nomor tiket tidak ditemukan. Periksa kembali penulisannya.']);
        }

        return view('pengaduan.lacak', compact('laporan'));
    }
}