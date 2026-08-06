<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\KategoriLaporan;
use App\Models\Dusun;
use App\Models\ProfilDesa;
use App\Models\PetaLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PetaLaporanController extends Controller
{
    /** Status yang dianggap masih perlu ditindaklanjuti */
    private const STATUS_AKTIF = ['menunggu', 'diproses'];

    public function index(Request $request)
    {
        $kategoriList = KategoriLaporan::orderBy('nama')->get();
        $dusunList = Dusun::orderBy('nama')->get();

        $bulanIni = now()->format('Y-m');
        $periode = $request->get('periode', $bulanIni); // format 'Y-m', atau 'semua'
        $isPeriodeBerjalan = $periode === $bulanIni;

        $query = Laporan::with(['kategori', 'dusun'])
            ->untukPengguna($request->user())
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($periode !== 'semua') {
            [$tahun, $bulan] = array_pad(explode('-', $periode), 2, null);

            $query->where(function ($q) use ($tahun, $bulan, $isPeriodeBerjalan) {
                $q->where(function ($sub) use ($tahun, $bulan) {
                    $sub->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $bulan);
                });

                // Pada periode berjalan, laporan yang belum tuntas SELALU ikut tampil
                // walaupun masuknya bulan-bulan sebelumnya, supaya tidak ada masalah
                // yang "hilang" dari pantauan hanya karena pergantian bulan.
                if ($isPeriodeBerjalan) {
                    $q->orWhereIn('status', self::STATUS_AKTIF);
                }
            });
        }

        $query->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('kategori_laporan_id'), fn ($q) => $q->where('kategori_laporan_id', $request->kategori_laporan_id))
            ->when($request->filled('dusun_id'), fn ($q) => $q->where('dusun_id', $request->dusun_id));

        $laporan = $query->latest()->get();

        // data ringkas untuk marker — tidak mengirim data pribadi pelapor
        $markers = $laporan->map(function ($l) use ($periode, $bulanIni) {
            $dariBulanLain = $periode !== 'semua'
                && $l->created_at->format('Y-m') !== $periode;

            return [
                'no_tiket' => $l->no_tiket,
                'judul' => $l->judul,
                'kategori' => $l->kategori->nama ?? '-',
                'dusun' => $l->dusun->nama ?? null,
                'status' => $l->status,
                'tanggal' => $l->tanggal_kejadian->format('d M Y'),
                'bulan_masuk' => $l->created_at->translatedFormat('F Y'),
                'luar_periode' => $dariBulanLain,
                'lat' => (float) $l->latitude,
                'lng' => (float) $l->longitude,
                'url' => route('admin.laporan.show', $l),
            ];
        });

        $jumlahPerStatus = [
            'menunggu' => $laporan->where('status', 'menunggu')->count(),
            'diproses' => $laporan->where('status', 'diproses')->count(),
            'selesai'  => $laporan->where('status', 'selesai')->count(),
            'ditolak'  => $laporan->where('status', 'ditolak')->count(),
        ];

        // jumlah laporan lama yang "dibawa" ke periode berjalan karena belum tuntas
        $tunggakan = $isPeriodeBerjalan
            ? $markers->where('luar_periode', true)->count()
            : 0;

        // daftar bulan yang punya laporan, untuk dropdown riwayat
        $daftarPeriode = Laporan::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan')
            )
            ->groupBy('tahun', 'bulan')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(function ($row) {
                $tgl = Carbon::createFromDate($row->tahun, $row->bulan, 1);

                return [
                    'value' => $tgl->format('Y-m'),
                    'label' => $tgl->translatedFormat('F Y'),
                ];
            });

        // pastikan bulan berjalan selalu ada di dropdown meski belum ada laporan
        if (!$daftarPeriode->contains('value', $bulanIni)) {
            $daftarPeriode->prepend([
                'value' => $bulanIni,
                'label' => now()->translatedFormat('F Y'),
            ]);
        }

        $labelPeriode = $periode === 'semua'
            ? 'Seluruh Periode'
            : (Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y'));

        $tanpaLokasi = Laporan::untukPengguna($request->user())->where(function ($q) {
                $q->whereNull('latitude')->orWhereNull('longitude');
            })->count();

        $titikPeta = (ProfilDesa::first()?->titik_peta) ?? ProfilDesa::KOORDINAT_CADANGAN;

        $layerPeta = PetaLayer::aktif()->where('tampil_di_pengaduan', true)
            ->get()->map(fn ($l) => $l->untukPeta());

        return view('admin.laporan.peta', compact(
            'titikPeta', 'layerPeta',
            'markers', 'kategoriList', 'dusunList', 'jumlahPerStatus',
            'tanpaLokasi', 'daftarPeriode', 'periode', 'labelPeriode',
            'isPeriodeBerjalan', 'tunggakan'
        ));
    }
}