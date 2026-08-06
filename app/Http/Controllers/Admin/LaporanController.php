<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanTanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Kepala Dusun hanya melihat laporan di dusunnya sendiri
        $query = Laporan::with(['kategori', 'dusun'])
            ->untukPengguna($request->user())
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori_laporan_id')) {
            $query->where('kategori_laporan_id', $request->kategori_laporan_id);
        }
        if ($request->filled('dusun_id')) {
            $query->where('dusun_id', $request->dusun_id);
        }
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_tiket', 'like', "%{$request->cari}%")
                    ->orWhere('judul', 'like', "%{$request->cari}%");
            });
        }

        $laporan = $query->paginate(15)->withQueryString();

        return view('admin.laporan.index', compact('laporan'));
    }

    public function show(Request $request, Laporan $laporan)
    {
        $this->pastikanBoleh($request, $laporan);

        $laporan->load(['kategori', 'dusun', 'foto', 'tanggapan.user', 'petugas']);

        return view('admin.laporan.show', compact('laporan'));
    }

    public function updateStatus(Request $request, Laporan $laporan)
    {
        $this->pastikanBoleh($request, $laporan);

        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
            'isi_tanggapan' => 'nullable|string|max:1000',
            'alasan_tolak' => 'required_if:status,ditolak|nullable|string|max:500',
            'dokumentasi_selesai' => 'nullable|image|max:4096',
        ]);

        $laporan->status = $validated['status'];
        $laporan->ditangani_oleh = $request->user()->id;

        if ($validated['status'] === 'ditolak') {
            $laporan->alasan_tolak = $validated['alasan_tolak'];
        }

        if ($validated['status'] === 'selesai') {
            $laporan->selesai_at = now();
            if ($request->hasFile('dokumentasi_selesai')) {
                $laporan->dokumentasi_selesai_path = $request->file('dokumentasi_selesai')
                    ->store('dokumentasi-laporan', 'public');
            }
        }

        $laporan->save();

        LaporanTanggapan::create([
            'laporan_id' => $laporan->id,
            'user_id' => $request->user()->id,
            'status_baru' => $validated['status'],
            'isi_tanggapan' => $validated['isi_tanggapan'] ?? null,
        ]);

        return back()->with('success', "Status laporan {$laporan->no_tiket} berhasil diperbarui.");
    }

    private function pastikanBoleh(Request $request, Laporan $laporan): void
    {
        if (!$laporan->bolehDiaksesOleh($request->user())) {
            abort(403, 'Anda hanya dapat mengakses laporan di dusun Anda.');
        }
    }
}