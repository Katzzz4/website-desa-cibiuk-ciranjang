@extends('layouts.publik')

@section('title', $berita->judul)

{{-- Pratinjau saat tautan berita ini dibagikan ke WhatsApp --}}
@section('meta_tipe', 'article')
@section('meta_judul', $berita->judul)
@section('meta_deskripsi', \Illuminate\Support\Str::limit(strip_tags($berita->konten), 180))
@section('meta_gambar', $berita->thumbnail_path ? url(Storage::url($berita->thumbnail_path)) : '')

@section('content')
<div class="space-y-8">

    <a href="{{ route('berita.index') }}"
       class="tombol-garis group inline-flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg text-sm font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="2" stroke="currentColor"
             class="w-4 h-4 shrink-0 transition-transform group-hover:-translate-x-0.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Daftar Berita
    </a>

    <div>
        <span class="text-[11px] px-2 py-0.5 rounded-full font-medium"
              style="background: {{ $berita->kategori === 'pengumuman' ? '#FEF6E7' : '#E8F0FB' }}; color: {{ $berita->kategori === 'pengumuman' ? '#92600E' : '#1B4B8F' }};">
            {{ ucfirst($berita->kategori) }}
        </span>
        <h1 class="font-display text-2xl sm:text-3xl font-semibold mt-3 leading-tight">{{ $berita->judul }}</h1>
        <p class="text-xs text-black/40 mt-2">
            {{ $berita->tanggal_publish->translatedFormat('d M Y') }}
            @if($berita->penulis) · Diterbitkan oleh {{ $berita->penulis->name }} @endif
        </p>
    </div>

    @if($berita->thumbnail_path)
        <img src="{{ Storage::url($berita->thumbnail_path) }}" class="reveal-skala rounded-2xl w-full aspect-video object-cover">
    @endif

    <div class="reveal bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
        <div class="prose prose-sm teks-baca text-black/75 leading-relaxed">
            {!! nl2br(e($berita->konten)) !!}
        </div>
    </div>

    @if($lainnya->count())
        <div>
            <h2 class="font-display text-lg font-semibold mb-4">Berita Lainnya</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach ($lainnya as $l)
                    <a href="{{ route('berita.show', $l->slug) }}" class="reveal bg-white rounded-xl border border-black/5 p-4 hover:shadow-md transition" style="transition-delay: {{ $loop->index * 70 }}ms;">
                        <p class="text-xs text-black/40 mb-1">{{ $l->tanggal_publish->translatedFormat('d M Y') }}</p>
                        <p class="text-sm font-medium leading-snug line-clamp-2">{{ $l->judul }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection