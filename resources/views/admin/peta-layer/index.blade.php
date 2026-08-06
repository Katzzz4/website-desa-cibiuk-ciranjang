@extends('layouts.admin')

@section('title', 'Lapisan Peta')

@section('header-action')
    <a href="{{ route('admin.peta-layer.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tambah Lapisan
    </a>
@endsection

@section('content')
<div class="space-y-4">

    <div class="bg-white rounded-2xl border border-black/5 p-5">
        <p class="text-sm text-black/60 leading-relaxed">
            Lapisan peta berupa berkas <strong>GeoJSON</strong> yang ditumpuk di atas peta desa —
            misalnya batas wilayah, batas dusun, atau area persawahan.
            Setiap berkas menjadi satu lapisan dengan satu warna, sehingga keterangannya mudah dibaca warga.
        </p>
        <p class="text-xs text-black/45 mt-2 leading-relaxed">
            Punya berkas dalam bentuk <em>shapefile</em> dari ArcGIS? Ubah dulu ke GeoJSON lewat
            QGIS atau <a href="https://mapshaper.org" target="_blank" rel="noopener"
            class="underline underline-offset-2" style="color: var(--talang);">mapshaper.org</a>,
            sekaligus sederhanakan agar ukurannya ringan saat dibuka lewat data seluler.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                    <th class="px-5 py-3">Lapisan</th>
                    <th class="px-5 py-3">Berkas</th>
                    <th class="px-5 py-3">Tampil di Peta Laporan</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($layer as $l)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <span class="w-4 h-4 rounded shrink-0 border border-black/10"
                                      style="background: {{ $l->warna }};"></span>
                                <div>
                                    <p class="font-medium">{{ $l->nama }}</p>
                                    @if($l->keterangan)
                                        <p class="text-xs text-black/45 mt-0.5">{{ $l->keterangan }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-black/60">
                            @if($l->ukuran_terbaca)
                                {{ $l->ukuran_terbaca }}
                            @else
                                <span class="text-red-600 text-xs">berkas hilang</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-black/60">
                            {{ $l->tampil_di_pengaduan ? 'Ya' : 'Tidak' }}
                        </td>
                        <td class="px-5 py-3">
                            @if($l->aktif)
                                <span class="text-xs px-2.5 py-1 rounded-full badge-status-selesai">Aktif</span>
                            @else
                                <span class="text-xs px-2.5 py-1 rounded-full badge-status-menunggu">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                <x-aksi.edit :href="route('admin.peta-layer.edit', $l)" />
                                <x-aksi.hapus :action="route('admin.peta-layer.destroy', $l)"
                                              judul="Hapus lapisan peta ini?"
                                              konfirmasi="Berkas GeoJSON-nya ikut terhapus dari server dan lapisan ini akan hilang dari peta desa."
                                              :nama="$l->nama" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-black/40">Belum ada lapisan peta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection