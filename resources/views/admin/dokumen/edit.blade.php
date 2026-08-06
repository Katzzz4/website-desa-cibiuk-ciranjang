@extends('layouts.admin')

@section('title', 'Edit Dokumen')

@section('content')
@php $nilaiKlasifikasi = $dokumen->klasifikasi; $nilaiKategori = $dokumen->kategori; @endphp
<form method="POST" action="{{ route('admin.dokumen.update', $dokumen) }}" enctype="multipart/form-data" class="max-w-lg space-y-5">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama Dokumen</label>
            <input type="text" name="nama" value="{{ old('nama', $dokumen->nama) }}" required maxlength="200"
                   class="w-full rounded-lg border-black/10 text-sm">
            @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Klasifikasi</label>
            <select name="klasifikasi" id="pilih-klasifikasi" required class="w-full rounded-lg border-black/10 text-sm">
                @foreach (\App\Models\Dokumen::KLASIFIKASI as $kunci => $k)
                    <option value="{{ $kunci }}" @selected(old('klasifikasi', $nilaiKlasifikasi) === $kunci)>{{ $k['label'] }}</option>
                @endforeach
            </select>
            <p class="text-xs text-black/40 mt-1">Menentukan di bagian mana dokumen ini muncul pada halaman publik.</p>
            @error('klasifikasi') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Jenis Dokumen</label>
            <select name="kategori" id="pilih-jenis" required class="w-full rounded-lg border-black/10 text-sm"></select>
            @error('kategori') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>


        <div>
            <label class="text-xs text-black/50 mb-1 block">Berkas Saat Ini</label>
            <div class="flex items-center gap-3 mb-3 p-3 rounded-lg bg-black/[0.02] text-sm">
                <span class="uppercase font-semibold text-xs px-2 py-1 rounded"
                      style="background: var(--sawah-light); color: var(--sawah-dark);">{{ $dokumen->ekstensi }}</span>
                <span class="text-black/60">{{ $dokumen->ukuran_terbaca ?? 'Berkas tidak ditemukan' }}</span>
                <a href="{{ route('dokumen.unduh', $dokumen) }}" class="ml-auto text-xs underline underline-offset-2" style="color: var(--talang);">Unduh</a>
            </div>

            <label class="text-xs text-black/50 mb-1 block">Ganti Berkas (opsional)</label>
            <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-sm">
            <p class="text-xs text-black/40 mt-1">Kosongkan jika tidak ingin mengganti berkas. Berkas lama akan dihapus jika diganti.</p>
            @error('file') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.dokumen.index')" />
    </div>
</form>

<script>
    // Pilihan jenis dokumen menyesuaikan klasifikasi yang dipilih,
    // agar tidak ada gabungan yang tidak masuk akal.
    (function () {
        const daftarJenis = @json(collect(\App\Models\Dokumen::KLASIFIKASI)->map->jenis);
        const pilihKlasifikasi = document.getElementById('pilih-klasifikasi');
        const pilihJenis = document.getElementById('pilih-jenis');
        const jenisTerpilih = @json(old('kategori', $nilaiKategori));

        function perbarui(pertamaKali) {
            const jenis = daftarJenis[pilihKlasifikasi.value] || {};
            pilihJenis.innerHTML = '';
            Object.entries(jenis).forEach(([nilai, label]) => {
                const opsi = document.createElement('option');
                opsi.value = nilai;
                opsi.textContent = label;
                if (pertamaKali && nilai === jenisTerpilih) opsi.selected = true;
                pilihJenis.appendChild(opsi);
            });
        }

        pilihKlasifikasi.addEventListener('change', () => perbarui(false));
        perbarui(true);
    })();
</script>
@endsection