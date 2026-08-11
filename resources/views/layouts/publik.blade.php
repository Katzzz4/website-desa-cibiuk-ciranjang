<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $namaDesa   = 'Desa ' . ($profilGlobal->nama_desa ?? 'Cibiuk');
        $wilayah    = $profilGlobal->wilayah_lengkap ?? 'Kecamatan Ciranjang, Kabupaten Cianjur, Jawa Barat';
        $judulIsi   = trim($__env->yieldContent('title')) ?: 'Beranda';

        // Nilai berikut dapat ditimpa tiap halaman lewat:
        //   @section('meta_judul', ...) @section('meta_deskripsi', ...) @section('meta_gambar', ...)
        $metaJudul     = trim($__env->yieldContent('meta_judul')) ?: ($judulIsi . ' — ' . $namaDesa);
        $metaDeskripsi = trim($__env->yieldContent('meta_deskripsi'))
            ?: 'Website resmi ' . $namaDesa . ', ' . $wilayah . '. Berisi profil desa, berita, agenda kegiatan, data kependudukan, serta layanan pengaduan masyarakat yang dapat dilacak.';
        $metaTipe      = trim($__env->yieldContent('meta_tipe')) ?: 'website';

        // Gambar pratinjau: dari halaman, lalu foto beranda, lalu logo desa
        $metaGambar = trim($__env->yieldContent('meta_gambar'));
        if (!$metaGambar && ($profilGlobal->foto_hero_path ?? null)) {
            $metaGambar = url(Storage::url($profilGlobal->foto_hero_path));
        }
        if (!$metaGambar && ($profilGlobal->logo_path ?? null)) {
            $metaGambar = url(Storage::url($profilGlobal->logo_path));
        }
    @endphp

    <title>{{ $judulIsi }} — {{ $namaDesa }}</title>
    <meta name="description" content="{{ $metaDeskripsi }}">

    @if($profilGlobal->logo_path ?? null)
        <link rel="icon" href="{{ Storage::url($profilGlobal->logo_path) }}">
    @endif

    {{-- ============ PRATINJAU SAAT DIBAGIKAN ============
         Dipakai WhatsApp, Facebook, dan aplikasi lain untuk menampilkan
         judul, keterangan, dan gambar ketika tautan situs ini dikirim. --}}
    <meta property="og:site_name" content="{{ $namaDesa }}">
    <meta property="og:type" content="{{ $metaTipe }}">
    <meta property="og:title" content="{{ $metaJudul }}">
    <meta property="og:description" content="{{ $metaDeskripsi }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="id_ID">
    @if($metaGambar)
        <meta property="og:image" content="{{ $metaGambar }}">
        <meta property="og:image:alt" content="{{ $metaJudul }}">
    @endif

    <meta name="twitter:card" content="{{ $metaGambar ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $metaJudul }}">
    <meta name="twitter:description" content="{{ $metaDeskripsi }}">
    @if($metaGambar)
        <meta name="twitter:image" content="{{ $metaGambar }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================================
           GAYA: Bersih & Institusional
           Nama variabel lama dipertahankan supaya seluruh halaman
           yang sudah ada ikut menyesuaikan tanpa perlu diubah satu per satu.
           ============================================================ */
        :root {
            --sawah-dark:   #0E5C3A;   /* hijau utama: header, tombol, band gelap */
            --sawah-darker: #093F28;
            --sawah-light:  #EDF5F1;   /* hijau muda: latar lembut, badge */
            --kertas:       #F7F8F7;   /* latar halaman, abu sangat terang */
            --padi:         #157F4F;   /* aksen (dulu emas, kini hijau segar) */
            --padi-light:   #A7D9BE;   /* aksen terang di atas latar hijau tua */
            --talang:       #157F4F;   /* aksen sekunder, disamakan agar konsisten */
            --ink:          #171A18;
            --lembut:       #5D635F;   /* teks sekunder */
            --garis:        #E3E7E4;   /* garis pembatas */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--kertas);
            color: var(--ink);
            overflow-x: clip; 
        }

        /* institusional = seluruhnya sans-serif, tanpa serif dekoratif */
        .font-display {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
        }
        .font-mono-tiket { font-family: 'JetBrains Mono', monospace; letter-spacing: 0.02em; }

        /* tekstur garis dihilangkan demi tampilan yang lebih bersih */
        .terrace-texture { background-image: none; }

        /* ---- Komponen dasar ---- */
        .label-bagian {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--padi);
        }
        .kartu {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: 12px;
        }
        .kartu-tautan { transition: border-color .18s ease, box-shadow .18s ease; }
        .kartu-tautan:hover {
            border-color: #C6D2CB;
            box-shadow: 0 6px 18px -10px rgba(14,92,58,0.28);
        }
        .tombol-utama {
            background: var(--sawah-dark);
            color: #fff;
            transition: background .18s ease;
        }
        .tombol-utama:hover { background: var(--sawah-darker); }
        .tombol-garis {
            border: 1px solid var(--garis);
            color: var(--ink);
            transition: background .18s ease, border-color .18s ease;
        }
        .tombol-garis:hover { background: var(--kertas); border-color: #C6D2CB; }

        /* menyelaraskan kartu lama (rounded-2xl + border-black/5) dengan gaya baru */
        .border-black\/5 { border-color: var(--garis); }

        /* ---- Motif anyaman (dipertahankan sebagai aksen khas desa) ---- */
        .motif-anyaman {
            background-image:
                repeating-linear-gradient(45deg,  rgba(14,92,58,0.13) 0 5px, transparent 5px 11px),
                repeating-linear-gradient(-45deg, rgba(14,92,58,0.13) 0 5px, transparent 5px 11px);
        }
        .motif-anyaman-terang {
            background-image:
                repeating-linear-gradient(45deg,  rgba(255,255,255,0.16) 0 5px, transparent 5px 11px),
                repeating-linear-gradient(-45deg, rgba(255,255,255,0.16) 0 5px, transparent 5px 11px);
        }
        .pemisah-anyaman { display: flex; align-items: center; gap: 14px; }
        .pemisah-anyaman::before,
        .pemisah-anyaman::after {
            content: ''; flex: 1; height: 1px; background: var(--garis);
        }
        .pemisah-anyaman > span {
            width: 8px; height: 8px; transform: rotate(45deg);
            background: var(--padi); border-radius: 2px; flex-shrink: 0;
        }

        /* ---- Animasi halus ---- */
        .reveal { opacity: 0; transform: translateY(16px); transition: opacity .55s ease, transform .55s ease; }
        .reveal.tampil { opacity: 1; transform: none; }
        .kartu-angkat { transition: transform .2s ease, box-shadow .2s ease; }
        .kartu-angkat:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -14px rgba(14,92,58,0.32); }

        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
            .kartu-angkat { transition: none; }
            .kartu-angkat:hover { transform: none; }
        }

        #mobile-nav { transition: max-height 0.25s ease, opacity 0.2s ease; }

        /* ---- Wadah isi halaman ----
           Lebarnya mengikuti navigasi agar tepi kiri-kanan sejajar
           dari bagian atas sampai bawah halaman. --------------------- */
        .wadah {
            width: 100%;
            margin-inline: auto;
            max-width: 1536px;
            padding-inline: 20px;
        }
        @media (min-width: 640px)  { .wadah { padding-inline: 32px; } }
        @media (min-width: 1024px) { .wadah { padding-inline: 56px; } }
        @media (min-width: 1280px) { .wadah { padding-inline: 80px; } }

        /* Navigasi sengaja tanpa batas lebar agar logo menempel ke tepi kiri
           dan tombol ke tepi kanan, berapa pun lebar layarnya. */
        .wadah-nav {
            width: 100%;
            max-width: none;
            padding-inline: 20px;
        }
        @media (min-width: 640px)  { .wadah-nav { padding-inline: 28px; } }
        @media (min-width: 1024px) { .wadah-nav { padding-inline: 40px; } }
        @media (min-width: 1280px) { .wadah-nav { padding-inline: 52px; } }
        @media (min-width: 1536px) { .wadah-nav { padding-inline: 64px; } }

        /* Blok teks panjang tetap dibatasi agar nyaman dibaca,
           karena baris yang terlalu lebar melelahkan mata. */
        .teks-baca { max-width: 74ch; }

        /* ---- Tautan pada navigasi ---- */
        .tautan-nav {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 4px;
            margin: 0 clamp(10px, 1.1vw, 20px);
            white-space: nowrap;
            color: rgba(255,255,255,.8);
            font-weight: 500;
            transition: color .18s ease;
            background: none;
            border: 0;
            cursor: pointer;
            font-size: inherit;
            font-family: inherit;
            text-shadow: 0 1px 8px rgba(0,0,0,.25);
        }
        .tautan-nav:hover { color: #fff; }

        /* garis penanda halaman yang sedang dibuka */
        .tautan-nav::after {
            content: '';
            position: absolute;
            left: 0; right: 0; bottom: -3px;
            height: 3px;
            border-radius: 2px;
            background: #fff;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform .22s ease;
        }
        .tautan-nav:hover::after { transform: scaleX(.4); }
        .tautan-nav.aktif { color: #fff; font-weight: 700; }
        .tautan-nav.aktif::after { transform: scaleX(1); }

        @media (prefers-reduced-motion: reduce) {
            .tautan-nav, .tautan-nav::after { transition: none; }
        }

        /* ---- Header transparan di atas foto hero ---- */
        .header-tembus {
            /* gradasi tipis agar tulisan menu tetap terbaca di atas foto terang */
            background: linear-gradient(180deg, rgba(7,46,29,.55) 0%, rgba(7,46,29,.18) 60%, transparent 100%);
        }
        .header-tembus #mobile-nav { background: var(--sawah-darker); }

        /* ============================================================
           ANIMASI
           Semua animasi di bawah ini dimatikan otomatis bila pengguna
           menyetel perangkatnya untuk mengurangi gerakan.
           ============================================================ */

        /* ---- Foto hero bergerak sangat perlahan ----
           Memberi kesan hidup tanpa mengganggu keterbacaan teks. */
        @keyframes zoom-perlahan {
            from { transform: scale(1);    }
            to   { transform: scale(1.09); }
        }
        .foto-hero { animation: zoom-perlahan 22s ease-out forwards; }

        /* ---- Isi hero muncul bertahap ---- */
        @keyframes naik-masuk {
            from { opacity: 0; transform: translateY(26px); }
            to   { opacity: 1; transform: none; }
        }
        .anim-hero {
            opacity: 0;
            animation: naik-masuk .85s cubic-bezier(.22,.68,.36,1) forwards;
        }
        .tunda-1 { animation-delay: .12s; }
        .tunda-2 { animation-delay: .30s; }
        .tunda-3 { animation-delay: .52s; }
        .tunda-4 { animation-delay: .72s; }
        .tunda-5 { animation-delay: .92s; }

        /* ---- Judul hero: tiap baris tersingkap dari bawah ---- */
        .baris-judul {
            display: block;
            overflow: hidden;
        }
        .baris-judul > span {
            display: block;
            transform: translateY(105%);
            animation: singkap-baris .95s cubic-bezier(.19,.85,.28,1) forwards;
        }
        @keyframes singkap-baris {
            to { transform: none; }
        }

        /* ---- Garis aksen yang melebar ---- */
        @keyframes lebar-masuk {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }
        .garis-hero {
            transform-origin: center;
            animation: lebar-masuk .9s cubic-bezier(.22,.68,.36,1) forwards;
            transform: scaleX(0);
        }

        /* ---- Varian munculnya isi saat digulir ---- */
        .reveal-kiri  { opacity: 0; transform: translateX(-26px); transition: opacity .6s ease, transform .6s ease; }
        .reveal-kanan { opacity: 0; transform: translateX(26px);  transition: opacity .6s ease, transform .6s ease; }
        .reveal-skala { opacity: 0; transform: scale(.94);        transition: opacity .6s ease, transform .6s ease; }
        .reveal-kiri.tampil, .reveal-kanan.tampil, .reveal-skala.tampil { opacity: 1; transform: none; }

        @media (prefers-reduced-motion: reduce) {
            .foto-hero,
            .anim-hero,
            .baris-judul > span,
            .garis-hero { animation: none !important; }
            .anim-hero { opacity: 1; }
            .baris-judul > span { transform: none; }
            .garis-hero { transform: none; }
            .reveal-kiri, .reveal-kanan, .reveal-skala {
                opacity: 1; transform: none; transition: none;
            }
        }

        /* ============================================================
           DEKORASI LATAR
           Motif samar yang membuat bidang kosong tidak terasa hampa,
           tanpa mengganggu keterbacaan isi di atasnya.
           ============================================================ */

        /* Pola titik halus, untuk latar terang */
        .pola-titik {
            background-image: radial-gradient(circle, rgba(14,92,58,.10) 1.1px, transparent 1.1px);
            background-size: 22px 22px;
        }

        /* Pola garis miring tipis, mengingatkan pada petak sawah */
        .pola-petak {
            background-image: repeating-linear-gradient(
                -45deg,
                rgba(14,92,58,.045) 0px, rgba(14,92,58,.045) 1px,
                transparent 1px, transparent 14px
            );
        }

        /* Pola titik untuk latar hijau tua */
        .pola-titik-terang {
            background-image: radial-gradient(circle, rgba(255,255,255,.09) 1.1px, transparent 1.1px);
            background-size: 24px 24px;
        }

        /* Lingkaran besar samar sebagai pengisi bidang kosong */
        .bulat-hias {
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        /* Pemisah bermotif anyaman antar bagian */
        .pemisah-motif {
            height: 10px;
            background-image:
                repeating-linear-gradient(45deg,  rgba(14,92,58,.14) 0 5px, transparent 5px 11px),
                repeating-linear-gradient(-45deg, rgba(14,92,58,.14) 0 5px, transparent 5px 11px);
        }

        /* Aksen garis pada sisi kiri kartu */
        .kartu-aksen { position: relative; overflow: hidden; }
        .kartu-aksen::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--padi);
            transform: scaleY(0);
            transform-origin: top;
            transition: transform .28s ease;
        }
        .kartu-aksen:hover::before { transform: scaleY(1); }

        @media (prefers-reduced-motion: reduce) {
            .kartu-aksen::before { transition: none; }
        }

        /* ---- Pemisah melengkung antar bagian ----
           Digambar dengan CSS agar tidak menambah permintaan berkas. */
        .lengkung-atas {
            position: relative;
            overflow: hidden;   /* tambahkan baris ini */
        }
        .lengkung-atas::before {
            content: '';
            position: absolute;
            left: -5%;
            right: -5%;
            top: -34px;
            height: 68px;
            border-radius: 50% / 100% 100% 0 0;
            background: inherit;
            pointer-events: none;
        }

        /* ---- Garis waktu agenda ---- */
        .garis-waktu { position: relative; padding-left: 30px; }
        .garis-waktu::before {
            content: '';
            position: absolute;
            left: 8px; top: 6px; bottom: 6px;
            width: 2px;
            background: linear-gradient(180deg, var(--padi) 0%, var(--garis) 100%);
            border-radius: 2px;
        }
        .titik-waktu { position: relative; }
        .titik-waktu::before {
            content: '';
            position: absolute;
            left: -30px; top: 14px;
            width: 12px; height: 12px;
            border-radius: 999px;
            background: #fff;
            border: 3px solid var(--padi);
            box-shadow: 0 0 0 3px var(--kertas);
        }
        .titik-waktu:first-child::before { background: var(--padi); }

        /* ---- Penanda gulir pada hero ---- */
        @keyframes turun-naik {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(5px); }
        }
        .anim-turun { animation: turun-naik 1.9s ease-in-out infinite; }
        @media (prefers-reduced-motion: reduce) { .anim-turun { animation: none; } }

        /* ---- Judul hero ----
           Di layar sempit baris kedua boleh membungkus agar tidak terpotong. */
        @media (max-width: 640px) {
            .hero-penuh h1 span { white-space: normal !important; }
        }

        /* ---- Hero satu layar penuh ---- */
        .hero-penuh {
            min-height: 100vh;
            min-height: 100svh;   /* memperhitungkan bilah alamat di ponsel */
        }
        @media (max-width: 767px) {
            /* Di ponsel hero tidak dipaksa satu layar penuh
               agar isi di bawahnya tetap terlihat tanpa menggulir jauh. */
            .hero-penuh { min-height: 0; }
        }

        /* Menu turun dibuka dan ditutup lewat kelas .terbuka yang diatur JavaScript,
           bukan :hover atau :focus-within. Dengan :focus-within, menu tetap terbuka
           setelah tombolnya diklik meski kursor sudah berpindah. */
        .nav-group > .nav-panel { display: none; }
        .nav-group.terbuka > .nav-panel { display: block; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    @php
        // Halaman dengan hero satu layar penuh memakai header transparan
        // yang menumpang di atas foto. Diaktifkan lewat @section('header-transparan', 'ya')
        $headerTembus = trim($__env->yieldContent('header-transparan')) === 'ya';
    @endphp

    <header class="text-white z-30 {{ $headerTembus ? 'absolute inset-x-0 top-0 header-tembus' : 'terrace-texture sticky top-0' }}"
            style="{{ $headerTembus ? '' : 'background: var(--sawah-dark);' }}">
        <div class="wadah-nav py-4 md:py-6 flex items-center justify-between gap-6">

            <a href="{{ route('beranda') }}" class="flex items-center gap-3 shrink-0">
                @if(($profilGlobal->logo_path ?? null))
                    <img src="{{ Storage::url($profilGlobal->logo_path) }}" alt="Logo Desa {{ $profilGlobal->nama_desa ?? 'Cibiuk' }}"
                         class="w-12 h-12 md:w-16 md:h-16 object-contain shrink-0"
                         style="filter: drop-shadow(0 2px 6px rgba(0,0,0,.28));">
                @else
                    <span class="w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center font-display font-bold text-lg shrink-0"
                          style="background: var(--padi); color: var(--sawah-darker);">DC</span>
                @endif
                <span class="whitespace-nowrap leading-tight">
                    <span class="font-display font-bold block text-lg md:text-2xl"
                          style="text-shadow: 0 1px 8px rgba(0,0,0,.3);">
                        Desa {{ $profilGlobal->nama_desa ?? 'Cibiuk' }}
                    </span>
                    <span class="text-xs md:text-sm text-white/70 hidden sm:block">
                        {{ $profilGlobal ? 'Kabupaten ' . $profilGlobal->kabupaten : 'Kabupaten Cianjur' }}
                    </span>
                </span>
            </a>

            @php
                $aktifProfil = request()->routeIs('profil.*') || request()->routeIs('infografis.*') || request()->routeIs('potensi.*') || request()->routeIs('peta.*');
                $aktifInfo = request()->routeIs('berita.*') || request()->routeIs('agenda.*') || request()->routeIs('galeri.*') || request()->routeIs('dokumen.*') || request()->routeIs('transparansi.*');
            @endphp

            <nav class="hidden md:flex items-center text-base lg:text-[17px]">
                <a href="{{ route('beranda') }}"
                   class="tautan-nav {{ request()->routeIs('beranda') ? 'aktif' : '' }}">
                    Beranda
                </a>

                <div class="nav-group relative">
                    <button type="button" aria-expanded="false" aria-haspopup="true"
                            class="tautan-nav {{ $aktifProfil ? 'aktif' : '' }}">
                        Profil
                        <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="nav-panel absolute left-0 top-full pt-2 z-40">
                        <div class="bg-white rounded-xl shadow-xl py-2.5 min-w-[240px]">
                            <a href="{{ route('profil.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Profil Desa</a>
                            <a href="{{ route('infografis.penduduk') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Infografis Penduduk</a>
                            <a href="{{ route('potensi.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Potensi Desa</a>
                            <a href="{{ route('peta.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Peta Wilayah</a>
                        </div>
                    </div>
                </div>

                <div class="nav-group relative">
                    <button type="button" aria-expanded="false" aria-haspopup="true"
                            class="tautan-nav {{ $aktifInfo ? 'aktif' : '' }}">
                        Informasi
                        <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="nav-panel absolute left-0 top-full pt-2 z-40">
                        <div class="bg-white rounded-xl shadow-xl py-2.5 min-w-[240px]">
                            <a href="{{ route('berita.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Berita &amp; Pengumuman</a>
                            <a href="{{ route('agenda.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Agenda Kegiatan</a>
                            <a href="{{ route('galeri.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Galeri</a>
                            <a href="{{ route('dokumen.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Dokumen Desa</a>
                            <a href="{{ route('transparansi.index') }}" class="block px-5 py-3 text-[15px] text-[var(--ink)] hover:bg-black/[0.04] whitespace-nowrap">Transparansi Pengaduan</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('pengaduan.create') }}"
                   class="tautan-nav {{ request()->routeIs('pengaduan.create') ? 'aktif' : '' }}">
                    Pengaduan
                </a>

                <a href="{{ route('pencarian.index') }}"
                   title="Cari informasi desa" aria-label="Cari"
                   class="ml-3 lg:ml-5 w-11 h-11 flex items-center justify-center rounded-full transition
                          {{ request()->routeIs('pencarian.*') ? 'bg-white/15 text-white' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.9" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </a>

                <a href="{{ route('pengaduan.lacak.form') }}"
                   class="ml-3 px-6 py-3 rounded-full text-[15px] font-semibold whitespace-nowrap transition
                          bg-white hover:bg-white/90"
                   style="color: var(--sawah-dark);">
                    Lacak Laporan
                </a>
            </nav>

            <button id="nav-toggle" class="md:hidden w-11 h-11 flex items-center justify-center rounded-lg hover:bg-white/10 shrink-0">
                <svg id="icon-open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav id="mobile-nav" class="md:hidden max-h-0 overflow-hidden opacity-0">
            <div class="wadah-nav py-4 border-t border-white/10 space-y-4">

                {{-- kolom pencarian ditaruh paling atas agar mudah dijangkau dari HP --}}
                <form method="GET" action="{{ route('pencarian.index') }}" class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="search" name="q" placeholder="Cari informasi desa..."
                           class="w-full rounded-lg border-white/20 bg-white/10 text-white placeholder-white/40
                                  text-sm pl-10 pr-3 py-2.5">
                </form>

                <a href="{{ route('beranda') }}" class="block py-2 text-sm text-white/85">Beranda</a>

                <div>
                    <p class="text-[10px] uppercase tracking-widest text-white/40 mb-1">Profil</p>
                    <a href="{{ route('profil.index') }}" class="block py-2 text-sm text-white/85">Profil Desa</a>
                    <a href="{{ route('infografis.penduduk') }}" class="block py-2 text-sm text-white/85">Infografis Penduduk</a>
                    <a href="{{ route('potensi.index') }}" class="block py-2 text-sm text-white/85">Potensi Desa</a>
                    <a href="{{ route('peta.index') }}" class="block py-2 text-sm text-white/85">Peta Wilayah</a>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-widest text-white/40 mb-1">Informasi</p>
                    <a href="{{ route('berita.index') }}" class="block py-2 text-sm text-white/85">Berita &amp; Pengumuman</a>
                    <a href="{{ route('agenda.index') }}" class="block py-2 text-sm text-white/85">Agenda Kegiatan</a>
                    <a href="{{ route('galeri.index') }}" class="block py-2 text-sm text-white/85">Galeri</a>
                    <a href="{{ route('dokumen.index') }}" class="block py-2 text-sm text-white/85">Dokumen Desa</a>
                    <a href="{{ route('transparansi.index') }}" class="block py-2 text-sm text-white/85">Transparansi Pengaduan</a>
                </div>

                <div class="pt-1 border-t border-white/10 space-y-2">
                    <a href="{{ route('pengaduan.create') }}" class="block py-2 text-sm text-white/85">Buat Pengaduan</a>
                    <a href="{{ route('pengaduan.lacak.form') }}"
                       class="block text-center py-2.5 rounded-full border border-white/25 text-sm">Lacak Laporan</a>
                </div>
            </div>
        </nav>
    </header>

    {{-- Halaman biasa memakai lebar terbatas.
         Halaman yang butuh section selebar layar (mis. beranda) menimpanya
         dengan @section('main-class', '') --}}
    <main class="flex-1 @yield('main-class', 'wadah py-10')">
        @yield('content')
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="terrace-texture text-white mt-16" style="background: var(--sawah-dark);">
        <div class="h-2 motif-anyaman-terang"></div>

        <div class="wadah py-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Identitas --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2.5 mb-3">
                    @if(($profilGlobal->logo_path ?? null))
                        <img src="{{ Storage::url($profilGlobal->logo_path) }}" alt="" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <span class="w-10 h-10 rounded-full flex items-center justify-center font-display font-semibold text-sm"
                              style="background: var(--padi); color: var(--sawah-darker);">DC</span>
                    @endif
                    <div>
                        <p class="font-display text-lg font-semibold leading-tight">Desa {{ $profilGlobal->nama_desa ?? 'Cibiuk' }}</p>
                        <p class="text-xs text-white/55">{{ $profilGlobal->wilayah_lengkap ?? 'Ciranjang, Cianjur, Jawa Barat' }}</p>
                    </div>
                </div>

                @if($profilGlobal?->visi)
                    <p class="text-sm text-white/70 leading-relaxed max-w-md">&ldquo;{{ $profilGlobal->visi }}&rdquo;</p>
                @endif

                <div class="mt-5 space-y-1.5 text-sm text-white/70">
                    @if($profilGlobal?->alamat_kantor)
                        <p>{{ $profilGlobal->alamat_kantor }}</p>
                    @endif
                    @if($profilGlobal?->telepon)
                        <p>Telepon: {{ $profilGlobal->telepon }}</p>
                    @endif
                    @if($profilGlobal?->email)
                        <p>Email: {{ $profilGlobal->email }}</p>
                    @endif
                </div>
            </div>

            {{-- Tautan cepat --}}
            <div>
                <p class="text-[11px] uppercase tracking-widest mb-3" style="color: var(--padi-light);">Tautan Cepat</p>
                <ul class="space-y-2 text-sm text-white/70">
                    <li><a href="{{ route('profil.index') }}" class="hover:text-white transition">Profil Desa</a></li>
                    <li><a href="{{ route('infografis.penduduk') }}" class="hover:text-white transition">Infografis Penduduk</a></li>
                    <li><a href="{{ route('potensi.index') }}" class="hover:text-white transition">Potensi Desa</a></li>
                    <li><a href="{{ route('berita.index') }}" class="hover:text-white transition">Berita &amp; Pengumuman</a></li>
                    <li><a href="{{ route('dokumen.index') }}" class="hover:text-white transition">Dokumen Desa</a></li>
                </ul>
            </div>

            {{-- Layanan & jam --}}
            <div>
                <p class="text-[11px] uppercase tracking-widest mb-3" style="color: var(--padi-light);">Layanan</p>
                <ul class="space-y-2 text-sm text-white/70 mb-5">
                    <li><a href="{{ route('pengaduan.create') }}" class="hover:text-white transition">Buat Pengaduan</a></li>
                    <li><a href="{{ route('pengaduan.lacak.form') }}" class="hover:text-white transition">Lacak Laporan</a></li>
                    <li><a href="{{ route('agenda.index') }}" class="hover:text-white transition">Agenda Kegiatan</a></li>
                </ul>

            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="wadah py-5 text-xs text-white/45 text-center sm:text-left">
                © {{ date('Y') }} Pemerintah Desa {{ $profilGlobal->nama_desa ?? 'Cibiuk' }}, {{ $profilGlobal->wilayah_lengkap ?? 'Kecamatan Ciranjang, Kabupaten Cianjur' }}
            </div>
        </div>
    </footer>

    <script>
        // ---- menu mobile ----

        // ---- Menu turun pada navigasi ----
        (function () {
            const grup = Array.from(document.querySelectorAll('.nav-group'));
            if (!grup.length) return;

            const bukaSatu = (g) => {
                // hanya satu menu boleh terbuka pada satu waktu
                grup.forEach(lain => {
                    const aktif = lain === g;
                    lain.classList.toggle('terbuka', aktif);
                    const tombol = lain.querySelector('button');
                    if (tombol) tombol.setAttribute('aria-expanded', aktif ? 'true' : 'false');
                });
            };

            const tutupSemua = () => {
                grup.forEach(g => {
                    g.classList.remove('terbuka');
                    const tombol = g.querySelector('button');
                    if (tombol) tombol.setAttribute('aria-expanded', 'false');
                });
            };

            grup.forEach(g => {
                const tombol = g.querySelector('button');

                // klik: berguna untuk layar sentuh dan papan ketik
                tombol?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    g.classList.contains('terbuka') ? tutupSemua() : bukaSatu(g);
                });

                // kursor masuk membuka, kursor keluar menutup
                g.addEventListener('mouseenter', () => bukaSatu(g));
                g.addEventListener('mouseleave', () => tutupSemua());
            });

            // klik di luar menu, atau tombol Escape, menutup semuanya
            document.addEventListener('click', tutupSemua);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') tutupSemua();
            });
        })();

        const navToggle = document.getElementById('nav-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');

        navToggle.addEventListener('click', () => {
            const isOpen = mobileNav.classList.contains('nav-open');
            if (isOpen) {
                mobileNav.style.maxHeight = '0px';
                mobileNav.classList.remove('opacity-100', 'nav-open');
                mobileNav.classList.add('opacity-0');
            } else {
                mobileNav.style.maxHeight = mobileNav.scrollHeight + 'px';
                mobileNav.classList.add('opacity-100', 'nav-open');
                mobileNav.classList.remove('opacity-0');
            }
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });

        // ---- animasi muncul saat di-scroll ----
        const kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!kurangiGerak && 'IntersectionObserver' in window) {
            const pengamat = new IntersectionObserver((entri) => {
                entri.forEach((e) => {
                    if (e.isIntersecting) {
                        e.target.classList.add('tampil');
                        pengamat.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12 });

            document.querySelectorAll('.reveal, .reveal-kiri, .reveal-kanan, .reveal-skala').forEach((el) => {
                // Isi yang sudah tampak sejak awal langsung ditampilkan,
                // agar tidak sempat terlihat kosong saat halaman dibuka.
                const posisi = el.getBoundingClientRect();
                if (posisi.top < window.innerHeight * 0.92) {
                    el.classList.add('tampil');
                    return;
                }
                pengamat.observe(el);
            });
        } else {
            document.querySelectorAll('.reveal, .reveal-kiri, .reveal-kanan, .reveal-skala').forEach((el) => el.classList.add('tampil'));
        }

        // ---- angka statistik menghitung naik ----
        function hitungNaik(el) {
            const target = parseFloat(el.dataset.angka || '0');
            const desimal = parseInt(el.dataset.desimal || '0', 10);
            const durasi = 1100;
            const mulai = performance.now();

            function langkah(waktu) {
                const progres = Math.min((waktu - mulai) / durasi, 1);
                // easing supaya melambat di akhir
                const nilai = target * (1 - Math.pow(1 - progres, 3));
                el.textContent = nilai.toLocaleString('id-ID', {
                    minimumFractionDigits: desimal,
                    maximumFractionDigits: desimal,
                });
                if (progres < 1) requestAnimationFrame(langkah);
            }
            requestAnimationFrame(langkah);
        }

        const angkaEl = document.querySelectorAll('[data-angka]');

        if (!kurangiGerak && 'IntersectionObserver' in window) {
            const pengamatAngka = new IntersectionObserver((entri) => {
                entri.forEach((e) => {
                    if (e.isIntersecting) {
                        hitungNaik(e.target);
                        pengamatAngka.unobserve(e.target);
                    }
                });
            }, { threshold: 0.5 });

            angkaEl.forEach((el) => pengamatAngka.observe(el));
        } else {
            angkaEl.forEach((el) => {
                const d = parseInt(el.dataset.desimal || '0', 10);
                el.textContent = parseFloat(el.dataset.angka || '0').toLocaleString('id-ID', {
                    minimumFractionDigits: d, maximumFractionDigits: d,
                });
            });
        }
    </script>


</body>
</html>