@extends('layouts.admin')

@section('title', 'Tambah Potensi Desa')

@section('content')
<form method="POST" action="{{ route('admin.potensi.store') }}" enctype="multipart/form-data" class="max-w-lg space-y-5">
    @csrf
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Jenis Potensi</label>
            <select name="jenis" required class="w-full rounded-lg border-black/10 text-sm">
                @foreach (\App\Models\PotensiDesa::JENIS as $val => $label)
                    <option value="{{ $val }}" @selected(old('jenis') == $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('jenis') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="150"
                   placeholder="Contoh: Keripik Singkong Bu Imas"
                   class="w-full rounded-lg border-black/10 text-sm">
            @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Deskripsi</label>
            <textarea name="deskripsi" rows="5" maxlength="2000"
                      placeholder="Jelaskan produk/potensi ini, lokasi, keunggulannya..."
                      class="w-full rounded-lg border-black/10 text-sm">{{ old('deskripsi') }}</textarea>
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Nomor HP / WhatsApp (opsional)</label>
            <input type="text" name="kontak" value="{{ old('kontak') }}" maxlength="30"
                   placeholder="Contoh: 081234567890"
                   class="w-full rounded-lg border-black/10 text-sm">
            <p class="text-xs text-black/40 mt-1">Jika diisi, akan muncul tombol "Hubungi via WhatsApp" di halaman publik.</p>
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Foto (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-black/40 mt-1">Maksimal 4 MB.</p>
            @error('foto') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan />
        <x-tombol.batal :href="route('admin.potensi.index')" />
    </div>
</form>
@endsection