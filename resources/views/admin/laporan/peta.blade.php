@extends('layouts.admin')

@section('title', 'Peta Sebaran Laporan')

@section('content')
@php
    $pengguna = auth()->user();
@endphp

@if($pengguna->role === 'kadus')
    @if($pengguna->dusun_id)
        <div class="mb-5 rounded-xl px-4 py-3 text-sm flex items-start gap-2.5"
             style="background: var(--sawah-light); color: var(--sawah-dark);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.7" stroke="currentColor" class="w-4 h-4 shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <span>
                Anda masuk sebagai Kepala Dusun, jadi yang ditampilkan hanya laporan
                di <strong>Dusun {{ $pengguna->dusun->nama ?? '-' }}</strong>.
            </span>
        </div>
    @else
        <div class="mb-5 rounded-xl px-4 py-3 text-sm flex items-start gap-2.5"
             style="background: #FEF6E7; color: #92600E;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.7" stroke="currentColor" class="w-4 h-4 shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span>
                Akun Anda belum ditautkan ke dusun mana pun, sehingga belum ada laporan yang dapat ditampilkan.
                Silakan hubungi Super Admin desa untuk menetapkan dusun Anda.
            </span>
        </div>
    @endif
@endif

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

<style>
    .cluster-cibiuk {
        background: rgba(31, 61, 43, 0.85);
        color: #fff;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: 13px;
        border: 3px solid rgba(201, 154, 61, 0.55);
    }
</style>

