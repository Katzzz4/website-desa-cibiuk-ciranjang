@php
    // diambil langsung di sini karena halaman login tidak melewati controller khusus
    $profil = \App\Models\ProfilDesa::first();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Desa {{ $profil->nama_desa ?? 'Cibiuk' }}</title>

    @if($profil->logo_path ?? null)
        <link rel="icon" href="{{ Storage::url($profil->logo_path) }}">
    @endif
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sawah-dark:   #0E5C3A;
            --sawah-darker: #093F28;
            --sawah-light:  #EDF5F1;
            --kertas:       #F7F8F7;
            --padi:         #157F4F;
            --padi-light:   #A7D9BE;
            --ink:          #171A18;
            --lembut:       #5D635F;
            --garis:        #E3E7E4;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--kertas);
            color: var(--ink);
        }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.02em; }
        .kolom-input {
            width: 100%;
            border: 1px solid var(--garis);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .kolom-input:focus {
            outline: none;
            border-color: var(--padi);
            box-shadow: 0 0 0 3px rgba(21,127,79,0.12);
        }
    </style>
</head>
<body class="min-h-screen">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ============ PANEL IDENTITAS DESA ============ --}}
    <div class="lg:w-[45%] xl:w-[42%] relative overflow-hidden text-white px-6 sm:px-10 py-10 lg:py-14 flex flex-col justify-between"
         style="background: linear-gradient(155deg, var(--sawah-dark), var(--sawah-darker));">

        <div>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                @if($profil?->logo_path)
                    <img src="{{ Storage::url($profil->logo_path) }}" alt="Logo Desa"
                         class="w-11 h-11 rounded-full object-cover shrink-0">
                @else
                    <span class="w-11 h-11 rounded-full flex items-center justify-center font-display font-bold shrink-0"
                          style="background: var(--padi-light); color: var(--sawah-darker);">DC</span>
                @endif
                <span>
                    <span class="font-display text-lg font-bold block leading-tight">
                        Desa {{ $profil->nama_desa ?? 'Cibiuk' }}
                    </span>
                    <span class="text-xs text-white/55 block leading-tight">
                        Kec. {{ $profil->kecamatan ?? 'Ciranjang' }} · Kab. {{ $profil->kabupaten ?? 'Cianjur' }}
                    </span>
                </span>
            </a>
        </div>

        <div class="hidden lg:block py-10">
            <p class="text-[11px] font-semibold tracking-[0.18em] uppercase mb-3" style="color: var(--padi-light);">
                Dashboard Pemerintah Desa
            </p>
            <h1 class="font-display text-3xl xl:text-4xl font-bold leading-tight">
                Kelola informasi dan<br>layanan warga
            </h1>
            @if($profil?->visi)
                <p class="text-sm text-white/60 mt-5 leading-relaxed max-w-sm">
                    &ldquo;{{ $profil->visi }}&rdquo;
                </p>
            @endif
        </div>

        <p class="text-xs text-white/45 mt-8 lg:mt-0">
            Halaman ini khusus untuk perangkat desa.
            <a href="{{ url('/') }}" class="underline underline-offset-4 hover:text-white/70 transition">
                Kembali ke situs desa
            </a>
        </p>
    </div>

    {{-- ============ FORMULIR MASUK ============ --}}
    <div class="flex-1 flex items-center justify-center px-6 sm:px-10 py-12">
        <div class="w-full max-w-sm">

            <div class="mb-8">
                <h2 class="font-display text-2xl font-bold">Masuk ke Dashboard</h2>
                <p class="text-sm mt-1.5" style="color: var(--lembut);">
                    Gunakan email dan kata sandi yang diberikan Super Admin.
                </p>
            </div>

            {{-- pesan setelah reset kata sandi, dll --}}
            @if (session('status'))
                <div class="mb-5 rounded-lg px-4 py-3 text-sm" style="background: #E7F3EC; color: #0E5C3A;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-lg px-4 py-3 text-sm" style="background: #FBEAEA; color: #96261F;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium mb-1.5" style="color: var(--lembut);">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           placeholder="nama@desacibiuk.id" class="kolom-input">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-medium" style="color: var(--lembut);">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs hover:underline underline-offset-4" style="color: var(--padi);">
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password" class="kolom-input" style="padding-right: 44px;">
                        <button type="button"
                                aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi"
                                class="absolute inset-y-0 right-0 w-11 flex items-center justify-center transition"
                                style="color: var(--lembut);"
                                onclick="
                                    const kolom = this.previousElementSibling;
                                    const disembunyikan = kolom.type === 'password';
                                    kolom.type = disembunyikan ? 'text' : 'password';
                                    this.querySelectorAll('svg').forEach(i => i.classList.toggle('hidden'));
                                    const teks = disembunyikan ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi';
                                    this.setAttribute('aria-label', teks);
                                    this.setAttribute('title', teks);
                                ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.7" stroke="currentColor" class="w-[18px] h-[18px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.7" stroke="currentColor" class="w-[18px] h-[18px] hidden">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2.5 pt-1 cursor-pointer">
                    <input type="checkbox" name="remember"
                           class="rounded border-black/20 w-4 h-4"
                           style="color: var(--sawah-dark);">
                    <span class="text-sm" style="color: var(--lembut);">Ingat saya di perangkat ini</span>
                </label>

                <button type="submit"
                        class="w-full py-3 rounded-lg text-sm font-semibold text-white transition hover:brightness-110 mt-2"
                        style="background: var(--sawah-dark);">
                    Masuk
                </button>
            </form>

            <p class="text-xs mt-8 leading-relaxed" style="color: var(--lembut);">
                Belum punya akun? Akun dashboard hanya dapat dibuatkan oleh Super Admin desa.
                Hubungi kantor desa untuk permintaan akses.
            </p>
        </div>
    </div>
</div>

</body>
</html>