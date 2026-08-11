@extends('layouts.publik')

@section('title', $kata ? 'Pencarian: ' . $kata : 'Pencarian')
@section('main-class', '')

@section('content')

{{-- ============ KOTAK PENCARIAN ============ --}}
<section class="border-b" style="border-color: var(--garis); background: var(--kertas);">
    <div class="wadah py-12">
        <p class="label-bagian">Pencarian</p>
        <h1 class="font-display text-2xl sm:text-3xl font-bold mt-1.5 mb-6">
            Cari Informasi Desa
        </h1>

        <form method="GET" action="{{ route('pencarian.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--lembut);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <input type="search" name="q" value="{{ $kata }}" autofocus
                       placeholder="Ketik kata kunci, misalnya: posyandu"
                       class="w-full rounded-lg border text-sm pl-11 pr-4 py-3 bg-white"
                       style="border-color: var(--garis);">
            </div>
            <button class="tombol-utama px-6 rounded-lg text-sm font-semibold shrink-0">Cari</button>
        </form>

        <p class="text-xs mt-3" style="color: var(--lembut);">
            Mencari di berita, pengumuman, agenda kegiatan, dokumen desa, dan potensi desa.
        </p>
    </div>
</section>

<section class="wadah py-12">

    {{-- ============ KEADAAN KHUSUS ============ --}}
    @if($kata === '')
        <div class="reveal-skala kartu p-10 text-center">
            <p class="font-display text-base font-bold mb-1">Belum ada kata kunci</p>
            <p class="text-sm" style="color: var(--lembut);">
                Masukkan kata kunci di kolom pencarian di atas untuk mulai mencari.
            </p>
        </div>

    @elseif($terlaluPendek)
        <div class="reveal-skala kartu p-10 text-center">
            <p class="font-display text-base font-bold mb-1">Kata kunci terlalu pendek</p>
            <p class="text-sm" style="color: var(--lembut);">
                Gunakan minimal {{ $minimalHuruf }} huruf agar hasil pencarian lebih tepat.
            </p>
        </div>

    @elseif($jumlah === 0)
        <div class="reveal-skala kartu p-10 text-center">
            <p class="font-display text-base font-bold mb-1">Tidak ada hasil untuk &ldquo;{{ $kata }}&rdquo;</p>
            <p class="text-sm mb-6" style="color: var(--lembut);">
                Coba gunakan kata yang lebih umum, atau periksa kembali ejaannya.
            </p>
            <div class="flex flex-wrap gap-2 justify-center">
                <a href="{{ route('berita.index') }}" class="tombol-garis bg-white px-4 py-2 rounded-lg text-sm">Lihat Semua Berita</a>
                <a href="{{ route('dokumen.index') }}" class="tombol-garis bg-white px-4 py-2 rounded-lg text-sm">Lihat Dokumen Desa</a>
            </div>
        </div>

    @else
        <p class="text-sm mb-8" style="color: var(--lembut);">
            Ditemukan <strong style="color: var(--ink);">{{ $jumlah }}</strong> hasil untuk
            &ldquo;<strong style="color: var(--ink);">{{ $kata }}</strong>&rdquo;
        </p>

        <div class="space-y-10">

            {{-- ---------- BERITA ---------- --}}
            @if($berita->count())
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <h2 class="font-display text-lg font-bold">Berita &amp; Pengumuman</h2>
                        <span class="text-xs px-2 py-0.5 rounded-full"
                              style="background: var(--sawah-light); color: var(--sawah-dark);">{{ $berita->count() }}</span>
                    </div>

                    <div class="reveal kartu overflow-hidden">
                        @foreach ($berita as $b)
                            <a href="{{ route('berita.show', $b->slug) }}"
                               class="block px-5 py-4 hover:bg-black/[0.02] transition {{ !$loop->last ? 'border-b' : '' }}"
                               style="border-color: var(--garis);">
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded"
                                      style="background: {{ $b->kategori === 'pengumuman' ? '#FEF6E7' : 'var(--sawah-light)' }}; color: {{ $b->kategori === 'pengumuman' ? '#92600E' : 'var(--sawah-dark)' }};">
                                    {{ ucfirst($b->kategori) }}
                                </span>
                                <p class="font-display text-sm font-semibold mt-2 leading-snug">{{ $b->judul }}</p>
                                <p class="text-xs mt-1.5 leading-relaxed" style="color: var(--lembut);">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($b->konten), 140) }}
                                </p>
                                <p class="text-[11px] mt-2" style="color: var(--lembut);">
                                    {{ $b->tanggal_publish->translatedFormat('d F Y') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ---------- DOKUMEN ---------- --}}
            @if($dokumen->count())
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <h2 class="font-display text-lg font-bold">Dokumen Desa</h2>
                        <span class="text-xs px-2 py-0.5 rounded-full"
                              style="background: var(--sawah-light); color: var(--sawah-dark);">{{ $dokumen->count() }}</span>
                    </div>

                    <div class="reveal kartu overflow-hidden">
                        @foreach ($dokumen as $d)
                            <div class="flex items-center gap-4 px-5 py-4 {{ !$loop->last ? 'border-b' : '' }}"
                                 style="border-color: var(--garis);">
                                <span class="shrink-0 w-10 h-12 rounded-lg flex items-center justify-center text-[10px] font-semibold uppercase"
                                      style="background: var(--sawah-light); color: var(--sawah-dark);">
                                    {{ $d->ekstensi }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium leading-snug">{{ $d->nama }}</p>
                                    <p class="text-[11px] mt-1" style="color: var(--lembut);">
                                        {{ $d->label_kategori }}
                                        @if($d->ukuran_terbaca) &middot; {{ $d->ukuran_terbaca }} @endif
                                    </p>
                                </div>
                                <a href="{{ route('dokumen.unduh', $d) }}"
                                   class="tombol-utama shrink-0 px-4 py-2 rounded-lg text-xs font-semibold">Unduh</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ---------- AGENDA ---------- --}}
            @if($agenda->count())
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <h2 class="font-display text-lg font-bold">Agenda Kegiatan</h2>
                        <span class="text-xs px-2 py-0.5 rounded-full"
                              style="background: var(--sawah-light); color: var(--sawah-dark);">{{ $agenda->count() }}</span>
                    </div>

                    <div class="reveal kartu overflow-hidden">
                        @foreach ($agenda as $a)
                            <div class="flex gap-4 px-5 py-4 {{ !$loop->last ? 'border-b' : '' }}"
                                 style="border-color: var(--garis);">
                                <div class="shrink-0 w-12 text-center rounded-lg py-1.5"
                                     style="background: var(--sawah-light); color: var(--sawah-dark);">
                                    <p class="text-[10px] uppercase font-semibold">{{ $a->tanggal_mulai->translatedFormat('M') }}</p>
                                    <p class="font-display text-base font-bold leading-none mt-0.5">{{ $a->tanggal_mulai->format('d') }}</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium leading-snug">{{ $a->judul }}</p>
                                    <p class="text-[11px] mt-1" style="color: var(--lembut);">
                                        {{ $a->tanggal_mulai->translatedFormat('d F Y, H:i') }}
                                        @if($a->lokasi) &middot; {{ $a->lokasi }} @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-xs mt-2.5">
                        <a href="{{ route('agenda.index') }}" class="hover:underline underline-offset-4" style="color: var(--padi);">
                            Lihat seluruh agenda &rarr;
                        </a>
                    </p>
                </div>
            @endif

            {{-- ---------- POTENSI DESA ---------- --}}
            @if($potensi->count())
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <h2 class="font-display text-lg font-bold">Potensi Desa</h2>
                        <span class="text-xs px-2 py-0.5 rounded-full"
                              style="background: var(--sawah-light); color: var(--sawah-dark);">{{ $potensi->count() }}</span>
                    </div>

                    <div class="reveal kartu overflow-hidden">
                        @foreach ($potensi as $p)
                            <div class="flex gap-4 px-5 py-4 {{ !$loop->last ? 'border-b' : '' }}"
                                 style="border-color: var(--garis);">
                                <div class="w-14 h-12 rounded-lg overflow-hidden shrink-0" style="background: var(--sawah-light);">
                                    @if($p->foto_path)
                                        <img src="{{ Storage::url($p->foto_path) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center font-display text-xs font-bold"
                                             style="color: var(--sawah-dark); opacity: 0.35;">DC</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] font-semibold uppercase tracking-wide" style="color: var(--padi);">{{ $p->label_jenis }}</p>
                                    <p class="text-sm font-medium leading-snug">{{ $p->nama }}</p>
                                    @if($p->deskripsi)
                                        <p class="text-xs mt-1 line-clamp-2" style="color: var(--lembut);">{{ $p->deskripsi }}</p>
                                    @endif
                                </div>
                                @if($p->link_wa)
                                    <a href="{{ $p->link_wa }}" target="_blank" rel="noopener"
                                       class="tombol-garis bg-white shrink-0 self-center px-3 py-2 rounded-lg text-xs">WhatsApp</a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <p class="text-xs mt-2.5">
                        <a href="{{ route('potensi.index') }}" class="hover:underline underline-offset-4" style="color: var(--padi);">
                            Lihat seluruh potensi desa &rarr;
                        </a>
                    </p>
                </div>
            @endif
        </div>
    @endif
</section>
@endsection