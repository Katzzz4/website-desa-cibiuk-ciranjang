@extends('layouts.admin')

@section('title', 'Profil Desa')

@section('content')
<form method="POST" action="{{ route('admin.profil.update') }}" enctype="multipart/form-data" class="max-w-2xl space-y-5">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <h2 class="font-display text-base font-semibold mb-1">Data Umum</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Nama Desa</label>
                <input type="text" name="nama_desa" value="{{ old('nama_desa', $profil->nama_desa ?? '') }}" required class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Nama Kepala Desa</label>
                <input type="text" name="nama_kepala_desa" value="{{ old('nama_kepala_desa', $profil->nama_kepala_desa ?? '') }}" class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ old('kecamatan', $profil->kecamatan ?? '') }}" required class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Kabupaten</label>
                <input type="text" name="kabupaten" value="{{ old('kabupaten', $profil->kabupaten ?? '') }}" required class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $profil->provinsi ?? '') }}" required class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Luas Wilayah (Ha)</label>
                <input type="number" step="0.001" name="luas_wilayah_ha" value="{{ old('luas_wilayah_ha', $profil->luas_wilayah_ha ?? '') }}" class="w-full rounded-lg border-black/10 text-sm">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <h2 class="font-display text-base font-semibold mb-1">Sejarah, Visi & Misi</h2>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Sejarah Singkat</label>
            <textarea name="sejarah" rows="5" class="w-full rounded-lg border-black/10 text-sm">{{ old('sejarah', $profil->sejarah ?? '') }}</textarea>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Visi</label>
            <textarea name="visi" rows="2" class="w-full rounded-lg border-black/10 text-sm">{{ old('visi', $profil->visi ?? '') }}</textarea>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Misi (satu poin per baris)</label>
            <textarea name="misi" rows="5" class="w-full rounded-lg border-black/10 text-sm">{{ old('misi', $profil && $profil->misi ? implode("\n", $profil->misi) : '') }}</textarea>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <h2 class="font-display text-base font-semibold">Titik Tengah Desa</h2>
            <p class="text-xs text-black/50 mt-1">
                Menentukan posisi awal peta pada form pengaduan warga dan peta sebaran laporan.
                Klik pada peta atau geser penanda untuk memindahkan titiknya.
            </p>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <div id="peta-titik-desa" class="w-full h-72 rounded-xl border border-black/5" style="z-index: 0;"></div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Latitude</label>
                <input type="text" name="latitude" id="input-lat"
                       value="{{ old('latitude', $profil->latitude ?? '') }}"
                       class="w-full rounded-lg border-black/10 text-sm font-mono-tiket">
                @error('latitude') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Longitude</label>
                <input type="text" name="longitude" id="input-lng"
                       value="{{ old('longitude', $profil->longitude ?? '') }}"
                       class="w-full rounded-lg border-black/10 text-sm font-mono-tiket">
                @error('longitude') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Tingkat Perbesaran</label>
                <select name="zoom_peta" id="input-zoom" class="w-full rounded-lg border-black/10 text-sm">
                    @for ($z = 12; $z <= 18; $z++)
                        <option value="{{ $z }}" @selected(old('zoom_peta', $profil->zoom_peta ?? 15) == $z)>
                            {{ $z }}{{ $z === 15 ? ' (disarankan)' : '' }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <p class="text-xs text-black/40">
            Belum tahu titiknya? Buka
            <a href="https://www.openstreetmap.org" target="_blank" rel="noopener"
               class="underline underline-offset-2" style="color: var(--talang);">openstreetmap.org</a>,
            cari lokasi kantor desa, lalu geser penanda di peta ini ke posisi yang sama.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <h2 class="font-display text-base font-semibold mb-1">Geografis</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="text-xs text-black/50 mb-1 block">Batas Utara</label>
                <input type="text" name="batas_utara" value="{{ old('batas_utara', $profil->batas_utara ?? '') }}" class="w-full rounded-lg border-black/10 text-sm"></div>
            <div><label class="text-xs text-black/50 mb-1 block">Batas Selatan</label>
                <input type="text" name="batas_selatan" value="{{ old('batas_selatan', $profil->batas_selatan ?? '') }}" class="w-full rounded-lg border-black/10 text-sm"></div>
            <div><label class="text-xs text-black/50 mb-1 block">Batas Timur</label>
                <input type="text" name="batas_timur" value="{{ old('batas_timur', $profil->batas_timur ?? '') }}" class="w-full rounded-lg border-black/10 text-sm"></div>
            <div><label class="text-xs text-black/50 mb-1 block">Batas Barat</label>
                <input type="text" name="batas_barat" value="{{ old('batas_barat', $profil->batas_barat ?? '') }}" class="w-full rounded-lg border-black/10 text-sm"></div>
            <div><label class="text-xs text-black/50 mb-1 block">Jarak ke Kabupaten (km)</label>
                <input type="number" step="0.1" name="jarak_ke_kabupaten_km" value="{{ old('jarak_ke_kabupaten_km', $profil->jarak_ke_kabupaten_km ?? '') }}" class="w-full rounded-lg border-black/10 text-sm"></div>
            <div><label class="text-xs text-black/50 mb-1 block">Jarak ke Kecamatan (km)</label>
                <input type="number" step="0.1" name="jarak_ke_kecamatan_km" value="{{ old('jarak_ke_kecamatan_km', $profil->jarak_ke_kecamatan_km ?? '') }}" class="w-full rounded-lg border-black/10 text-sm"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <h2 class="font-display text-base font-semibold mb-1">Kontak &amp; Pelayanan</h2>
        <p class="text-xs text-black/40 -mt-2 mb-2">Data ini tampil di footer seluruh halaman publik.</p>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Alamat Kantor Desa</label>
            <input type="text" name="alamat_kantor" value="{{ old('alamat_kantor', $profil->alamat_kantor ?? '') }}"
                   placeholder="Contoh: Jl. Raya Ciranjang No. 12, Dusun Sukamaju"
                   class="w-full rounded-lg border-black/10 text-sm">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $profil->telepon ?? '') }}"
                       class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Email</label>
                <input type="email" name="email" value="{{ old('email', $profil->email ?? '') }}"
                       class="w-full rounded-lg border-black/10 text-sm">
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Jam Pelayanan (satu baris per hari)</label>
            <textarea name="jam_pelayanan" rows="3"
                      placeholder="Senin – Kamis: 08.00 – 15.00&#10;Jumat: 08.00 – 11.00&#10;Sabtu – Minggu: Tutup"
                      class="w-full rounded-lg border-black/10 text-sm">{{ old('jam_pelayanan', $profil->jam_pelayanan ?? '') }}</textarea>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <h2 class="font-display text-base font-semibold mb-1">Media</h2>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Foto Utama Beranda</label>
            @if($profil?->foto_hero_path)
                <img src="{{ Storage::url($profil->foto_hero_path) }}" class="w-full max-w-sm rounded-lg mb-2">
            @endif
            <input type="file" name="foto_hero" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-black/40 mt-1">Foto lanskap desa untuk latar bagian atas beranda. Sebaiknya melebar (landscape), maksimal 6 MB.</p>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Peta Sosial Desa</label>
            @if($profil?->peta_wilayah_path)
                <img src="{{ Storage::url($profil->peta_wilayah_path) }}" class="w-full max-w-sm rounded-lg mb-2">
            @endif
            <input type="file" name="peta_wilayah" accept="image/*" class="w-full text-sm">
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Logo Desa</label>
            @if($profil?->logo_path)
                <img src="{{ Storage::url($profil->logo_path) }}" class="w-20 h-20 object-contain rounded-lg mb-2">
            @endif
            <input type="file" name="logo" accept="image/*" class="w-full text-sm">
        </div>
    </div>

    <x-tombol.simpan label="Simpan Perubahan" />