<div class="space-y-5">

    {{-- PERIODE --}}
    <form method="GET" class="bg-white rounded-2xl border border-black/5 p-4 flex flex-wrap gap-3 items-center">
        <div class="flex items-center gap-2">
            <label class="text-xs text-black/50">Periode</label>
            <select name="periode" onchange="this.form.submit()" class="rounded-lg border-black/10 text-sm">
                @foreach ($daftarPeriode as $p)
                    <option value="{{ $p['value'] }}" @selected($periode === $p['value'])>{{ $p['label'] }}</option>
                @endforeach
                <option value="semua" @selected($periode === 'semua')>Seluruh Periode</option>
            </select>
        </div>

        <span class="w-px h-6 bg-black/10 hidden sm:block"></span>

        <select name="status" class="rounded-lg border-black/10 text-sm">
            <option value="">Semua Status</option>
            @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $label)
                <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
            @endforeach
        </select>

        <select name="kategori_laporan_id" class="rounded-lg border-black/10 text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($kategoriList as $k)
                <option value="{{ $k->id }}" @selected(request('kategori_laporan_id') == $k->id)>{{ $k->nama }}</option>
            @endforeach
        </select>

        @if($pengguna->role !== 'kadus')
        <select name="dusun_id" class="rounded-lg border-black/10 text-sm">
            <option value="">Semua Dusun</option>
            @foreach ($dusunList as $d)
                <option value="{{ $d->id }}" @selected(request('dusun_id') == $d->id)>{{ $d->nama }}</option>
            @endforeach
        </select>
        @endif

        <button class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">Terapkan</button>

        @if(request()->hasAny(['status', 'kategori_laporan_id', 'dusun_id']))
            <a href="{{ route('admin.laporan.peta', ['periode' => $periode]) }}" class="text-sm text-black/40 underline underline-offset-2">Reset filter</a>
        @endif
    </form>

    {{-- KETERANGAN PERIODE --}}
    <div class="flex flex-wrap items-center gap-2 text-sm">
        <span class="px-3 py-1.5 rounded-full font-medium" style="background: var(--sawah-light); color: var(--sawah-dark);">
            Menampilkan: {{ $labelPeriode }}
        </span>
        @if($isPeriodeBerjalan && $tunggakan > 0)
            <span class="px-3 py-1.5 rounded-full" style="background: #FEF6E7; color: #92600E;">
                + {{ $tunggakan }} laporan bulan sebelumnya yang belum tuntas
            </span>
        @endif
    </div>

    {{-- RINGKASAN PER STATUS (sekaligus legenda warna) --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @php
            $legenda = [
                'menunggu' => ['Menunggu Verifikasi', '#C98A16'],
                'diproses' => ['Diproses', '#2563A8'],
                'selesai'  => ['Selesai', '#157F4F'],
                'ditolak'  => ['Ditolak', '#C0392B'],
            ];
        @endphp
        @foreach ($legenda as $key => [$label, $warna])
            <div class="bg-white rounded-2xl border border-black/5 p-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full shrink-0" style="background: {{ $warna }};"></span>
                    <p class="text-xs text-black/50">{{ $label }}</p>
                </div>
                <p class="font-display text-2xl font-semibold mt-1">{{ $jumlahPerStatus[$key] }}</p>
            </div>
        @endforeach
    </div>

    {{-- PETA --}}
    <div class="bg-white rounded-2xl border border-black/5 p-4">
        <div id="peta-sebaran" class="w-full rounded-xl" style="height: 520px; z-index: 0;"></div>

        @if($markers->isEmpty())
            <p class="text-sm text-black/40 text-center py-6">
                Tidak ada laporan dengan titik lokasi pada periode dan filter ini.
            </p>
        @endif

        <div class="mt-3 space-y-1 text-xs text-black/40">
            <p>Marker bergaris putus-putus = laporan dari bulan sebelumnya yang belum tuntas.</p>
            @if($tanpaLokasi > 0)
                <p>{{ $tanpaLokasi }} laporan tidak memiliki titik lokasi sehingga tidak muncul di peta.</p>
            @endif
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
    const dataLaporan = @json($markers);

    const warnaStatus = {
        menunggu: '#C98A16',
        diproses: '#2563A8',
        selesai:  '#157F4F',
        ditolak:  '#C0392B',
    };

    const labelStatus = {
        menunggu: 'Menunggu Verifikasi',
        diproses: 'Diproses',
        selesai:  'Selesai',
        ditolak:  'Ditolak',
    };

    // titik tengah default — sesuaikan dengan koordinat Desa Cibiuk kalau perlu
    // titik tengah desa diatur lewat Dashboard > Profil Desa
    const pusatDesa = [{{ $titikPeta['lat'] }}, {{ $titikPeta['lng'] }}];
    const zoomDesa = {{ $titikPeta['zoom'] }};

    const map = L.map('peta-sebaran').setView(pusatDesa, zoomDesa);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // marker yang berdekatan otomatis mengelompok, memisah saat di-zoom

    // ---- Lapisan wilayah dari data peta desa ----
    @foreach ($layerPeta as $lp)
        fetch(@json($lp['url']))
            .then(r => r.json())
            .then(data => L.geoJSON(data, {
                style: {
                    color: @json($lp['warna']),
                    weight: 2,
                    fillColor: @json($lp['warna']),
                    fillOpacity: {{ $lp['opasitas'] }},
                },
                interactive: false,   // agar tidak menghalangi penandaan lokasi
            }).addTo(map))
            .catch(() => console.warn('Lapisan peta gagal dimuat:', @json($lp['nama'])));
    @endforeach

    const clusterGroup = L.markerClusterGroup({
        maxClusterRadius: 45,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        iconCreateFunction: function (cluster) {
            const jumlah = cluster.getChildCount();
            const ukuran = jumlah < 10 ? 36 : (jumlah < 50 ? 44 : 52);

            return L.divIcon({
                html: `<div class="cluster-cibiuk" style="width:${ukuran}px;height:${ukuran}px;">${jumlah}</div>`,
                className: '',
                iconSize: L.point(ukuran, ukuran),
            });
        },
    });

    dataLaporan.forEach((l) => {
        const warna = warnaStatus[l.status] || '#666';

        const marker = L.circleMarker([l.lat, l.lng], {
            radius: 9,
            color: l.luar_periode ? warna : '#ffffff',
            weight: l.luar_periode ? 2 : 2,
            dashArray: l.luar_periode ? '3,3' : null,
            fillColor: warna,
            fillOpacity: l.luar_periode ? 0.55 : 0.9,
        });

        const dusunText = l.dusun ? ' &middot; Dusun ' + l.dusun : '';
        const catatanBulan = l.luar_periode
            ? `<div style="font-size:11px;color:#92600E;margin-top:4px;">Laporan dari ${l.bulan_masuk}, belum tuntas</div>`
            : '';

        marker.bindPopup(`
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 190px;">
                <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; color: #888;">${l.no_tiket}</div>
                <div style="font-weight: 600; margin: 3px 0 4px;">${l.judul}</div>
                <div style="font-size: 12px; color: #666;">${l.kategori}${dusunText}</div>
                <div style="font-size: 12px; color: #666; margin-bottom: 6px;">Kejadian: ${l.tanggal}</div>
                <span style="display:inline-block; font-size:11px; padding:2px 8px; border-radius:999px; background:${warna}; color:#fff;">
                    ${labelStatus[l.status] || l.status}
                </span>
                ${catatanBulan}
                <div style="margin-top: 8px;">
                    <a href="${l.url}" style="font-size: 12px; color: #2563A8; text-decoration: underline;">Lihat detail laporan →</a>
                </div>
            </div>
        `);

        clusterGroup.addLayer(marker);
    });

    map.addLayer(clusterGroup);

    // auto zoom supaya semua marker terlihat
    if (dataLaporan.length > 0) {
        map.fitBounds(clusterGroup.getBounds(), { padding: [40, 40], maxZoom: 17 });
    }
</script>
@endsection