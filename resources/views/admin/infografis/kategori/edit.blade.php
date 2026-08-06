@extends('layouts.admin')

@section('title', 'Edit Kategori Infografis')

@section('content')
<form method="POST" action="{{ route('admin.infografis.kategori.update', $kategori) }}" class="max-w-lg space-y-5">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama Kategori</label>
            <input type="text" name="nama" value="{{ old('nama', $kategori->nama) }}" required maxlength="100"
                   class="w-full rounded-lg border-black/10 text-sm">
            @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Urutan Tampil</label>
            <input type="number" name="urutan" value="{{ old('urutan', $kategori->urutan) }}" min="0"
                   class="w-full rounded-lg border-black/10 text-sm">
        </div>

        <div class="pt-2 border-t border-black/5">
            <p class="text-xs text-black/40">
                Berisi <strong>{{ $kategori->data()->count() }}</strong> baris data.
                <a href="{{ route('admin.infografis.data.index', ['kategori_id' => $kategori->id]) }}"
                   class="underline underline-offset-2" style="color: var(--talang);">Kelola datanya</a>
            </p>
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.infografis.kategori.index')" />
    </div>
</form>
@endsection