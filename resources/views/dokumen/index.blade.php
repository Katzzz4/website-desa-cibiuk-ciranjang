@extends('layouts.publik')

@section('title', $info['label'])
@section('main-class', '')

@section('meta_judul', $info['label'] . ' — Desa Cibiuk')
@section('meta_deskripsi', $info['ket'])

@section('content')

{{-- ============ HEADER ============ --}}
<section class="border-b" style="border-color: var(--garis); background: var(--kertas);">
    <div class="wadah py-10">

        <a href="{{ route('dokumen.index') }}"
           class="tombol-garis group inline-flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg text-sm font-medium mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor"
                 class="w-4 h-4 shrink-0 transition-transform group-hover:-translate-x-0.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Semua Dokumen
        </a>

        <p class="label-bagian">Dokumen Desa</p>
        <h1 class="font-display text-2xl sm:text-3xl font-bold mt-1.5">{{ $info['label'] }}</h1>
        <p class="text-sm mt-2.5 max-w-2xl leading-relaxed" style="color: var(--lembut);">
            {{ $info['ket'] }}
        </p>
    </div>
</section>

<section class="wadah py-10">

    {{-- ---------- Saringan jenis & pencarian ---------- --}}
    <div class="space-y-3 mb-8">
        @if(count($jenisTerpakai) > 1)
            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('dokumen.daftar', $klasifikasi) }}"
                   class="px-4 py-2 rounded-full transition {{ !$kategori ? 'text-white' : 'bg-white border border-black/10 text-black/60 hover:border-black/20' }}"
                   style="{{ !$kategori ? 'background: var(--sawah-dark);' : '' }}">
                    Semua
                </a>
                @foreach ($info['jenis'] as $nilai => $label)
                    @if(in_array($nilai, $jenisTerpakai))
                        <a href="{{ route('dokumen.daftar', [$klasifikasi, 'kategori' => $nilai]) }}"
                           class="px-4 py-2 rounded-full transition {{ $kategori === $nilai ? 'text-white' : 'bg-white border border-black/10 text-black/60 hover:border-black/20' }}"
                           style="{{ $kategori === $nilai ? 'background: var(--sawah-dark);' : '' }}">
                            {{ $label }}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        <form method="GET" class="flex gap-2 max-w-xl">
            @if($kategori)
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif
            <input type="search" name="cari" value="{{ request('cari') }}"
                   placeholder="Cari nama dokumen..."
                   class="flex-1 rounded-lg border-black/10 text-sm">
            <button class="tombol-utama px-5 rounded-lg text-sm font-semibold shrink-0">Cari</button>
        </form>
    </div>

    {{-- ---------- Daftar dokumen ---------- --}}
    <div class="space-y-3">
        @forelse ($dokumen as $d)
            <div class="reveal kartu p-5 flex items-center gap-4" style="transition-delay: {{ $loop->index * 55 }}ms;">
                <div class="shrink-0 w-12 h-14 rounded-lg flex items-center justify-center text-[10px] font-semibold uppercase tracking-wide"
                     style="background: var(--sawah-light); color: var(--sawah-dark);">
                    {{ $d->ekstensi }}
                </div>

                <div class="flex-1 min-w-0">
                    <h2 class="font-display text-base font-semibold leading-snug">{{ $d->nama }}</h2>
                    <p class="text-xs mt-1" style="color: var(--lembut);">
                        {{ $d->label_kategori }}
                        @if($d->ukuran_terbaca) &middot; {{ $d->ukuran_terbaca }} @endif
                        &middot; {{ $d->created_at->translatedFormat('d M Y') }}
                    </p>
                </div>

                <a href="{{ route('dokumen.unduh', $d) }}"
                   class="tombol-utama shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Unduh
                </a>
            </div>
        @empty
            <div class="kartu p-10 text-center">
                <p class="font-display text-base font-bold mb-1">
                    @if(request('cari'))
                        Tidak ada dokumen yang cocok
                    @else
                        Belum ada dokumen pada bagian ini
                    @endif
                </p>
                <p class="text-sm" style="color: var(--lembut);">
                    @if(request('cari'))
                        Coba kata kunci lain, atau lihat seluruh dokumen pada bagian ini.
                    @else
                        Dokumen sedang disiapkan oleh pemerintah desa.
                    @endif
                </p>
                @if(request('cari'))
                    <a href="{{ route('dokumen.daftar', $klasifikasi) }}"
                       class="tombol-garis inline-flex bg-white px-5 py-2.5 rounded-lg text-sm mt-5">
                        Lihat Semua
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $dokumen->links() }}
    </div>
</section>
@endsection