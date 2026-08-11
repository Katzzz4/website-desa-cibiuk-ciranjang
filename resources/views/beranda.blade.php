@extends('layouts.publik')

@section('title', 'Beranda')
@section('main-class', 'overflow-x-hidden max-w-full')
@section('header-transparan', 'ya')

@section('content')



{{-- ============ HERO ============ --}}
{{-- Satu layar penuh di komputer, lebih ringkas di ponsel. --}}
<section class="hero-penuh relative flex items-center justify-center overflow-hidden"
         style="background: var(--sawah-dark);">

    @if($profil?->foto_hero_path)
        <img src="{{ Storage::url($profil->foto_hero_path) }}"
             alt="Pemandangan Desa {{ $profil->nama_desa ?? 'Cibiuk' }}"
             class="foto-hero absolute inset-0 w-full h-full object-cover">

        {{-- Lapisan gelap agar tulisan putih tetap terbaca di atas foto,
             lebih pekat di bagian atas tempat menu berada. --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(180deg, rgba(7,46,29,.62) 0%, rgba(7,46,29,.38) 45%, rgba(7,46,29,.72) 100%);"></div>
    @else
        {{-- Bila foto belum diunggah, tampilkan motif agar tidak terlihat kosong --}}
        <div class="absolute inset-0 terrace-texture opacity-60"></div>
    @endif

    <div class="relative w-full max-w-full min-w-0 wadah py-24 md:py-32 text-center text-white">
        <p class="label-bagian anim-hero tunda-1" style="color: rgba(255,255,255,.72);">
            Website Resmi Pemerintah Desa
        </p>

        {{-- Tiap baris judul tersingkap naik dari bawah secara berurutan. --}}
        <h1 class="font-display font-bold mt-5"
            style="font-size: clamp(1.9rem, 4.2vw, 3.5rem); line-height: 1.18;
                   text-shadow: 0 2px 24px rgba(0,0,0,.38);">
            <span class="baris-judul">
                <span style="animation-delay: .26s;">Selamat Datang</span>
            </span>
            <span class="baris-judul">
                <span class="break-words" style="animation-delay: .42s;">Website Resmi Desa {{ $profil->nama_desa ?? 'Cibiuk' }}</span>
            </span>
        </h1>

        {{-- Garis aksen melebar setelah judul selesai tampil --}}
        <span class="garis-hero block h-[3px] w-24 mx-auto mt-7 rounded-full"
              style="background: var(--padi-light); animation-delay: .82s;"></span>

        <p class="anim-hero tunda-4 text-base sm:text-lg text-white/80 mt-7 max-w-2xl mx-auto leading-relaxed"
           style="text-shadow: 0 1px 12px rgba(0,0,0,.4);">
            Sumber informasi terbaru tentang pemerintahan dan pelayanan warga
            di {{ $profil?->wilayah_lengkap ?? 'Kecamatan Ciranjang, Kabupaten Cianjur, Jawa Barat' }}
        </p>

        <div class="anim-hero tunda-5 flex flex-wrap gap-3 mt-10 justify-center">
            <a href="{{ route('pengaduan.create') }}"
               class="px-6 py-3 rounded-lg text-sm font-semibold bg-white transition hover:bg-white/90"
               style="color: var(--sawah-dark);">
                Sampaikan Pengaduan
            </a>
            <a href="{{ route('profil.index') }}"
               class="px-6 py-3 rounded-lg text-sm font-medium border border-white/35 hover:bg-white/10 transition">
                Profil Desa
            </a>
        </div>
    </div>

    {{-- Garis motif tipis sebagai pembatas ke bagian berikutnya --}}
    <span class="absolute inset-x-0 bottom-0 h-2.5 motif-anyaman-terang opacity-70"></span>

    {{-- Penanda gulir, hanya di layar besar tempat hero setinggi satu layar --}}
    <a href="#layanan-cepat" aria-label="Lihat isi selanjutnya"
       class="anim-hero hidden md:flex absolute bottom-8 left-1/2 -translate-x-1/2 flex-col items-center gap-2
              text-white/60 hover:text-white transition"
       style="animation-delay: 1.2s;">
        <span class="text-[11px] tracking-[0.18em] uppercase font-medium">Gulir</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.8" stroke="currentColor" class="w-5 h-5 anim-turun">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </a>
</section>

{{-- ============ LAYANAN CEPAT ============ --}}
<section id="layanan-cepat" class="pola-titik border-b" style="border-color: var(--garis);">
    <div class="wadah py-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $ikon = [
                    'pengaduan' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
                    'lacak'     => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
                    'data'      => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
                    'dokumen'   => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 013.5 7.125v-1.5A3.375 3.375 0 0010.625 2.25H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
                ];
                $layanan = [
                    ['Buat Pengaduan', 'Laporkan masalah di lingkungan Anda', route('pengaduan.create'), $ikon['pengaduan']],
                    ['Lacak Laporan', 'Cek perkembangan lewat nomor tiket', route('pengaduan.lacak.form'), $ikon['lacak']],
                    ['Infografis Warga', 'Data kependudukan desa terkini', route('infografis.penduduk'), $ikon['data']],
                    ['Dokumen Desa', 'Unduh Perdes, SK, dan lainnya', route('dokumen.index'), $ikon['dokumen']],
                ];
            @endphp

            @foreach ($layanan as [$judul, $ket, $tautan, $path])
                <a href="{{ $tautan }}" class="reveal-skala kartu kartu-tautan kartu-aksen p-5 block"
                   style="transition-delay: {{ $loop->index * 55 }}ms;">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg mb-3"
                          style="background: var(--sawah-light); color: var(--sawah-dark);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                        </svg>
                    </span>
                    <p class="font-display text-sm font-semibold leading-snug">{{ $judul }}</p>
                    <p class="text-xs mt-1.5 leading-relaxed" style="color: var(--lembut);">{{ $ket }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ VIDEO PENGENALAN DESA ============ --}}
{{-- Otomatis tersembunyi bila alamat videonya belum diisi dari dashboard --}}
@include('partials.video-desa')

