@extends('layouts.admin')

@section('title', 'Unggah Dokumen')

@section('content')
@php $nilaiKlasifikasi = 'produk_hukum'; $nilaiKategori = 'perdes'; @endphp
<form method="POST" action="{{ route('admin.dokumen.store') }}" enctype="multipart/form-data" class="max-w-lg space-y-5">
    @csrf
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama Dokumen</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="200"
                   placeholder="Contoh: Perdes No. 3 Tahun 2025 tentang APBDes"
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
            <label class="text-xs text-black/50 mb-1 block">Berkas</label>
            <input type="file" name="file" required accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-sm">
            <p class="text-xs text-black/40 mt-1">Format: PDF, Word, atau Excel. Maksimal 10 MB.</p>
            @error('file') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Unggah" />
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