</form>

{{-- ============ PEMILIH TITIK TENGAH DESA ============ --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function () {
        const inputLat  = document.getElementById('input-lat');
        const inputLng  = document.getElementById('input-lng');
        const inputZoom = document.getElementById('input-zoom');

        // pakai koordinat tersimpan; bila kosong pakai perkiraan wilayah Ciranjang
        const latAwal  = parseFloat(inputLat.value)  || {{ \App\Models\ProfilDesa::KOORDINAT_CADANGAN['lat'] }};
        const lngAwal  = parseFloat(inputLng.value)  || {{ \App\Models\ProfilDesa::KOORDINAT_CADANGAN['lng'] }};
        const zoomAwal = parseInt(inputZoom.value, 10) || {{ \App\Models\ProfilDesa::KOORDINAT_CADANGAN['zoom'] }};

        const map = L.map('peta-titik-desa').setView([latAwal, lngAwal], zoomAwal);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        const penanda = L.marker([latAwal, lngAwal], { draggable: true }).addTo(map);

        const simpan = (lat, lng) => {
            inputLat.value = lat.toFixed(7);
            inputLng.value = lng.toFixed(7);
        };

        simpan(latAwal, lngAwal);

        penanda.on('dragend', () => {
            const p = penanda.getLatLng();
            simpan(p.lat, p.lng);
        });

        map.on('click', (e) => {
            penanda.setLatLng(e.latlng);
            simpan(e.latlng.lat, e.latlng.lng);
        });

        // perbesaran peta ikut pilihan pada dropdown
        inputZoom.addEventListener('change', () => {
            map.setZoom(parseInt(inputZoom.value, 10));
        });

        // ketikan manual pada kolom koordinat langsung memindahkan penanda
        [inputLat, inputLng].forEach((el) => {
            el.addEventListener('change', () => {
                const lat = parseFloat(inputLat.value);
                const lng = parseFloat(inputLng.value);
                if (isNaN(lat) || isNaN(lng)) return;
                penanda.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            });
        });
    })();
</script>
@endsection