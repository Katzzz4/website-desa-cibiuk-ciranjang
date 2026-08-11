@extends('layouts.publik')

@section('title', 'Berita & Pengumuman')
@section('main-class', '')

@section('content')
@php
    $items = $berita->getCollection();
    // kartu sorotan hanya di halaman pertama, supaya halaman 2 dst tetap rapi
    $sorotan = $berita->currentPage() === 1 ? $items->first() : null;
    $sisanya = $sorotan ? $items->slice(1) : $items;
@endphp

{{-- ============ HEADER ============ --}}
<section class="wadah pt-10 pb-8">
    <p class="text-xs font-medium tracking-[0.2em] uppercase" style="color: var(--talang);">Informasi Desa</p>
    <h1 class="font-display text-3xl sm:text-4xl font-semibold mt-1">Berita &amp; Pengumuman</h1>

    <div class="flex flex-wrap gap-2 text-sm mt-6">
        @php $tabs = [null => 'Semua', 'berita' => 'Berita', 'pengumuman' => 'Pengumuman']; @endphp
        @foreach ($tabs as $val => $label)
            <a href="{{ route('berita.index', $val ? ['kategori' => $val] : []) }}"
               class="px-4 py-2 rounded-full transition {{ $kategori === $val ? 'text-white' : 'bg-white border border-black/10 text-black/60 hover:border-black/20' }}"
               style="{{ $kategori === $val ? 'background: var(--sawah-dark);' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</section>

{{-- ============ SOROTAN (BAND PUTIH) ============ --}}
@if($sorotan)
    <section class="bg-white border-y border-black/5">
        <div class="wadah py-10">
            <a href="{{ route('berita.show', $sorotan->slug) }}" class="reveal-kiri group grid lg:grid-cols-2 gap-7 lg:gap-10 items-center">

                <div class="aspect-[16/10] rounded-2xl overflow-hidden bg-[var(--sawah-light)] order-1">
                    @if($sorotan->thumbnail_path)
                        <img src="{{ Storage::url($sorotan->thumbnail_path) }}" alt="{{ $sorotan->judul }}"
                             class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center font-display text-5xl"
                             style="color: var(--sawah-dark); opacity: 0.18;">DC</div>
                    @endif
                </div>

                <div class="order-2">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-[11px] px-2.5 py-1 rounded-full font-medium"
                              style="background: {{ $sorotan->kategori === 'pengumuman' ? '#FEF6E7' : '#E8F0FB' }}; color: {{ $sorotan->kategori === 'pengumuman' ? '#92600E' : '#1B4B8F' }};">
                            {{ ucfirst($sorotan->kategori) }}
                        </span>
                        <span class="text-[11px] uppercase tracking-widest text-black/35">Terbaru</span>
                    </div>

                    <h2 class="font-display text-2xl sm:text-3xl font-semibold leading-tight group-hover:underline underline-offset-4 decoration-1">
                        {{ $sorotan->judul }}
                    </h2>

                    <p class="text-sm text-black/60 leading-relaxed mt-3">
                        {{ \Illuminate\Support\Str::limit(strip_tags($sorotan->konten), 200) }}
                    </p>

                    <p class="text-xs text-black/40 mt-4">{{ $sorotan->tanggal_publish->translatedFormat('d F Y') }}</p>

                    {{-- kartu sorotan seluruhnya sudah berupa tautan,
                         jadi ini penanda visual saja, bukan tautan bersarang --}}
                    <span class="tombol-utama inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-lg text-sm font-semibold">
                        Baca Selengkapnya
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor"
                             class="w-4 h-4 shrink-0 transition-transform group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </div>
            </a>
        </div>
    </section>
@endif

{{-- ============ DAFTAR LAINNYA ============ --}}
<section class="wadah py-12">
    @if($sisanya->count())
        @if($sorotan)
            <div class="flex items-center gap-3 mb-6">
                <h2 class="font-display text-lg font-semibold shrink-0">Lainnya</h2>
                <span class="h-px flex-1 bg-black/10"></span>
            </div>
        @endif

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($sisanya as $b)
                <a href="{{ route('berita.show', $b->slug) }}"
                   class="reveal bg-white rounded-2xl border border-black/5 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition duration-200 group flex flex-col"
                   style="transition-delay: {{ ($loop->index % 3) * 80 }}ms;">
                    <div class="aspect-[16/10] bg-[var(--sawah-light)] overflow-hidden shrink-0">
                        @if($b->thumbnail_path)
                            <img src="{{ Storage::url($b->thumbnail_path) }}" alt="{{ $b->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-display text-3xl"
                                 style="color: var(--sawah-dark); opacity: 0.2;">DC</div>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <span class="text-[11px] px-2 py-0.5 rounded-full font-medium self-start"
                              style="background: {{ $b->kategori === 'pengumuman' ? '#FEF6E7' : '#E8F0FB' }}; color: {{ $b->kategori === 'pengumuman' ? '#92600E' : '#1B4B8F' }};">
                            {{ ucfirst($b->kategori) }}
                        </span>
                        <h3 class="font-display text-base font-semibold mt-2 leading-snug line-clamp-2 flex-1">{{ $b->judul }}</h3>
                        <p class="text-xs text-black/40 mt-3">{{ $b->tanggal_publish->translatedFormat('d M Y') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @elseif(!$sorotan)
        <p class="text-sm text-black/40 text-center py-10">Belum ada berita atau pengumuman.</p>
    @endif

    <div class="mt-8">
        {{ $berita->links() }}
    </div>
</section>
@endsection