@extends('layouts.admin')

@section('title', 'Pengaturan Akun')

@section('content')
@php
    $pengguna = auth()->user();

    $labelPeran = match ($pengguna->role) {
        'superadmin' => 'Super Admin',
        'admin'      => 'Admin Desa',
        'kadus'      => 'Kepala Dusun',
        default      => 'Pengguna',
    };

    $gayaPeran = match ($pengguna->role) {
        'superadmin' => 'background:#E7F3EC; color:#0E5C3A;',
        'admin'      => 'background:#E8F0FB; color:#1B4B8F;',
        'kadus'      => 'background:#FEF6E7; color:#92600E;',
        default      => 'background:#F0F1F0; color:#5D635F;',
    };

    $inisial = collect(explode(' ', trim($pengguna->name)))
        ->filter()->take(2)
        ->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))
        ->implode('');
@endphp

<div class="max-w-2xl space-y-6">

    {{-- ============ RINGKASAN AKUN ============ --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6 flex items-center gap-4">
        <span class="w-14 h-14 rounded-full flex items-center justify-center text-lg font-semibold shrink-0 text-white"
              style="background: var(--sawah-dark);">
            {{ $inisial ?: '?' }}
        </span>
        <div class="min-w-0">
            <p class="font-display text-lg font-semibold truncate">{{ $pengguna->name }}</p>
            <p class="text-sm text-black/50 truncate">{{ $pengguna->email }}</p>
            <span class="inline-block mt-2 text-xs px-2.5 py-1 rounded-full font-medium" style="{{ $gayaPeran }}">
                {{ $labelPeran }}
            </span>
        </div>
    </div>

    {{-- ============ DATA DIRI ============ --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
            <h2 class="font-display text-base font-semibold">Data Diri</h2>
        </div>
        <p class="text-sm text-black/50 mb-5 ml-3.5">Perbarui nama dan alamat email akun Anda.</p>

        @if (session('status') === 'profile-updated')
            <div class="mb-5 rounded-lg px-4 py-3 text-sm" style="background:#E7F3EC; color:#0E5C3A;">
                Data diri berhasil diperbarui.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="text-xs text-black/50 mb-1 block">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name', $pengguna->name) }}"
                       required autocomplete="name" class="w-full rounded-lg border-black/10 text-sm">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="text-xs text-black/50 mb-1 block">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $pengguna->email) }}"
                       required autocomplete="username" class="w-full rounded-lg border-black/10 text-sm">
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

                @if ($pengguna instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$pengguna->hasVerifiedEmail())
                    <div class="mt-2 text-xs" style="color:#92600E;">
                        Email ini belum terverifikasi.
                        <form method="POST" action="{{ route('verification.send') }}" class="inline">
                            @csrf
                            <button class="underline underline-offset-2">Kirim ulang tautan verifikasi</button>
                        </form>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs" style="color:#0E5C3A;">Tautan verifikasi baru telah dikirim ke email Anda.</p>
                    @endif
                @endif
            </div>

            <button class="px-5 py-2.5 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- ============ GANTI KATA SANDI ============ --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
            <h2 class="font-display text-base font-semibold">Ganti Kata Sandi</h2>
        </div>
        <p class="text-sm text-black/50 mb-5 ml-3.5">
            Gunakan kata sandi yang panjang dan tidak dipakai di tempat lain.
        </p>

        @if (session('status') === 'password-updated')
            <div class="mb-5 rounded-lg px-4 py-3 text-sm" style="background:#E7F3EC; color:#0E5C3A;">
                Kata sandi berhasil diperbarui.
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <div>
                <x-kolom-sandi name="current_password" label="Kata Sandi Saat Ini"
                               autocomplete="current-password" />
                @if ($errors->updatePassword->get('current_password'))
                    <p class="text-xs text-red-600 mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <x-kolom-sandi name="password" label="Kata Sandi Baru" petunjuk="Minimal 8 karakter." />
                @if ($errors->updatePassword->get('password'))
                    <p class="text-xs text-red-600 mt-1">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <x-kolom-sandi name="password_confirmation" label="Ulangi Kata Sandi Baru" />
                @if ($errors->updatePassword->get('password_confirmation'))
                    <p class="text-xs text-red-600 mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>

            <button class="px-5 py-2.5 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
                Perbarui Kata Sandi
            </button>
        </form>
    </div>

    {{-- ============ CATATAN ============ --}}
    <div class="rounded-2xl p-5 text-sm" style="background: var(--sawah-light); color: var(--sawah-dark);">
        Akun dashboard hanya dapat dibuat atau dihapus oleh Super Admin desa melalui menu
        <strong>Kelola Akun</strong>. Bila Anda perlu mengubah peran atau menonaktifkan akun,
        silakan hubungi Super Admin.
    </div>
</div>
@endsection