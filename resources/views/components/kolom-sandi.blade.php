{{--
    Kolom kata sandi dengan tombol untuk menampilkan isinya.
    Berguna saat admin membuatkan akun untuk orang lain — sandi yang
    diketik bisa diperiksa dulu sebelum disampaikan ke pemiliknya.

    Pemakaian:
        <x-kolom-sandi name="password" label="Kata Sandi" required />
        <x-kolom-sandi name="password_confirmation" label="Ulangi Kata Sandi" />
        <x-kolom-sandi name="current_password" label="Kata Sandi Saat Ini"
                       autocomplete="current-password" />
--}}
@props([
    'name',
    'label' => null,
    'required' => false,
    'autocomplete' => 'new-password',
    'petunjuk' => null,
    'id' => null,
])

@php $idKolom = $id ?? $name; @endphp

<div>
    @if($label)
        <label for="{{ $idKolom }}" class="text-xs text-black/50 mb-1 block">{{ $label }}</label>
    @endif

    <div class="relative">
        <input type="password"
               id="{{ $idKolom }}"
               name="{{ $name }}"
               autocomplete="{{ $autocomplete }}"
               @if($required) required @endif
               {{ $attributes->merge(['class' => 'w-full rounded-lg border-black/10 text-sm pr-11']) }}>

        <button type="button"
                aria-label="Tampilkan kata sandi"
                title="Tampilkan kata sandi"
                class="absolute inset-y-0 right-0 w-11 flex items-center justify-center rounded-r-lg
                       text-black/35 hover:text-black/60 transition"
                onclick="
                    const kolom = this.previousElementSibling;
                    const disembunyikan = kolom.type === 'password';
                    kolom.type = disembunyikan ? 'text' : 'password';
                    this.querySelectorAll('svg').forEach(i => i.classList.toggle('hidden'));
                    const teks = disembunyikan ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi';
                    this.setAttribute('aria-label', teks);
                    this.setAttribute('title', teks);
                ">
            {{-- ikon mata: sandi sedang tersembunyi --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.7" stroke="currentColor" class="w-[18px] h-[18px]">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            {{-- ikon mata dicoret: sandi sedang terlihat --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.7" stroke="currentColor" class="w-[18px] h-[18px] hidden">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
        </button>
    </div>

    @if($petunjuk)
        <p class="text-xs text-black/40 mt-1">{{ $petunjuk }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>