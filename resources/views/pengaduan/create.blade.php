@extends('layouts.publik')

@section('title', 'Buat Laporan Pengaduan')

@section('meta_judul', 'Layanan Pengaduan Masyarakat')
@section('meta_deskripsi', 'Laporkan jalan rusak, lampu jalan mati, sampah menumpuk, atau masalah lain di lingkungan Anda. Setiap laporan mendapat nomor tiket sehingga perkembangannya dapat dipantau sendiri.')

@section('content')
<div class="max-w-3xl mx-auto">
<div class="mb-8">
    <p class="text-xs font-medium tracking-wide uppercase" style="color: var(--talang);">Layanan Pengaduan Masyarakat</p>
    <h1 class="font-display text-3xl font-semibold mt-1">Sampaikan Laporan Anda</h1>
    <p class="text-sm text-black/50 mt-2">
        Laporkan kondisi jalan rusak, lampu mati, sampah, keamanan, atau masalah lain di lingkungan Anda.
        Setiap laporan akan diberi nomor tiket untuk dilacak perkembangannya.
    </p>
</div>

@if ($errors->any())
    <div class="mb-6 rounded-xl p-4 text-sm" style="background:#FBEAEA; color:#96261F;">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    {{-- ANONIM TOGGLE --}}
    <div class="reveal bg-white rounded-2xl border border-black/5 p-5">
        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="anonim" value="1" id="anonim-check" class="mt-1 rounded border-black/20">
            <span>
                <span class="text-sm font-medium block">Kirim sebagai laporan anonim</span>
                <span class="text-xs text-black/50">Identitas Anda tidak akan dicatat. Nomor tiket tetap diberikan untuk tracking.</span>
            </span>
        </label>

        <div id="identitas-wrap" class="grid sm:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Nama Pelapor</label>
                <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor') }}" class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full rounded-lg border-black/10 text-sm">
            </div>
        </div>
    </div>

    {{-- KATEGORI & DUSUN --}}
    <div class="reveal bg-white rounded-2xl border border-black/5 p-5 grid sm:grid-cols-2 gap-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Kategori Laporan</label>
            <select name="kategori_laporan_id" required class="w-full rounded-lg border-black/10 text-sm">
                <option value="">Pilih kategori...</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected(old('kategori_laporan_id') == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Dusun</label>
            <select name="dusun_id" class="w-full rounded-lg border-black/10 text-sm">
                <option value="">Tidak yakin / lainnya</option>
                @foreach ($dusun as $d)
                    <option value="{{ $d->id }}" @selected(old('dusun_id') == $d->id)>{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- JUDUL & DESKRIPSI --}}
    <div class="reveal bg-white rounded-2xl border border-black/5 p-5 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Judul Laporan</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="150"
                   placeholder="Contoh: Lampu jalan mati di depan Masjid Al-Ikhlas" class="w-full rounded-lg border-black/10 text-sm">
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Deskripsi</label>
            <textarea name="deskripsi" required rows="4" maxlength="2000"
                      placeholder="Jelaskan detail kejadian..." class="w-full rounded-lg border-black/10 text-sm">{{ old('deskripsi') }}</textarea>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Tanggal Kejadian</label>
            <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" required
                   max="{{ date('Y-m-d') }}" class="rounded-lg border-black/10 text-sm">
        </div>
    </div>

    {{-- LOKASI - LEAFLET + OPENSTREETMAP --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div class="reveal bg-white rounded-2xl border border-black/5 p-5">
        <label class="text-xs text-black/50 mb-2 block">Titik Lokasi Kejadian</label>
        <p class="text-xs text-black/40 mb-3">Klik pada peta atau geser marker untuk menandai lokasi kejadian.</p>

        <div id="peta-lokasi" class="w-full h-72 rounded-xl bg-black/5" style="z-index: 0;"></div>

        <input type="hidden" name="latitude" id="input-latitude" value="{{ old('latitude') }}">
        <input type="hidden" name="longitude" id="input-longitude" value="{{ old('longitude') }}">

        <div class="mt-3">
            <label class="text-xs text-black/50 mb-1 block">Alamat / Patokan Lokasi</label>
            <input type="text" name="alamat_lokasi" value="{{ old('alamat_lokasi') }}"
                   placeholder="Contoh: Depan RT 02/RW 05, Dusun Sukamaju" class="w-full rounded-lg border-black/10 text-sm">
        </div>
    </div>

    {{-- FOTO --}}
    <div class="reveal bg-white rounded-2xl border border-black/5 p-5">
        <label class="text-xs text-black/50 mb-1 block">Foto Pendukung (maks. 5)</label>
        <input type="file" name="foto[]" multiple accept="image/*" class="w-full text-sm">
    </div>

    <button type="submit" class="w-full py-3 rounded-xl text-white font-medium" style="background: var(--sawah-dark);">
        Kirim Laporan
    </button>
</form>

<script>
    document.getElementById('anonim-check').addEventListener('change', function () {
        document.getElementById('identitas-wrap').classList.toggle('opacity-40', this.checked);
        document.querySelectorAll('#identitas-wrap input').forEach(el => el.disabled = this.checked);
    });
</script>

{{-- ================================================================
     LEAFLET + OPENSTREETMAP
     Gratis, tanpa API key, tanpa billing.
     ================================================================ --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Perkiraan koordinat Ciranjang, Cianjur — sesuaikan dengan titik tengah Desa Cibiuk
    // (buka openstreetmap.org, cari lokasi desa, klik kanan > "Show address" untuk koordinat pastinya)
    // titik tengah desa diatur lewat Dashboard > Profil Desa
    const pusatDesa = { lat: {{ $titikPeta['lat'] }}, lng: {{ $titikPeta['lng'] }} };
    const zoomDesa = {{ $titikPeta['zoom'] }};

    const map = L.map('peta-lokasi').setView([pusatDesa.lat, pusatDesa.lng], zoomDesa);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);


    const marker = L.marker([pusatDesa.lat, pusatDesa.lng], { draggable: true }).addTo(map);

    function updateInputs(lat, lng) {
        document.getElementById('input-latitude').value = lat;
        document.getElementById('input-longitude').value = lng;
    }

    updateInputs(pusatDesa.lat, pusatDesa.lng);

    marker.on('dragend', () => {
        const pos = marker.getLatLng();
        updateInputs(pos.lat, pos.lng);
    });

    map.on('click', (e) => {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
    });
</script>
</div>
@endsection