@extends('layouts.publik')

@section('title', 'Peta Wilayah Desa')
@section('main-class', '')

@section('meta_judul', 'Peta Wilayah Desa ' . ($profil->nama_desa ?? 'Cibiuk'))
@section('meta_deskripsi', 'Peta batas wilayah dan penggunaan lahan Desa ' . ($profil->nama_desa ?? 'Cibiuk') . '.')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

{{-- ============ HEADER ============ --}}
<section class="border-b" style="border-color: var(--garis); background: var(--kertas);">
    <div class="wadah py-12">
        <p class="label-bagian">Geografis</p>
        <h1 class="font-display text-2xl sm:text-3xl font-bold mt-1.5">Peta Wilayah Desa</h1>
        <p class="text-sm mt-3 max-w-2xl leading-relaxed" style="color: var(--lembut);">
            Batas wilayah dan penggunaan lahan Desa {{ $profil->nama_desa ?? 'Cibiuk' }}.
            Lapisan peta dapat dinyalakan atau dimatikan lewat tombol di sudut kanan atas peta.
        </p>
    </div>
</section>

<section class="wadah py-10">
    @if($layer->isEmpty())
        <div class="kartu p-10 text-center">
            <p class="font-display text-base font-bold mb-1">Peta wilayah belum tersedia</p>
            <p class="text-sm" style="color: var(--lembut);">
                Data peta sedang disiapkan oleh pemerintah desa.
            </p>
        </div>
    @else
        <div class="kartu overflow-hidden">
            <div id="peta-wilayah" style="height: 540px; z-index: 0;"></div>
        </div>

        {{-- Keterangan lapisan --}}
        <div class="kartu p-5 mt-4">
            <p class="label-bagian mb-3">Keterangan</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($layer as $l)
                    <div class="flex items-start gap-2.5 text-sm">
                        <span class="w-4 h-4 rounded shrink-0 mt-0.5 border border-black/10"
                              style="background: {{ $l['warna'] }};"></span>
                        <div>
                            <p class="font-medium">{{ $l['nama'] }}</p>
                            @if($l['keterangan'])
                                <p class="text-xs mt-0.5" style="color: var(--lembut);">{{ $l['keterangan'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Data pendukung dari profil desa --}}
    <div class="grid sm:grid-cols-2 gap-5 mt-8">
        <div class="kartu p-5">
            <p class="label-bagian mb-3">Batas Wilayah</p>
            <div class="space-y-2 text-sm">
                @foreach ([['Utara', $profil->batas_utara ?? null], ['Selatan', $profil->batas_selatan ?? null],
                           ['Timur', $profil->batas_timur ?? null], ['Barat', $profil->batas_barat ?? null]] as [$arah, $nilai])
                    <div class="flex justify-between gap-4 border-b last:border-0 pb-2 last:pb-0"
                         style="border-color: var(--garis);">
                        <span style="color: var(--lembut);">{{ $arah }}</span>
                        <span class="font-medium text-right">{{ $nilai ?? '-' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="kartu p-5">
            <p class="label-bagian mb-3">Dusun</p>
            <div class="space-y-2">
                @forelse ($dusun as $d)
                    <div class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-sm"
                         style="background: var(--sawah-light); color: var(--sawah-dark);">
                        <span class="font-medium">{{ $d->nama }}</span>
                        <span class="text-xs opacity-70">{{ $d->jarak_ke_desa_km }} km dari kantor desa</span>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--lembut);">Belum ada data dusun.</p>
                @endforelse
            </div>

            @if($profil?->luas_wilayah_ha)
                <p class="text-sm mt-4 pt-4 border-t" style="border-color: var(--garis); color: var(--lembut);">
                    Luas wilayah keseluruhan
                    <strong style="color: var(--ink);">{{ number_format($profil->luas_wilayah_ha, 3, ',', '.') }} Ha</strong>
                </p>
            @endif
        </div>
    </div>
</section>

@if($layer->isNotEmpty())
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const titik = [{{ $titikPeta['lat'] }}, {{ $titikPeta['lng'] }}];
    const peta = L.map('peta-wilayah').setView(titik, {{ $titikPeta['zoom'] }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(peta);

    const daftarLapisan = @json($layer);
    const kendali = {};
    const semuaBatas = [];
    let sisa = daftarLapisan.length;

    daftarLapisan.forEach((l) => {
        fetch(l.url)
            .then(r => r.json())
            .then(data => {
                const lapisan = L.geoJSON(data, {
                    style: {
                        color: l.warna,
                        weight: 2,
                        fillColor: l.warna,
                        fillOpacity: l.opasitas,
                    },
                    onEachFeature: (fitur, layer) => {
                        // tampilkan nama wilayah bila tersedia di data GeoJSON
                        const p = fitur.properties || {};
                        const label = p.nama || p.NAMA || p.name || p.NAME || p.Nama || null;
                        layer.bindPopup(
                            '<strong>' + l.nama + '</strong>' +
                            (label ? '<br>' + label : '')
                        );
                    },
                }).addTo(peta);

                kendali[l.nama] = lapisan;
                if (lapisan.getBounds().isValid()) semuaBatas.push(lapisan.getBounds());
            })
            .catch(() => {
                console.warn('Lapisan peta gagal dimuat:', l.nama);
            })
            .finally(() => {
                sisa--;
                if (sisa === 0) selesaiMemuat();
            });
    });

    function selesaiMemuat() {
        if (Object.keys(kendali).length > 1) {
            L.control.layers(null, kendali, { collapsed: false }).addTo(peta);
        }

        // arahkan tampilan agar seluruh wilayah terlihat
        if (semuaBatas.length) {
            let gabungan = semuaBatas[0];
            semuaBatas.slice(1).forEach(b => gabungan = gabungan.extend(b));
            peta.fitBounds(gabungan, { padding: [24, 24] });
        }
    }
</script>
@endif
@endsection