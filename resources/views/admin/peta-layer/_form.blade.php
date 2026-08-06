@php $l = $layer ?? null; @endphp

<div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
    <div>
        <label class="text-xs text-black/50 mb-1 block">Nama Lapisan</label>
        <input type="text" name="nama" value="{{ old('nama', $l->nama ?? '') }}" required maxlength="100"
               list="contoh-lapisan" placeholder="Contoh: Batas Desa Cibiuk"
               class="w-full rounded-lg border-black/10 text-sm">
        <datalist id="contoh-lapisan">
            <option value="Batas Desa">
            <option value="Batas Dusun">
            <option value="Lahan Sawah">
            <option value="Permukiman">
            <option value="Jalan Desa">
            <option value="Sungai dan Saluran Air">
        </datalist>
        @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-xs text-black/50 mb-1 block">Keterangan (opsional)</label>
        <input type="text" name="keterangan" value="{{ old('keterangan', $l->keterangan ?? '') }}" maxlength="200"
               placeholder="Muncul sebagai penjelasan singkat pada keterangan peta"
               class="w-full rounded-lg border-black/10 text-sm">
    </div>

    <div>
        <label class="text-xs text-black/50 mb-1 block">Berkas GeoJSON</label>
        @if($l && $l->file_path)
            <p class="text-xs text-black/50 mb-2">
                Berkas saat ini:
                <strong>{{ $l->ukuran_terbaca ?? 'tidak ditemukan' }}</strong>.
                Kosongkan bila tidak ingin mengganti.
            </p>
        @endif
        <input type="file" name="berkas" accept=".geojson,.json,application/geo+json,application/json"
               @if(!$l) required @endif class="w-full text-sm">
        <p class="text-xs text-black/40 mt-1">
            Maksimal 2 MB. Berkas yang lebih besar akan membuat peta lambat dibuka lewat data seluler —
            sederhanakan dulu di mapshaper.org.
        </p>
        @error('berkas') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
    <h2 class="font-display text-base font-semibold">Tampilan di Peta</h2>

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Warna</label>
            <div class="flex gap-2">
                <input type="color" name="warna" value="{{ old('warna', $l->warna ?? '#0E5C3A') }}"
                       class="w-12 h-10 rounded-lg border border-black/10 p-1 cursor-pointer">
                <input type="text" value="{{ old('warna', $l->warna ?? '#0E5C3A') }}" readonly
                       class="flex-1 rounded-lg border-black/10 text-sm font-mono-tiket bg-black/[0.02]"
                       id="warna-teks">
            </div>
            @error('warna') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">
                Kepekatan Isian: <span id="nilai-opasitas">{{ old('opasitas', $l->opasitas ?? 25) }}</span>%
            </label>
            <input type="range" name="opasitas" min="0" max="100" step="5"
                   value="{{ old('opasitas', $l->opasitas ?? 25) }}"
                   class="w-full" id="geser-opasitas">
            <p class="text-xs text-black/40 mt-1">
                0% berarti hanya garis tepinya yang tampak. Cocok untuk batas wilayah.
            </p>
        </div>
    </div>

    <div>
        <label class="text-xs text-black/50 mb-1 block">Urutan Tampil</label>
        <input type="number" name="urutan" value="{{ old('urutan', $l->urutan ?? '') }}" min="0"
               placeholder="Kosongkan untuk ditaruh paling akhir"
               class="w-full sm:w-48 rounded-lg border-black/10 text-sm">
        <p class="text-xs text-black/40 mt-1">
            Lapisan dengan angka lebih kecil digambar lebih dulu, sehingga berada di bawah lapisan lain.
        </p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-black/5 p-6 space-y-3">
    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="aktif" value="1" class="mt-1 rounded border-black/20"
               @checked(old('aktif', $l->aktif ?? true))>
        <span>
            <span class="text-sm font-medium block">Tampilkan lapisan ini</span>
            <span class="text-xs text-black/50">Nonaktifkan untuk menyembunyikan sementara tanpa menghapus berkasnya.</span>
        </span>
    </label>

    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="tampil_di_pengaduan" value="1" class="mt-1 rounded border-black/20"
               @checked(old('tampil_di_pengaduan', $l->tampil_di_pengaduan ?? false))>
        <span>
            <span class="text-sm font-medium block">Tampilkan juga di peta pengaduan</span>
            <span class="text-xs text-black/50">
                Berguna untuk batas wilayah, agar warga tahu area mana yang termasuk Desa Cibiuk
                saat menandai lokasi laporan. Tidak disarankan untuk lapisan yang rumit seperti penggunaan lahan.
            </span>
        </span>
    </label>
</div>

<script>
    (function () {
        const warna = document.querySelector('input[name="warna"]');
        const warnaTeks = document.getElementById('warna-teks');
        warna.addEventListener('input', () => warnaTeks.value = warna.value.toUpperCase());

        const geser = document.getElementById('geser-opasitas');
        const nilai = document.getElementById('nilai-opasitas');
        geser.addEventListener('input', () => nilai.textContent = geser.value);
    })();
</script>