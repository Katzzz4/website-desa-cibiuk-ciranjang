<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        // Diambil langsung di sini karena View::composer profilGlobal
        // hanya dipasang untuk layout publik.
        $profilDesa = \App\Models\ProfilDesa::first();
        $namaDesa = 'Desa ' . ($profilDesa->nama_desa ?? 'Cibiuk');
    @endphp

    <title>@yield('title', 'Dashboard') — {{ $namaDesa }}</title>

    {{-- logo desa sebagai ikon tab, agar tab dashboard mudah dikenali --}}
    @if($profilDesa->logo_path ?? null)
        <link rel="icon" href="{{ Storage::url($profilDesa->logo_path) }}">
    @endif

    {{-- halaman dashboard tidak perlu muncul di hasil pencarian --}}
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================================
           GAYA: Bersih & Institusional — disamakan dengan sisi publik.
           Nama variabel lama dipertahankan agar seluruh halaman admin
           ikut menyesuaikan tanpa perlu diubah satu per satu.
           ============================================================ */
        :root {
            --sawah-dark:   #0E5C3A;
            --sawah-darker: #093F28;
            --sawah-light:  #EDF5F1;
            --kertas:       #F7F8F7;
            --padi:         #157F4F;
            --padi-light:   #A7D9BE;
            --talang:       #157F4F;
            --ink:          #171A18;
            --lembut:       #5D635F;
            --garis:        #E3E7E4;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--kertas);
            color: var(--ink);
        }

        /* institusional = seluruhnya sans-serif */
        .font-display {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
        }
        .font-mono-tiket { font-family: 'JetBrains Mono', monospace; letter-spacing: 0.02em; }

        /* tekstur garis dihilangkan demi tampilan yang lebih bersih */
        .terrace-texture { background-image: none; }

        /* menyelaraskan kartu lama dengan gaya baru */
        .border-black\/5 { border-color: var(--garis); }

        /* ---- Badge dusun: nuansa tenang, tetap saling dibedakan ---- */
        .badge-dusun-sukamaju   { background: #E7F3EC; color: #0E5C3A; }
        .badge-dusun-pasirhonje { background: #FEF6E7; color: #92600E; }
        .badge-dusun-kepuh      { background: #E8F0FB; color: #1B4B8F; }

        /* ---- Badge status: warna sama persis dengan halaman publik ---- */
        .badge-status-menunggu { background: #FEF6E7; color: #92600E; }
        .badge-status-diproses { background: #E8F0FB; color: #1B4B8F; }
        .badge-status-selesai  { background: #E7F3EC; color: #0E5C3A; }
        .badge-status-ditolak  { background: #FBEAEA; color: #96261F; }

        /* ---- Tombol aksi pada tabel (Edit, Hapus, dll) ---- */
        .btn-aksi {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            line-height: 1;
            white-space: nowrap;
            background: #fff;
            border: 1px solid var(--garis);
            color: var(--talang);
            transition: background .15s ease, border-color .15s ease, color .15s ease;
        }
        .btn-aksi:hover { background: var(--sawah-light); border-color: #C6D2CB; }

        .btn-aksi-netral { color: var(--lembut); }
        .btn-aksi-netral:hover { background: var(--kertas); border-color: #C9CFCB; }

        .btn-aksi-hapus { color: #C0392B; }
        .btn-aksi-hapus:hover { background: #FBEAEA; border-color: #EFC4C0; }

        @media (prefers-reduced-motion: reduce) {
            .btn-aksi { transition: none; }
        }

        /* ---- Tombol pada formulir ---- */
        .tombol-simpan {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: var(--sawah-dark);
            border: 1px solid var(--sawah-dark);
            transition: background .15s ease, border-color .15s ease;
        }
        .tombol-simpan:hover { background: var(--sawah-darker); border-color: var(--sawah-darker); }

        .tombol-batal {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            color: var(--lembut);
            background: #fff;
            border: 1px solid var(--garis);
            transition: background .15s ease, border-color .15s ease, color .15s ease;
        }
        .tombol-batal:hover { background: var(--kertas); border-color: #C9CFCB; color: var(--ink); }

        /* ---- Kolom isian: tampilan & fokus seragam di seluruh dashboard ---- */
        main input:not([type="file"]):not([type="checkbox"]):not([type="radio"]),
        main select,
        main textarea {
            border-color: var(--garis);
            border-radius: 10px;
            font-size: 14px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        main input:not([type="file"]):not([type="checkbox"]):not([type="radio"]):focus,
        main select:focus,
        main textarea:focus {
            outline: none;
            border-color: var(--padi);
            box-shadow: 0 0 0 3px rgba(21,127,79,0.12);
        }
        main input[type="file"] {
            font-size: 13px;
            color: var(--lembut);
        }
        main input[type="file"]::file-selector-button {
            margin-right: 12px;
            padding: 7px 14px;
            border-radius: 8px;
            border: 1px solid var(--garis);
            background: #fff;
            color: var(--talang);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }
        main input[type="file"]::file-selector-button:hover {
            background: var(--sawah-light);
            border-color: #C6D2CB;
        }

        @media (prefers-reduced-motion: reduce) {
            .tombol-simpan, .tombol-batal { transition: none; }
        }

        #sidebar { transition: transform 0.25s ease; }
        #sidebar-backdrop { display: none; }
        @media (max-width: 767px) {
            #sidebar { position: fixed; inset: 0 auto 0 0; transform: translateX(-100%); z-index: 50; }
            #sidebar.sidebar-open { transform: translateX(0); }
            #sidebar-backdrop.backdrop-open { display: block; }
        }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- BACKDROP (mobile only) --}}
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-40" onclick="toggleSidebar(false)"></div>

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="terrace-texture w-64 shrink-0 min-h-screen text-white flex flex-col" style="background: var(--sawah-dark);">
        <div class="px-6 py-6 border-b border-white/10 flex items-center justify-between">
            <div>
                <p class="font-display text-lg font-semibold leading-tight">Desa Cibiuk</p>
                <p class="text-xs text-white/60 mt-0.5">Kec. Ciranjang · Kab. Cianjur</p>
            </div>
            <button class="md:hidden text-white/70" onclick="toggleSidebar(false)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto text-sm">
            @php
                // Jumlah laporan yang belum diverifikasi, ditampilkan sebagai penanda
                // di samping menu. Mengikuti pembatasan dusun untuk akun Kepala Dusun.
                $laporanBaru = \App\Models\Laporan::untukPengguna(auth()->user())
                    ->where('status', 'menunggu')
                    ->count();

                // Menu dikelompokkan agar sidebar mudah dipindai meski isinya banyak.
                // 'cocok' dipakai untuk menandai menu yang sedang dibuka.
                $ikon = [
                    'dashboard'  => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
                    'pengaduan'  => 'M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z',
                    'peta'       => 'M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z',
                    'berita'     => 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z',
                    'agenda'     => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
                    'galeri'     => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
                    'dokumen'    => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
                    'profil'     => 'M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819',
                    'organisasi' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
                    'infografis' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
                    'potensi'    => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
                    'akun'       => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
                ];

                $grup = [
                    [
                        'judul' => null,
                        'menu' => [
                            ['Dashboard', route('admin.dashboard'), 'admin.dashboard', $ikon['dashboard']],
                        ],
                    ],
                    [
                        'judul' => 'Pengaduan Warga',
                        'menu' => [
                            ['Daftar Laporan', route('admin.laporan.index'), 'admin.laporan.index|admin.laporan.show', $ikon['pengaduan'], $laporanBaru],
                            ['Peta Sebaran', route('admin.laporan.peta'), 'admin.laporan.peta', $ikon['peta']],
                        ],
                    ],
                ];

                // Pengelolaan isi situs hanya untuk Admin Desa dan Super Admin.
                // Kepala Dusun cukup menangani laporan warga di dusunnya.
                if (in_array(auth()->user()->role, ['superadmin', 'admin'], true)) {
                    $grup[] = [
                        'judul' => 'Informasi Desa',
                        'menu' => [
                            ['Berita & Pengumuman', route('admin.berita.index'), 'admin.berita.*', $ikon['berita']],
                            ['Agenda Kegiatan', route('admin.agenda.index'), 'admin.agenda.*', $ikon['agenda']],
                            ['Galeri', route('admin.galeri.index'), 'admin.galeri.*', $ikon['galeri']],
                            ['Dokumen Desa', route('admin.dokumen.index'), 'admin.dokumen.*', $ikon['dokumen']],
                        ],
                    ];

                    $grup[] = [
                        'judul' => 'Data Desa',
                        'menu' => [
                            ['Profil Desa', route('admin.profil.edit'), 'admin.profil.*', $ikon['profil']],
                            ['Struktur Organisasi', route('admin.perangkat.index'), 'admin.perangkat.*', $ikon['organisasi']],
                            ['Infografis Penduduk', route('admin.infografis.ringkasan'), 'admin.infografis.*', $ikon['infografis']],
                            ['Potensi Desa', route('admin.potensi.index'), 'admin.potensi.*', $ikon['potensi']],
                        ],
                    ];
                }

                if (auth()->user()->role === 'superadmin') {
                    $grup[] = [
                        'judul' => 'Pengaturan',
                        'menu' => [
                            ['Kelola Akun', route('admin.user.index'), 'admin.user.*', $ikon['akun']],
                        ],
                    ];
                }
            @endphp

            @foreach ($grup as $g)
                <div class="{{ !$loop->first ? 'mt-5' : '' }}">
                    @if ($g['judul'])
                        <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/35">
                            {{ $g['judul'] }}
                        </p>
                    @endif

                    <div class="space-y-0.5">
                        @foreach ($g['menu'] as $m)
                            @php
                                [$label, $url, $pola, $path] = array_slice($m, 0, 4);
                                $penanda = $m[4] ?? null;
                                $aktif = request()->routeIs(explode('|', $pola));
                            @endphp
                            <a href="{{ $url }}"
                               class="relative flex items-center gap-3 pl-3 pr-3 py-2.5 rounded-lg transition
                                      {{ $aktif ? 'bg-white/[0.13] text-white font-medium' : 'text-white/65 hover:bg-white/[0.06] hover:text-white' }}">

                                {{-- penanda menu aktif --}}
                                @if ($aktif)
                                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 rounded-r"
                                          style="background: var(--padi-light);"></span>
                                @endif

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.6" stroke="currentColor"
                                     class="w-[18px] h-[18px] shrink-0 {{ $aktif ? '' : 'opacity-70' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                                </svg>

                                <span class="truncate">{{ $label }}</span>

                                {{-- penanda jumlah laporan yang menunggu verifikasi --}}
                                @if ($penanda)
                                    <span class="ml-auto shrink-0 min-w-[20px] h-5 px-1.5 rounded-full
                                                 text-[11px] font-semibold flex items-center justify-center"
                                          style="background:#C98A16; color:#fff;"
                                          title="{{ $penanda }} laporan menunggu verifikasi">
                                        {{ $penanda > 99 ? '99+' : $penanda }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="px-3 py-4 border-t border-white/10">
            @php
                $pengguna = auth()->user();

                // inisial dari maksimal dua kata pertama nama
                $inisial = collect(explode(' ', trim($pengguna->name)))
                    ->filter()
                    ->take(2)
                    ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
                    ->implode('');

                $labelPeran = match ($pengguna->role) {
                    'superadmin' => 'Super Admin',
                    'admin'      => 'Admin Desa',
                    'kadus'      => 'Kepala Dusun',
                    default      => 'Pengguna',
                };
            @endphp

            {{-- Identitas pengguna --}}
            <div class="flex items-center gap-3 px-2 mb-3">
                <span class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold shrink-0"
                      style="background: rgba(255,255,255,0.14); color: #fff;">
                    {{ $inisial ?: '?' }}
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $pengguna->name }}</p>
                    <p class="text-[11px] text-white/50 leading-tight">{{ $labelPeran }}</p>
                </div>
            </div>

            {{-- Aksi akun --}}
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-white/70 hover:bg-white/5 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.6" stroke="currentColor" class="w-4 h-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan Akun</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-white/70 hover:text-white transition"
                            onmouseover="this.style.background='rgba(192,57,43,0.35)'"
                            onmouseout="this.style.background='transparent'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.6" stroke="currentColor" class="w-4 h-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 min-h-screen min-w-0">
        <header class="bg-white border-b border-black/5 px-4 sm:px-8 py-4 flex items-center gap-3 justify-between">
            <div class="flex items-center gap-3">
                <button class="md:hidden text-black/60" onclick="toggleSidebar(true)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="font-display text-lg sm:text-xl font-semibold">@yield('title', 'Dashboard')</h1>
            </div>
            @hasSection('header-action')
                @yield('header-action')
            @endif
        </header>

        <main class="p-4 sm:p-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg px-4 py-3 text-sm" style="background:#DEEEDF; color:#205C31;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        function toggleSidebar(open) {
            sidebar.classList.toggle('sidebar-open', open);
            backdrop.classList.toggle('backdrop-open', open);
        }
    </script>

    {{-- ============ DIALOG KONFIRMASI HAPUS ============
         Satu dialog dipakai bersama oleh seluruh tombol hapus di dashboard,
         menggantikan kotak konfirmasi bawaan browser. --}}
    <div id="dialog-hapus" class="fixed inset-0 z-[60] hidden items-center justify-center p-5"
         style="background: rgba(9,26,17,0.55);" role="dialog" aria-modal="true"
         aria-labelledby="dialog-hapus-judul">

        <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-xl"
             onclick="event.stopPropagation()">

            <div class="p-6 text-center">
                <span class="inline-flex items-center justify-center w-14 h-14 rounded-full mb-4"
                      style="background:#FBEAEA; color:#C0392B;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.6" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </span>

                <h2 id="dialog-hapus-judul" class="font-display text-lg font-semibold">Hapus data ini?</h2>

                {{-- nama data yang akan dihapus, agar tidak salah sasaran --}}
                <p id="dialog-hapus-nama" class="text-sm font-medium mt-2 px-3 py-2 rounded-lg hidden"
                   style="background: var(--kertas); color: var(--ink);"></p>

                <p id="dialog-hapus-pesan" class="text-sm mt-2.5 leading-relaxed" style="color: var(--lembut);"></p>
            </div>

            <div class="flex gap-2 px-6 pb-6">
                <button type="button" id="dialog-hapus-batal"
                        class="tombol-batal flex-1 justify-center">
                    Batal
                </button>
                <button type="button" id="dialog-hapus-ya"
                        class="tombol-simpan flex-1 justify-center"
                        style="background:#C0392B; border-color:#C0392B;">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const dialog   = document.getElementById('dialog-hapus');
            const elJudul  = document.getElementById('dialog-hapus-judul');
            const elNama   = document.getElementById('dialog-hapus-nama');
            const elPesan  = document.getElementById('dialog-hapus-pesan');
            const tblBatal = document.getElementById('dialog-hapus-batal');
            const tblYa    = document.getElementById('dialog-hapus-ya');

            let formTertunda = null;

            function buka(form) {
                formTertunda = form;

                elJudul.textContent = form.dataset.judul || 'Hapus data ini?';
                elPesan.textContent = form.dataset.pesan || 'Data yang dihapus tidak dapat dikembalikan.';

                const nama = form.dataset.nama;
                if (nama) {
                    elNama.textContent = nama;
                    elNama.classList.remove('hidden');
                } else {
                    elNama.classList.add('hidden');
                }

                dialog.classList.remove('hidden');
                dialog.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // fokus diarahkan ke Batal, bukan ke Hapus, agar tombol Enter
                // tidak langsung menghapus data secara tidak sengaja
                tblBatal.focus();
            }

            function tutup() {
                formTertunda = null;
                dialog.classList.add('hidden');
                dialog.classList.remove('flex');
                document.body.style.overflow = '';
            }

            // tangkap semua form hapus, termasuk yang dimuat belakangan
            document.addEventListener('submit', function (e) {
                const form = e.target.closest('form[data-konfirmasi-hapus]');
                if (!form || form.dataset.sudahDisetujui === '1') return;

                e.preventDefault();
                buka(form);
            });

            tblYa.addEventListener('click', function () {
                if (!formTertunda) return;
                formTertunda.dataset.sudahDisetujui = '1';
                formTertunda.submit();
                tutup();
            });

            tblBatal.addEventListener('click', tutup);
            dialog.addEventListener('click', function (e) {
                if (e.target === dialog) tutup();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !dialog.classList.contains('hidden')) tutup();
            });
        })();
    </script>

</body>
</html>