{{-- ============ DESA DALAM ANGKA ============ --}}
@php
    $totalJiwa = $ringkasan ? $ringkasan->total_laki + $ringkasan->total_perempuan : null;
    $statistik = collect([
        ['angka' => $totalJiwa, 'label' => 'Jiwa Penduduk'],
        ['angka' => $jumlahDusun ?: null, 'label' => 'Dusun'],
        ['angka' => $profil?->luas_wilayah_ha, 'label' => 'Hektar Wilayah'],
        ['angka' => $ringkasan?->total_kk, 'label' => 'Kepala Keluarga'],
    ])->filter(fn ($s) => !is_null($s['angka']))->values();

    $kolom = match ($statistik->count()) {
        1 => 'sm:grid-cols-1',
        2 => 'sm:grid-cols-2',
        3 => 'sm:grid-cols-3',
        default => 'sm:grid-cols-4',
    };
@endphp

{{--
    PETA WILAYAH DESA — RINGKASAN UNTUK BERANDA

    Menampilkan gambar peta sosial berdampingan dengan batas wilayah dan
    daftar dusun. Otomatis tersembunyi bila gambar petanya belum diunggah.

    Membutuhkan variabel $profil dan $dusun. Pemakaian:
        @include('partials.peta-beranda')
--}}

