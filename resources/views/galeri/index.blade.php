@extends('layouts.publik')

@section('title', 'Galeri Desa')
@section('main-class', '')

@section('content')
{{-- ============ HEADER ============ --}}
<section class="wadah pt-10 pb-8">
    <p class="text-xs font-medium tracking-[0.2em] uppercase" style="color: var(--talang);">Dokumentasi</p>
    <h1 class="font-display text-3xl sm:text-4xl font-semibold mt-1">Galeri Desa</h1>

    <div class="flex flex-wrap gap-2 text-sm mt-6">
        @php $tabs = [null => 'Semua', 'foto' => 'Foto', 'video' => 'Video']; @endphp
        @foreach ($tabs as $val => $label)
            <a href="{{ route('galeri.index', $val ? ['tipe' => $val] : []) }}"
               class="px-4 py-2 rounded-full transition {{ $tipe === $val ? 'text-white' : 'bg-white border border-black/10 text-black/60 hover:border-black/20' }}"
               style="{{ $tipe === $val ? 'background: var(--sawah-dark);' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</section>

{{-- ============ MOSAIK (BAND PUTIH) ============ --}}
<section class="bg-white border-y border-black/5">
    <div class="wadah py-10">

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 auto-rows-fr gap-3 sm:gap-4">
            @forelse ($galeri as $i => $g)
                @php
                    // ubin pertama dibuat besar, lalu tiap kelipatan 7 dibuat lebar,
                    // supaya susunannya tidak monoton tapi tetap teratur
                    $besar = $galeri->currentPage() === 1 && $i === 0;
                    $lebar = !$besar && $i % 7 === 3;
                    $span = $besar
                        ? 'col-span-2 row-span-2'
                        : ($lebar ? 'sm:col-span-2' : '');
                @endphp

                @if($g->tipe === 'foto')
                    <button onclick="bukaLightbox('{{ Storage::url($g->file_path) }}', '{{ addslashes($g->judul) }}')"
                            class="group relative rounded-xl overflow-hidden bg-[var(--sawah-light)] {{ $span }} {{ $besar || $lebar ? '' : 'aspect-square' }}">
                        <img src="{{ Storage::url($g->file_path) }}" alt="{{ $g->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute inset-x-0 bottom-0 p-3 text-left text-white text-xs bg-gradient-to-t from-black/65 to-transparent opacity-0 group-hover:opacity-100 transition">
                            {{ $g->judul }}
                        </span>
                    </button>
                @else
                    @php $bisaDiputar = (bool) $g->id_youtube; @endphp

                    @if($bisaDiputar)
                        <button onclick="bukaVideo('{{ $g->url_embed }}', '{{ addslashes($g->judul) }}', '{{ $g->url_tonton }}')"
                                class="group relative rounded-xl overflow-hidden bg-black {{ $span }} {{ $besar || $lebar ? '' : 'aspect-square' }}">
                            <img src="{{ $g->sampul_video }}" alt="{{ $g->judul }}"
                                 class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition duration-500">
                    @else
                        {{-- alamat video di luar YouTube: tidak bisa disematkan, buka di tab baru --}}
                        <a href="{{ $g->url_tonton }}" target="_blank" rel="noopener"
                           class="group relative rounded-xl overflow-hidden bg-black flex items-center justify-center {{ $span }} {{ $besar || $lebar ? '' : 'aspect-square' }}">
                    @endif

                        {{-- tombol putar --}}
                        <span class="absolute inset-0 flex items-center justify-center">
                            <span class="w-14 h-14 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-1" style="color: var(--sawah-dark);"
                                     viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </span>
                        </span>

                        <span class="absolute inset-x-0 bottom-0 p-3 text-left text-white text-xs bg-gradient-to-t from-black/80 to-transparent">
                            {{ $g->judul }}
                        </span>

                    @if($bisaDiputar)
                        </button>
                    @else
                        </a>
                    @endif
                @endif
            @empty
                <p class="text-sm text-black/40 col-span-full text-center py-10">Belum ada dokumentasi.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $galeri->links() }}
        </div>
    </div>
</section>

{{-- LIGHTBOX: dipakai untuk foto maupun video --}}
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4 sm:p-6">
    <button onclick="tutupLightbox()" aria-label="Tutup"
            class="absolute top-4 right-5 text-white/70 hover:text-white text-4xl leading-none">&times;</button>

    <div class="w-full max-w-4xl" onclick="event.stopPropagation()">
        {{-- tampilan foto --}}
        <div id="wadah-foto">
            <img id="lightbox-img" src="" alt="" class="w-full rounded-lg max-h-[80vh] object-contain mx-auto">
        </div>

        {{-- tampilan video --}}
        <div id="wadah-video" class="hidden">
            <div class="relative w-full rounded-lg overflow-hidden bg-black" style="padding-top: 56.25%;">
                <iframe id="lightbox-video" class="absolute inset-0 w-full h-full" src=""
                        title="Pemutar video" frameborder="0" allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            </div>
        </div>

        <p id="lightbox-judul" class="text-white/70 text-sm text-center mt-4"></p>

        {{-- Sebagian video dikunci pemiliknya agar hanya bisa diputar di YouTube.
             Tautan ini memastikan warga tetap punya jalan untuk menontonnya. --}}
        <p id="lightbox-cadangan" class="text-center mt-2 hidden">
            <a href="#" target="_blank" rel="noopener"
               class="text-xs text-white/50 hover:text-white/80 underline underline-offset-4">
                Tidak bisa diputar di sini? Tonton di YouTube
            </a>
        </p>
    </div>
</div>

<script>
    const lightbox   = document.getElementById('lightbox');
    const wadahFoto  = document.getElementById('wadah-foto');
    const wadahVideo = document.getElementById('wadah-video');
    const elImg      = document.getElementById('lightbox-img');
    const elVideo    = document.getElementById('lightbox-video');
    const elJudul    = document.getElementById('lightbox-judul');
    const elCadangan = document.getElementById('lightbox-cadangan');

    function bukaLightbox(src, judul) {
        elImg.src = src;
        wadahFoto.classList.remove('hidden');
        wadahVideo.classList.add('hidden');
        elCadangan.classList.add('hidden');
        tampilkan(judul);
    }

    function bukaVideo(src, judul, urlTonton) {
        // autoplay ditambahkan agar video langsung berjalan saat dibuka
        elVideo.src = src + (src.includes('?') ? '&' : '?') + 'autoplay=1';
        wadahVideo.classList.remove('hidden');
        wadahFoto.classList.add('hidden');

        if (urlTonton) {
            elCadangan.querySelector('a').href = urlTonton;
            elCadangan.classList.remove('hidden');
        } else {
            elCadangan.classList.add('hidden');
        }

        tampilkan(judul);
    }

    function tampilkan(judul) {
        elJudul.textContent = judul || '';
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function tutupLightbox() {
        // sumber video dikosongkan supaya suaranya berhenti saat ditutup
        elVideo.src = '';
        elImg.src = '';
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
    }

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) tutupLightbox();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') tutupLightbox();
    });
</script>
@endsection