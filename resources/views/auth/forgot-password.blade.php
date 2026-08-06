@php
    $profil = \App\Models\ProfilDesa::first();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi — Desa {{ $profil->nama_desa ?? 'Cibiuk' }}</title>

    @if($profil->logo_path ?? null)
        <link rel="icon" href="{{ Storage::url($profil->logo_path) }}">
    @endif
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sawah-dark: #0E5C3A;
            --kertas:     #F7F8F7;
            --padi:       #157F4F;
            --ink:        #171A18;
            --lembut:     #5D635F;
            --garis:      #E3E7E4;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--kertas); color: var(--ink); }
        .font-display { letter-spacing: -0.02em; }
        .kolom-input {
            width: 100%; border: 1px solid var(--garis); border-radius: 10px;
            padding: 10px 14px; font-size: 14px; background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .kolom-input:focus {
            outline: none; border-color: var(--padi);
            box-shadow: 0 0 0 3px rgba(21,127,79,0.12);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-6 py-12">

<div class="w-full max-w-sm">

    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-8">
        @if($profil?->logo_path)
            <img src="{{ Storage::url($profil->logo_path) }}" alt="Logo Desa" class="w-10 h-10 rounded-full object-cover">
        @else
            <span class="w-10 h-10 rounded-full flex items-center justify-center font-display font-bold text-sm text-white"
                  style="background: var(--sawah-dark);">DC</span>
        @endif
        <span class="font-display text-base font-bold">Desa {{ $profil->nama_desa ?? 'Cibiuk' }}</span>
    </a>

    <div class="bg-white rounded-2xl p-7" style="border: 1px solid var(--garis);">
        <h1 class="font-display text-xl font-bold">Lupa Kata Sandi</h1>
        <p class="text-sm mt-2 leading-relaxed" style="color: var(--lembut);">
            Masukkan email akun Anda. Kami akan mengirimkan tautan untuk membuat kata sandi baru.
        </p>

        @if (session('status'))
            <div class="mt-5 rounded-lg px-4 py-3 text-sm" style="background: #E7F3EC; color: #0E5C3A;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-5 rounded-lg px-4 py-3 text-sm" style="background: #FBEAEA; color: #96261F;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-medium mb-1.5" style="color: var(--lembut);">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus class="kolom-input">
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-lg text-sm font-semibold text-white transition hover:brightness-110"
                    style="background: var(--sawah-dark);">
                Kirim Tautan Reset
            </button>
        </form>

        <p class="text-sm mt-5 text-center">
            <a href="{{ route('login') }}" class="hover:underline underline-offset-4" style="color: var(--padi);">
                Kembali ke halaman masuk
            </a>
        </p>
    </div>

    <p class="text-xs mt-6 text-center leading-relaxed" style="color: var(--lembut);">
        Email tidak kunjung datang? Hubungi Super Admin desa untuk menyetel ulang kata sandi Anda secara langsung.
    </p>
</div>

</body>
</html>