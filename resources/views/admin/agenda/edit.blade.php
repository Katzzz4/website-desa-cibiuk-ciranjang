@extends('layouts.admin')

@section('title', 'Edit Agenda')

@section('content')
<form method="POST" action="{{ route('admin.agenda.update', $agenda) }}" class="max-w-lg space-y-5">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Judul Kegiatan</label>
            <input type="text" name="judul" value="{{ old('judul', $agenda->judul) }}" required maxlength="150" class="w-full rounded-lg border-black/10 text-sm">
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-black/10 text-sm">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Tanggal &amp; Waktu Mulai</label>
                <input type="datetime-local" name="tanggal_mulai"
                       value="{{ old('tanggal_mulai', $agenda->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                       required class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Tanggal &amp; Waktu Selesai</label>
                <input type="datetime-local" name="tanggal_selesai"
                       value="{{ old('tanggal_selesai', $agenda->tanggal_selesai?->format('Y-m-d\TH:i')) }}"
                       class="w-full rounded-lg border-black/10 text-sm">
            </div>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi', $agenda->lokasi) }}" maxlength="200" class="w-full rounded-lg border-black/10 text-sm">
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.agenda.index')" />
    </div>
</form>
@endsection