{{--
    STRUKTUR ORGANISASI PEMERINTAH DESA

    Dipakai bersama oleh halaman Beranda dan Profil Desa, agar bagannya
    ditulis satu kali saja. Membutuhkan variabel $perangkat.

    Pemakaian:
        @include('partials.struktur-organisasi')
--}}

@php
    $kepalaDesa  = $perangkat->firstWhere('atasan_jabatan', null);
    $lapisKedua  = $perangkat->where('atasan_jabatan', $kepalaDesa->jabatan ?? '___');
    $lapisKetiga = $perangkat->whereIn('atasan_jabatan', $lapisKedua->pluck('jabatan')->all());

    // hanya perangkat yang tupoksinya sudah diisi yang muncul di daftar
    $adaTupoksi = $perangkat->filter(fn ($p) => filled($p->tupoksi));
@endphp

{{-- ============ BAGAN ============ --}}
<div class="reveal-kanan bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
    <div class="flex items-center gap-2 mb-1">
        <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
        <h2 class="font-display text-lg font-semibold">Struktur Organisasi Pemerintah Desa</h2>
    </div>
    <p class="text-sm text-black/50 mb-7 ml-3.5">
        Dipimpin oleh {{ $kepalaDesa->nama ?? '-' }} sebagai Kepala Desa.
        @if($adaTupoksi->count())
            Lihat tugas pokok dan fungsi di bawah untuk mengetahui keperluan Anda
            sebaiknya disampaikan ke bagian mana.
        @endif
    </p>

    <div class="space-y-6">

        {{-- Kepala Desa --}}
        @if($kepalaDesa)
            <div class="flex flex-col items-center">
                <div class="text-center rounded-xl px-6 py-4" style="background: var(--sawah-dark);">
                    <p class="text-sm font-semibold text-white">{{ $kepalaDesa->nama }}</p>
                    <p class="text-xs mt-0.5" style="color: var(--padi-light);">{{ $kepalaDesa->jabatan }}</p>
                </div>
                @if($lapisKedua->count())
                    <span class="w-px h-5 mt-3" style="background: rgba(0,0,0,.14);"></span>
                @endif
            </div>
        @endif

        {{-- Lapis kedua: Sekretaris dan Kepala Dusun --}}
        @if($lapisKedua->count())
            <div class="flex flex-wrap justify-center gap-3">
                @foreach ($lapisKedua as $p)
                    @if(filled($p->tupoksi))
                        <a href="#tupoksi-{{ $p->id }}"
                           class="text-center rounded-xl px-4 py-3 border-2 min-w-[140px] transition hover:brightness-95"
                           style="border-color: var(--sawah-light); background: var(--sawah-light);">
                            <p class="text-sm font-medium" style="color: var(--sawah-dark);">{{ $p->nama }}</p>
                            <p class="text-xs text-black/50 mt-0.5">
                                {{ $p->jabatan }}
                                @if($p->dusun) &middot; {{ $p->dusun->nama }} @endif
                            </p>
                        </a>
                    @else
                        <div class="text-center rounded-xl px-4 py-3 border-2 min-w-[140px]"
                             style="border-color: var(--sawah-light); background: var(--sawah-light);">
                            <p class="text-sm font-medium" style="color: var(--sawah-dark);">{{ $p->nama }}</p>
                            <p class="text-xs text-black/50 mt-0.5">
                                {{ $p->jabatan }}
                                @if($p->dusun) &middot; {{ $p->dusun->nama }} @endif
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Lapis ketiga: Kasi dan Kaur --}}
        @if($lapisKetiga->count())
            <div class="flex flex-wrap justify-center gap-2.5 pt-4 border-t border-dashed border-black/10">
                @foreach ($lapisKetiga as $p)
                    @if(filled($p->tupoksi))
                        <a href="#tupoksi-{{ $p->id }}"
                           class="text-center rounded-lg px-3.5 py-2 bg-black/[0.02] min-w-[125px] transition hover:bg-black/[0.05]">
                            <p class="text-xs font-medium">{{ $p->nama }}</p>
                            <p class="text-[11px] text-black/50">{{ $p->jabatan }}</p>
                        </a>
                    @else
                        <div class="text-center rounded-lg px-3.5 py-2 bg-black/[0.02] min-w-[125px]">
                            <p class="text-xs font-medium">{{ $p->nama }}</p>
                            <p class="text-[11px] text-black/50">{{ $p->jabatan }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ============ TUGAS POKOK DAN FUNGSI ============ --}}
@if($adaTupoksi->count())
    <div class="reveal-kiri bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
            <h2 class="font-display text-lg font-semibold">Tugas Pokok dan Fungsi</h2>
        </div>
        <p class="text-sm text-black/50 mb-6 ml-3.5">
            Keperluan Anda sebaiknya disampaikan kepada bagian yang menanganinya,
            agar prosesnya lebih cepat.
        </p>

        <div class="divide-y divide-black/5">
            @foreach ($adaTupoksi as $p)
                <div id="tupoksi-{{ $p->id }}"
                     class="py-5 first:pt-0 last:pb-0 scroll-mt-28 grid sm:grid-cols-[210px_1fr] gap-2 sm:gap-6">

                    <div>
                        <p class="font-display text-sm font-semibold" style="color: var(--sawah-dark);">
                            {{ $p->jabatan }}
                            @if($p->dusun) &middot; {{ $p->dusun->nama }} @endif
                        </p>
                        <p class="text-xs text-black/45 mt-0.5">{{ $p->nama }}</p>
                    </div>

                    <p class="text-sm text-black/70 leading-relaxed">{{ $p->tupoksi }}</p>
                </div>
            @endforeach
        </div>

        {{-- Jalan keluar bagi warga yang keperluannya tidak termasuk di atas --}}
        <div class="mt-7 pt-6 border-t border-black/5 rounded-xl p-5"
             style="background: var(--sawah-light); color: var(--sawah-dark);">
            <p class="text-sm leading-relaxed">
                Tidak yakin harus ke bagian mana? Sampaikan saja melalui
                <a href="{{ route('pengaduan.create') }}" class="font-semibold underline underline-offset-4">
                    layanan pengaduan
                </a>,
                nanti akan diteruskan oleh petugas desa ke bagian yang menangani.
            </p>
        </div>
    </div>
@endif