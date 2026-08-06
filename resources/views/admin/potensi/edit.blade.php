@extends('layouts.admin')

@section('title', 'Edit Potensi Desa')

@section('content')
<form method="POST" action="{{ route('admin.potensi.update', $potensi) }}" enctype="multipart/form-data" class="max-w-lg space-y-5">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Jenis Potensi</label>
            <select name="jenis" required class="w-full rounded-lg border-black/10 text-sm">
                @foreach (\App\Models\PotensiDesa::JENIS as $val => $label)
                    <option value="{{ $val }}" @selected(old('jenis', $potensi->jenis) == $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $potensi->nama) }}" required maxlength="150"
                   class="w-full rounded-lg border-black/10 text-sm">
            @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Deskripsi</label>
            <textarea name="deskripsi" rows="5" maxlength="2000"
                      class="w-full rounded-lg border-black/10 text-sm">{{ old('deskripsi', $potensi->deskripsi) }}</textarea>
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Nomor HP / WhatsApp</label>
            <input type="text" name="kontak" value="{{ old('kontak', $potensi->kontak) }}" maxlength="30"
                   class="w-full rounded-lg border-black/10 text-sm">
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Foto</label>
            @if($potensi->foto_path)
                <img src="{{ Storage::url($potensi->foto_path) }}" class="w-40 h-28 object-cover rounded-lg mb-2">
            @endif
            <input type="file" name="foto" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-black/40 mt-1">Kosongkan jika tidak ingin mengganti foto. Foto lama akan dihapus jika diganti.</p>
            @error('foto') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.potensi.index')" />
    </div>
</form>
@endsection