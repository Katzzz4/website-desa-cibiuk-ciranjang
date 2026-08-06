@extends('layouts.admin')

@section('title', 'Tambah Item Galeri')

@section('content')
<form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="max-w-lg space-y-5">
    @csrf
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Judul</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full rounded-lg border-black/10 text-sm">
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Tipe</label>
            <select name="tipe" id="tipe-select" class="w-full rounded-lg border-black/10 text-sm">
                <option value="foto" @selected(old('tipe', 'foto') == 'foto')>Foto</option>
                <option value="video" @selected(old('tipe') == 'video')>Video (embed YouTube)</option>
            </select>
        </div>

        <div id="wrap-foto">
            <label class="text-xs text-black/50 mb-1 block">Upload Foto</label>
            <input type="file" name="file" accept="image/*" class="w-full text-sm">
            @error('file') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div id="wrap-video" class="hidden">
            <label class="text-xs text-black/50 mb-1 block">Link YouTube</label>
            <input type="url" name="url_video" value="{{ old('url_video') }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full rounded-lg border-black/10 text-sm">
            @error('url_video') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan />
        <x-tombol.batal :href="route('admin.galeri.index')" />
    </div>
</form>

<script>
    const tipeSelect = document.getElementById('tipe-select');
    const wrapFoto = document.getElementById('wrap-foto');
    const wrapVideo = document.getElementById('wrap-video');
    function toggleWrap() {
        wrapFoto.classList.toggle('hidden', tipeSelect.value !== 'foto');
        wrapVideo.classList.toggle('hidden', tipeSelect.value !== 'video');
    }
    tipeSelect.addEventListener('change', toggleWrap);
    toggleWrap();
</script>
@endsection