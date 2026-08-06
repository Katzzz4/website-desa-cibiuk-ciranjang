@extends('layouts.admin')

@section('title', 'Tambah Kategori Infografis')

@section('content')
<form method="POST" action="{{ route('admin.infografis.kategori.store') }}" class="max-w-lg space-y-5">
    @csrf

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama Kategori</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="100"
                   list="contoh-kategori" placeholder="Contoh: Kelompok Umur"
                   class="w-full rounded-lg border-black/10 text-sm">
            <datalist id="contoh-kategori">
                <option value="Kelompok Umur">
                <option value="Status Perkawinan">
                <option value="Golongan Darah">
                <option value="Wajib Pilih">
                <option value="Kewarganegaraan">
                <option value="Penyandang Disabilitas">
            </datalist>
            <p class="text-xs text-black/40 mt-1">
                Nama ini akan tampil sebagai judul grafik, misalnya &ldquo;Berdasarkan Kelompok Umur&rdquo;.
            </p>
            @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Urutan Tampil</label>
            <input type="number" name="urutan" value="{{ old('urutan') }}" min="0"
                   placeholder="Kosongkan untuk ditaruh paling akhir"
                   class="w-full rounded-lg border-black/10 text-sm">
            <p class="text-xs text-black/40 mt-1">Angka lebih kecil tampil lebih dulu di halaman infografis.</p>
        </div>
    </div>

    <div class="rounded-2xl p-5 text-sm" style="background: var(--sawah-light); color: var(--sawah-dark);">
        Setelah kategori tersimpan, isi angkanya melalui menu <strong>Isi Data per Kategori</strong>.
        Grafik baru akan muncul di halaman publik begitu ada minimal satu baris data.
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan />
        <x-tombol.batal :href="route('admin.infografis.kategori.index')" />
    </div>
</form>
@endsection