@if($profil?->peta_wilayah_path)
    <section class="border-t" style="border-color: var(--garis);">
        <div class="wadah py-11">

            <div class="flex items-end justify-between gap-4 mb-7 reveal">
                <div>
                    <p class="label-bagian">Geografis</p>
                    <h2 class="font-display text-2xl font-bold mt-1.5">Peta Wilayah Desa</h2>
                </div>
                <a href="{{ route('peta.index') }}"
                   class="text-sm font-medium whitespace-nowrap shrink-0 hover:underline underline-offset-4"
                   style="color: var(--padi);">
                    Lihat selengkapnya &rarr;
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-5">

                {{-- Gambar peta --}}
                <a href="{{ route('peta.index') }}"
                   class="reveal-skala kartu kartu-tautan overflow-hidden lg:col-span-2 block group">
                    <div class="aspect-[16/10] overflow-hidden" style="background: var(--sawah-light);">
                        <img src="{{ Storage::url($profil->peta_wilayah_path) }}"
                             alt="Peta wilayah Desa {{ $profil->nama_desa ?? 'Cibiuk' }}"
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                             loading="lazy">
                    </div>
                </a>

                {{-- Keterangan wilayah --}}
                <div class="reveal-kanan kartu p-6 flex flex-col self-start">

                    @if($profil?->luas_wilayah_ha)
                        <div class="pb-4 mb-4 border-b" style="border-color: var(--garis);">
                            <p class="text-xs" style="color: var(--lembut);">Luas Wilayah</p>
                            <p class="font-display text-2xl font-bold mt-0.5" style="color: var(--sawah-dark);">
                                {{ number_format($profil->luas_wilayah_ha, 0, ',', '.') }}
                                <span class="text-base font-medium">Ha</span>
                            </p>
                        </div>
                    @endif

                    <p class="label-bagian mb-2.5">Berbatasan Dengan</p>
                    <div class="space-y-1.5 mb-5">
                        @foreach ([
                            ['Utara',   $profil->batas_utara ?? null],
                            ['Selatan', $profil->batas_selatan ?? null],
                            ['Timur',   $profil->batas_timur ?? null],
                            ['Barat',   $profil->batas_barat ?? null],
                        ] as [$arah, $nilai])
                            @if($nilai)
                                <div class="flex items-start justify-between gap-3">
                                    <span class="text-xs shrink-0" style="color: var(--lembut);">{{ $arah }}</span>
                                    <span class="text-xs font-medium text-right">{{ $nilai }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if($dusun->count())
                        <p class="label-bagian mb-2.5">Terbagi Atas</p>
                        <div class="flex flex-wrap gap-2 mt-auto">
                            @foreach ($dusun as $d)
                                <span class="text-xs px-3 py-1.5 rounded-full"
                                      style="background: var(--sawah-light); color: var(--sawah-dark);">
                                    Dusun {{ $d->nama }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif

@if($statistik->count())
    <section class="pola-petak lengkung-atas" style="background: var(--kertas);">
        <div class="wadah py-10">
            <div class="reveal">
                <p class="label-bagian">Desa dalam Angka</p>
                <h2 class="font-display text-2xl font-bold mt-1.5">Gambaran Singkat</h2>
            </div>

            <div class="grid grid-cols-2 {{ $kolom }} gap-px mt-8 rounded-xl overflow-hidden"
                 style="background: var(--garis); border: 1px solid var(--garis);">
                @php
                    // ikon sederhana untuk tiap angka, agar kartunya tidak polos
                    $ikonAngka = [
                        'Jiwa Penduduk'    => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                        'Dusun'            => 'M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819',
                        'Hektar Wilayah'   => 'M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z',
                        'Kepala Keluarga'  => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
                    ];
                @endphp

                @foreach ($statistik as $s)
                    <div class="reveal-skala bg-white px-5 py-6 relative overflow-hidden"
                         style="transition-delay: {{ $loop->index * 60 }}ms;">

                        {{-- ikon samar sebagai latar, mengisi bidang kosong di kanan kartu --}}
                        @if(isset($ikonAngka[$s['label']]))
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.1" stroke="currentColor"
                                 class="absolute -right-3 -bottom-3 w-20 h-20 pointer-events-none"
                                 style="color: var(--sawah-dark); opacity: .07;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ikonAngka[$s['label']] }}" />
                            </svg>
                        @endif

                        <p class="relative font-display text-3xl font-bold" style="color: var(--sawah-dark);">
                            <span data-angka="{{ $s['angka'] }}" data-desimal="0">0</span>
                        </p>
                        <p class="relative text-xs mt-1.5" style="color: var(--lembut);">{{ $s['label'] }}</p>
                    </div>
                @endforeach
            </div>

            @if($ringkasan)
                <p class="text-xs mt-4" style="color: var(--lembut);">
                    Sumber: data kependudukan Desa {{ $profil->nama_desa ?? 'Cibiuk' }} tahun {{ $ringkasan->tahun }}.
                </p>
            @endif
        </div>
    </section>
@endif

{{-- ============ BERITA TERBARU ============ --}}
@if($beritaTerbaru->count())
    <section class="border-t" style="border-color: var(--garis);">
        <div class="wadah py-11">
            <div class="flex items-end justify-between gap-4 mb-7 reveal">
                <div>
                    <p class="label-bagian">Kabar Desa</p>
                    <h2 class="font-display text-2xl font-bold mt-1.5">Berita Terbaru</h2>
                </div>
                <a href="{{ route('berita.index') }}"
                   class="text-sm font-medium whitespace-nowrap shrink-0 hover:underline underline-offset-4"
                   style="color: var(--padi);">
                    Lihat semua &rarr;
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($beritaTerbaru as $b)
                    <a href="{{ route('berita.show', $b->slug) }}"
                       class="reveal kartu kartu-tautan kartu-aksen overflow-hidden flex flex-col"
                       style="transition-delay: {{ $loop->index * 70 }}ms;">
                        <div class="aspect-[16/9] overflow-hidden shrink-0" style="background: var(--sawah-light);">
                            @if($b->thumbnail_path)
                                <img src="{{ Storage::url($b->thumbnail_path) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-display text-3xl font-bold"
                                     style="color: var(--sawah-dark); opacity: 0.25;">DC</div>
                            @endif
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded self-start"
                                  style="background: var(--sawah-light); color: var(--sawah-dark);">
                                {{ ucfirst($b->kategori) }}
                            </span>
                            <h3 class="font-display text-base font-semibold mt-2.5 leading-snug line-clamp-2 flex-1">
                                {{ $b->judul }}
                            </h3>
                            <p class="text-xs mt-3" style="color: var(--lembut);">
                                {{ $b->tanggal_publish->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ============ AGENDA + POTENSI ============ --}}
@if($agendaTerdekat->count() || $potensiSorotan->count())
    @php
        // Bila hanya salah satu yang terisi, jangan dipaksa dua kolom
        // agar tidak menyisakan bidang kosong di sebelahnya.
        $duaKolom = $agendaTerdekat->count() && $potensiSorotan->count();
    @endphp

    <section class="pola-titik lengkung-atas" style="background: var(--kertas);">
        <div class="wadah py-11 grid {{ $duaKolom ? 'lg:grid-cols-2 gap-10 lg:gap-14' : 'gap-10' }}">

            @if($agendaTerdekat->count())
                <div>
                    <div class="flex items-end justify-between gap-4 mb-5 reveal">
                        <div>
                            <p class="label-bagian">Jadwal</p>
                            <h2 class="font-display text-xl font-bold mt-1.5">Agenda Terdekat</h2>
                        </div>
                        <a href="{{ route('agenda.index') }}" class="text-sm font-medium shrink-0 hover:underline underline-offset-4"
                           style="color: var(--padi);">Semua</a>
                    </div>

                    {{-- Disusun sebagai garis waktu agar urutan kegiatan terbaca jelas --}}
                    <div class="kartu p-5 sm:p-6">
                        <div class="garis-waktu space-y-5">
                            @foreach ($agendaTerdekat as $a)
                                <div class="titik-waktu reveal-kiri"
                                     style="transition-delay: {{ $loop->index * 70 }}ms;">

                                    <p class="text-[11px] font-semibold uppercase tracking-wide mb-1"
                                       style="color: var(--padi);">
                                        {{ $a->tanggal_mulai->translatedFormat('l, d F Y') }}
                                    </p>

                                    <h3 class="font-display text-sm font-semibold leading-snug">{{ $a->judul }}</h3>

                                    <p class="text-xs mt-1" style="color: var(--lembut);">
                                        Pukul {{ $a->tanggal_mulai->translatedFormat('H:i') }}
                                        @if($a->lokasi) &middot; {{ $a->lokasi }} @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($potensiSorotan->count())
                <div>
                    <div class="flex items-end justify-between gap-4 mb-5 reveal">
                        <div>
                            <p class="label-bagian">Ekonomi Warga</p>
                            <h2 class="font-display text-xl font-bold mt-1.5">Potensi Desa</h2>
                        </div>
                        <a href="{{ route('potensi.index') }}" class="text-sm font-medium shrink-0 hover:underline underline-offset-4"
                           style="color: var(--padi);">Semua</a>
                    </div>

                    <div class="kartu overflow-hidden">
                        @foreach ($potensiSorotan as $p)
                            <div class="reveal-kiri flex gap-4 p-4 {{ !$loop->last ? 'border-b' : '' }}"
                                 style="border-color: var(--garis); transition-delay: {{ $loop->index * 60 }}ms;">
                                <div class="w-16 h-14 rounded-lg overflow-hidden shrink-0" style="background: var(--sawah-light);">
                                    @if($p->foto_path)
                                        <img src="{{ Storage::url($p->foto_path) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center font-display text-sm font-bold"
                                             style="color: var(--sawah-dark); opacity: 0.3;">DC</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-semibold uppercase tracking-wide" style="color: var(--padi);">{{ $p->label_jenis }}</p>
                                    <h3 class="font-display text-sm font-semibold leading-snug">{{ $p->nama }}</h3>
                                    @if($p->deskripsi)
                                        <p class="text-xs mt-1 line-clamp-2" style="color: var(--lembut);">{{ $p->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif

{{--============ STRUKTUR ORGANISASI & TUPOKSI ============ --}}
@if($perangkat->count())
    <section class="pola-petak lengkung-atas" style="background: var(--kertas);">
        <div class="wadah py-11">
            <div class="reveal mb-7">
                <p class="label-bagian">Pemerintahan Desa</p>
                <h2 class="font-display text-2xl font-bold mt-1.5">Perangkat Desa dan Tugasnya</h2>
                <p class="text-sm mt-2 max-w-2xl leading-relaxed" style="color: var(--lembut);">
                    Susunan pemerintahan Desa {{ $profil->nama_desa ?? 'Cibiuk' }} beserta
                    tugas pokok masing-masing bagian.
                </p>
            </div>

            <div class="space-y-6">
                @include('partials.struktur-organisasi')
            </div>
        </div>
    </section>
@endif

{{-- ============ KANTOR DESA & JAM PELAYANAN ============ --}}
@php
    $adaKontak = $profil?->alamat_kantor || $profil?->telepon || $profil?->email;
    $jamPelayanan = $profil?->baris_jam_pelayanan ?? [];

    // Bila hanya satu kartu yang terisi, tampilkan melebar penuh
    // agar tidak menyisakan bidang kosong di sebelahnya.
    $duaKartu = $adaKontak && count($jamPelayanan);
@endphp

@if($adaKontak || count($jamPelayanan))
    <section class="border-t" style="border-color: var(--garis);">
        <div class="wadah py-11">
            <div class="reveal mb-7">
                <p class="label-bagian">Pelayanan</p>
                <h2 class="font-display text-2xl font-bold mt-1.5">Kantor Desa</h2>
            </div>

            <div class="grid {{ $duaKartu ? 'lg:grid-cols-2' : '' }} gap-5">

                {{-- Jam pelayanan --}}
                @if(count($jamPelayanan))
                    <div class="reveal-kiri kartu p-6">
                        <div class="flex items-center gap-2.5 mb-4">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg shrink-0"
                                  style="background: var(--sawah-light); color: var(--sawah-dark);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <h3 class="font-display text-base font-semibold">Jam Pelayanan</h3>
                        </div>

                        {{-- Bila kartunya melebar penuh, daftar hari dibuat dua kolom
                             supaya tidak memanjang ke bawah dengan ruang kosong di kanan. --}}
                        <ul class="{{ $duaKartu ? '' : 'sm:grid sm:grid-cols-2 sm:gap-x-10' }}">
                            @foreach ($jamPelayanan as $baris)
                                @php
                                    // "Senin – Kamis: 08.00 – 15.00" dipecah jadi hari dan jamnya
                                    $bagian = explode(':', $baris, 2);
                                    $hari = trim($bagian[0]);
                                    $jam = isset($bagian[1]) ? trim($bagian[1]) : null;
                                    $tutup = $jam && str_contains(strtolower($jam), 'tutup');
                                @endphp
                                <li class="flex items-center justify-between gap-4 py-2.5 border-b"
                                    style="border-color: var(--garis);">
                                    <span class="text-sm font-medium">{{ $hari }}</span>
                                    @if($jam)
                                        <span class="text-sm {{ $tutup ? '' : 'font-mono-tiket' }}"
                                              style="color: {{ $tutup ? '#96261F' : 'var(--lembut)' }};">
                                            {{ $jam }}
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        {{-- Ajakan menghubungi, mengisi ruang bawah kartu sekaligus
                             memberi jalan bagi warga di luar jam pelayanan. --}}
                        <p class="text-xs mt-5 leading-relaxed" style="color: var(--lembut);">
                            Di luar jam pelayanan, keluhan tetap dapat disampaikan melalui
                            <a href="{{ route('pengaduan.create') }}"
                               class="font-medium hover:underline underline-offset-4" style="color: var(--padi);">
                                layanan pengaduan
                            </a>
                            kapan saja.
                        </p>
                    </div>
                @endif

                {{-- Alamat & kontak --}}
                @if($adaKontak)
                    <div class="reveal-kanan kartu p-6" style="transition-delay: 70ms;">
                        <div class="flex items-center gap-2.5 mb-4">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg shrink-0"
                                  style="background: var(--sawah-light); color: var(--sawah-dark);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </span>
                            <h3 class="font-display text-base font-semibold">Alamat &amp; Kontak</h3>
                        </div>

                        <div class="space-y-3 text-sm">
                            @if($profil->alamat_kantor)
                                <div>
                                    <p class="text-xs mb-0.5" style="color: var(--lembut);">Alamat Kantor</p>
                                    <p class="leading-relaxed">{{ $profil->alamat_kantor }}</p>
                                </div>
                            @endif

                            @if($profil->telepon)
                                <div>
                                    <p class="text-xs mb-0.5" style="color: var(--lembut);">Telepon</p>
                                    @if($profil->link_wa)
                                        <a href="{{ $profil->link_wa }}" target="_blank" rel="noopener"
                                           class="font-medium hover:underline underline-offset-4" style="color: var(--padi);">
                                            {{ $profil->telepon }}
                                        </a>
                                    @else
                                        <p class="font-medium">{{ $profil->telepon }}</p>
                                    @endif
                                </div>
                            @endif

                            @if($profil->email)
                                <div>
                                    <p class="text-xs mb-0.5" style="color: var(--lembut);">Email</p>
                                    <a href="mailto:{{ $profil->email }}"
                                       class="font-medium hover:underline underline-offset-4" style="color: var(--padi);">
                                        {{ $profil->email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif

{{-- ============ AJAKAN PENGADUAN ============ --}}
<section class="border-t" style="border-color: var(--garis);">
    <div class="wadah py-11">
        <div class="reveal-skala relative rounded-xl overflow-hidden" style="background: var(--sawah-dark);">

            {{-- Foto desa sebagai latar, dengan lapisan hijau pekat
                 agar tulisan putih tetap terbaca jelas. --}}
            @if($profil?->foto_hero_path)
                <img src="{{ Storage::url($profil->foto_hero_path) }}" alt=""
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0"
                     style="background: linear-gradient(115deg, rgba(9,63,40,.94) 0%, rgba(9,63,40,.86) 55%, rgba(9,63,40,.62) 100%);"></div>
            @endif

            {{-- hiasan latar: pola titik dan dua lingkaran samar --}}
            <div class="pola-titik-terang absolute inset-0"></div>
            <span class="bulat-hias" style="width: 260px; height: 260px; right: -70px; top: -90px;
                  background: radial-gradient(circle, rgba(167,217,190,.16), transparent 70%);"></span>
            <span class="bulat-hias" style="width: 180px; height: 180px; left: -50px; bottom: -70px;
                  background: radial-gradient(circle, rgba(167,217,190,.12), transparent 70%);"></span>

            <div class="relative p-8 sm:p-10 grid lg:grid-cols-5 gap-8 items-center text-white">

                <div class="lg:col-span-3">
                    <p class="label-bagian" style="color: rgba(255,255,255,0.6);">Layanan Warga</p>
                    <h2 class="font-display text-2xl font-bold leading-snug mt-2">
                        Ada jalan rusak, lampu mati, atau sampah menumpuk?
                    </h2>
                    <p class="text-sm text-white/70 mt-3 leading-relaxed max-w-md">
                        Sampaikan langsung ke pemerintah desa. Setiap laporan mendapat nomor tiket
                        sehingga Anda bisa memantau perkembangannya sendiri.
                    </p>
                    <div class="flex flex-wrap gap-3 mt-6">
                        <a href="{{ route('pengaduan.create') }}"
                           class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-white transition hover:bg-white/90"
                           style="color: var(--sawah-dark);">
                            Buat Laporan
                        </a>
                        <a href="{{ route('pengaduan.lacak.form') }}"
                           class="px-5 py-2.5 rounded-lg text-sm font-medium border border-white/30 hover:bg-white/10 transition">
                            Lacak Laporan
                        </a>
                    </div>
                </div>

                @if($totalLaporan > 0)
                    <div class="lg:col-span-2 grid grid-cols-2 gap-3">
                        <div class="rounded-lg p-5" style="background: rgba(255,255,255,0.10);">
                            <p class="font-display text-3xl font-bold">
                                <span data-angka="{{ $totalLaporan }}" data-desimal="0">0</span>
                            </p>
                            <p class="text-xs text-white/60 mt-1">Laporan Masuk</p>
                        </div>
                        <div class="rounded-lg p-5" style="background: rgba(255,255,255,0.10);">
                            <p class="font-display text-3xl font-bold">
                                <span data-angka="{{ $persenSelesai }}" data-desimal="0">0</span><span class="text-lg">%</span>
                            </p>
                            <p class="text-xs text-white/60 mt-1">Sudah Ditangani</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection