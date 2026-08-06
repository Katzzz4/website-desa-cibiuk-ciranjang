<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    /** Halaman awal: warga memilih klasifikasi terlebih dahulu */
    public function index()
    {
        // jumlah dokumen per klasifikasi, untuk ditampilkan pada kartu pilihan
        $jumlah = Dokumen::selectRaw('klasifikasi, COUNT(*) as total')
            ->groupBy('klasifikasi')
            ->pluck('total', 'klasifikasi');

        return view('dokumen.pilih', compact('jumlah'));
    }

    /** Daftar dokumen pada satu klasifikasi */
    public function daftar(Request $request, string $klasifikasi)
    {
        abort_unless(array_key_exists($klasifikasi, Dokumen::KLASIFIKASI), 404);

        $info = Dokumen::KLASIFIKASI[$klasifikasi];
        $kategori = $request->get('kategori');

        $dokumen = Dokumen::where('klasifikasi', $klasifikasi)
            ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
            ->when($request->filled('cari'), fn ($q) => $q->where('nama', 'like', '%' . $request->cari . '%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // hanya tampilkan tab jenis yang benar-benar ada isinya
        $jenisTerpakai = Dokumen::where('klasifikasi', $klasifikasi)
            ->distinct()->pluck('kategori')->all();

        return view('dokumen.index', compact(
            'dokumen', 'klasifikasi', 'info', 'kategori', 'jenisTerpakai'
        ));
    }

    public function unduh(Dokumen $dokumen)
    {
        if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'Berkas dokumen tidak ditemukan.');
        }

        $namaUnduhan = Str::slug($dokumen->nama) . '.' . $dokumen->ekstensi;

        return Storage::disk('public')->download($dokumen->file_path, $namaUnduhan);
    }
}