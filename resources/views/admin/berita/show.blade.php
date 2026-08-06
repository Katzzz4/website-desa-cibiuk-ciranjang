@extends('layouts.publik')

@section('title', $berita->judul)

@section('content')
<div class="space-y-8">

    <a href="{{ route('berita.index') }}" class="text-sm inline-flex items-center gap-1" style="color: var(--talang);">
        ← Kembali ke Berita &amp; Pengumuman
    </a>

    <div>
        <span class="text-[11px] px-2 py-0.5 rounded-full font-medium"
              style="background: {{ $berita->kategori === 'pengumuman' ? '#FCEFD4' : '#DCEAF0' }}; color: {{ $berita->kategori === 'pengumuman' ? '#8A6416' : '#1D5A72' }};">
            {{ ucfirst($berita->kategori) }}
        </span>
        <h1 class="font-display text-2xl sm:text-3xl font-semibold mt-3 leading-tight">{{ $berita->judul }}</h1>
        <p class="text-xs text-black/40 mt-2">
            {{ $berita->tanggal_publish->translatedFormat('d M Y') }}
            @if($berita->penulis) · Diterbitkan oleh {{ $berita->penulis->name }} @endif
        </p>
    </div>

    @if($berita->thumbnail_path)
        <img src="{{ Storage::url($berita->thumbnail_path) }}" class="rounded-2xl w-full aspect-video object-cover">
    @endif

    <div class="bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
        <div class="prose prose-sm max-w-none text-black/75 leading-relaxed">
            {!! nl2br(e($berita->konten)) !!}
        </div>
    </div>

    @if($lainnya->count())
        <div>
            <h2 class="font-display text-lg font-semibold mb-4">Berita Lainnya</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach ($lainnya as $l)
                    <a href="{{ route('berita.show', $l->slug) }}" class="bg-white rounded-xl border border-black/5 p-4 hover:shadow-md transition">
                        <p class="text-xs text-black/40 mb-1">{{ $l->tanggal_publish->translatedFormat('d M Y') }}</p>
                        <p class="text-sm font-medium leading-snug line-clamp-2">{{ $l->judul }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
