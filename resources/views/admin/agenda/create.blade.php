@extends('layouts.admin')

@section('title', 'Tambah Agenda')

@section('content')
<form method="POST" action="{{ route('admin.agenda.store') }}" class="max-w-lg space-y-5">
    @csrf
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Judul Kegiatan</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="150" class="w-full rounded-lg border-black/10 text-sm">
            @error('judul') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-black/10 text-sm">{{ old('deskripsi') }}</textarea>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Tanggal &amp; Waktu Mulai</label>
                <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full rounded-lg border-black/10 text-sm">
                @error('tanggal_mulai') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Tanggal &amp; Waktu Selesai (opsional)</label>
                <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full rounded-lg border-black/10 text-sm">
                @error('tanggal_selesai') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}" maxlength="200" class="w-full rounded-lg border-black/10 text-sm">
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan />
        <x-tombol.batal :href="route('admin.agenda.index')" />
    </div>
</form>
@endsection