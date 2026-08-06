@extends('layouts.admin')

@section('title', 'Tulis Berita Baru')

@section('content')
<form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-5">
    @csrf

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Judul</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="200" class="w-full rounded-lg border-black/10 text-sm">
            @error('judul') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Kategori</label>
                <select name="kategori" class="w-full rounded-lg border-black/10 text-sm">
                    <option value="berita" @selected(old('kategori') == 'berita')>Berita</option>
                    <option value="pengumuman" @selected(old('kategori') == 'pengumuman')>Pengumuman</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Status</label>
                <select name="status_publish" id="status-publish" class="w-full rounded-lg border-black/10 text-sm">
                    <option value="draft" @selected(old('status_publish') == 'draft')>Simpan sebagai Draft</option>
                    <option value="publish" @selected(old('status_publish', 'publish') == 'publish')>Publish Sekarang</option>
                </select>
            </div>
        </div>

        <div id="tanggal-wrap">
            <label class="text-xs text-black/50 mb-1 block">Tanggal Publish (opsional, default sekarang)</label>
            <input type="datetime-local" name="tanggal_publish" value="{{ old('tanggal_publish') }}" class="rounded-lg border-black/10 text-sm">
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm">
            @error('thumbnail') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Konten</label>
            <textarea name="konten" rows="10" required class="w-full rounded-lg border-black/10 text-sm">{{ old('konten') }}</textarea>
            @error('konten') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan />
        <x-tombol.batal :href="route('admin.berita.index')" />
    </div>
</form>
@endsection