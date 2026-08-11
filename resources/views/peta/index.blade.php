@extends('layouts.publik')

@section('title', 'Peta Wilayah Desa')
@section('main-class', '')

@section('meta_judul', 'Peta Wilayah Desa ' . ($profil->nama_desa ?? 'Cibiuk'))
@section('meta_deskripsi', 'Peta sosial dan batas wilayah Desa ' . ($profil->nama_desa ?? 'Cibiuk') . ', Kecamatan Ciranjang, Kabupaten Cianjur.')
@section('meta_gambar', $profil?->peta_wilayah_path ? url(Storage::url($profil->peta_wilayah_path)) : '')

@section('content')

{{-- ============ HEADER ============ --}}
<section class="pola-titik border-b" style="border-color: var(--garis); background: var(--kertas);">
    <div class="wadah py-12">
        <p class="label-bagian">Geografis</p>
        <h1 class="font-display text-2xl sm:text-3xl font-bold mt-1.5">Peta Wilayah Desa</h1>
        <p class="text-sm mt-3 max-w-2xl leading-relaxed" style="color: var(--lembut);">
            Peta sosial Desa {{ $profil->nama_desa ?? 'Cibiuk' }} beserta batas wilayah
            dan pembagian dusunnya.
        </p>
    </div>
</section>

{{-- ============ GAMBAR PETA ============ --}}
<section class="wadah py-11">
    @if($profil?->peta_wilayah_path)
        <div class="reveal-skala kartu overflow-hidden">
            <a href="{{ Storage::url($profil->peta_wilayah_path) }}" target="_blank" rel="noopener"
               class="block group" title="Buka gambar peta ukuran penuh">
                <img src="{{ Storage::url($profil->peta_wilayah_path) }}"
                     alt="Peta sosial Desa {{ $profil->nama_desa ?? 'Cibiuk' }}"
                     class="w-full h-auto">
            </a>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
            <p class="text-xs" style="color: var(--lembut);">
                Peta ini menunjukkan pembagian wilayah RT dan RW di Desa {{ $profil->nama_desa ?? 'Cibiuk' }}.
            </p>

            <a href="{{ Storage::url($profil->peta_wilayah_path) }}" target="_blank" rel="noopener"
               class="tombol-garis inline-flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg text-sm font-medium shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor" class="w-4 h-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Lihat Ukuran Penuh
            </a>
        </div>
    @else
        <div class="kartu p-10 text-center">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-full mb-4"
                  style="background: var(--sawah-light); color: var(--sawah-dark);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                </svg>
            </span>
            <p class="font-display text-base font-bold mb-1">Peta wilayah belum tersedia</p>
            <p class="text-sm" style="color: var(--lembut);">
                Gambar peta sedang disiapkan oleh pemerintah desa.
            </p>
        </div>
    @endif
</section>

{{-- ============ DATA WILAYAH ============ --}}
<section class="pola-petak lengkung-atas" style="background: var(--kertas);">
    <div class="wadah py-11">

        <div class="reveal mb-7">
            <p class="label-bagian">Keterangan</p>
            <h2 class="font-display text-2xl font-bold mt-1.5">Batas dan Pembagian Wilayah</h2>
        </div>

        <div class="grid lg:grid-cols-2 gap-5">

            {{-- Batas wilayah --}}
            <div class="reveal-kiri kartu p-6">
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
                    <h3 class="font-display text-base font-semibold">Batas Wilayah</h3>
                </div>

                <div class="space-y-1">
                    @foreach ([
                        ['Utara',   $profil->batas_utara ?? null],
                        ['Selatan', $profil->batas_selatan ?? null],
                        ['Timur',   $profil->batas_timur ?? null],
                        ['Barat',   $profil->batas_barat ?? null],
                    ] as [$arah, $nilai])
                        <div class="flex items-start justify-between gap-4 py-2.5 {{ !$loop->last ? 'border-b' : '' }}"
                             style="border-color: var(--garis);">
                            <span class="text-sm font-medium shrink-0">Sebelah {{ $arah }}</span>
                            <span class="text-sm text-right" style="color: var(--lembut);">{{ $nilai ?: '-' }}</span>
                        </div>
                    @endforeach
                </div>

                @if($profil?->luas_wilayah_ha)
                    <div class="mt-5 pt-5 border-t flex items-center justify-between gap-4"
                         style="border-color: var(--garis);">
                        <span class="text-sm font-medium">Luas Wilayah Keseluruhan</span>
                        <span class="font-display text-lg font-bold" style="color: var(--sawah-dark);">
                            {{ number_format($profil->luas_wilayah_ha, 3, ',', '.') }} Ha
                        </span>
                    </div>
                @endif
            </div>

            {{-- Dusun --}}
            <div class="reveal-kanan kartu p-6" style="transition-delay: 70ms;">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg shrink-0"
                          style="background: var(--sawah-light); color: var(--sawah-dark);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
                        </svg>
                    </span>
                    <h3 class="font-display text-base font-semibold">Dusun</h3>
                </div>

                <div class="space-y-2">
                    @forelse ($dusun as $d)
                        <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl"
                             style="background: var(--sawah-light);">
                            <span class="text-sm font-medium" style="color: var(--sawah-dark);">
                                Dusun {{ $d->nama }}
                            </span>
                            <span class="text-xs shrink-0" style="color: var(--sawah-dark); opacity: .7;">
                                {{ $d->jarak_ke_desa_km }} km dari kantor desa
                            </span>
                        </div>
                    @empty
                        <p class="text-sm" style="color: var(--lembut);">Belum ada data dusun.</p>
                    @endforelse
                </div>

                @if($profil?->jarak_ke_kecamatan_km || $profil?->jarak_ke_kabupaten_km)
                    <div class="mt-5 pt-5 border-t space-y-2.5" style="border-color: var(--garis);">
                        @if($profil->jarak_ke_kecamatan_km)
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-sm" style="color: var(--lembut);">Jarak ke Ibu Kota Kecamatan</span>
                                <span class="text-sm font-medium">{{ $profil->jarak_ke_kecamatan_km }} km</span>
                            </div>
                        @endif
                        @if($profil->jarak_ke_kabupaten_km)
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-sm" style="color: var(--lembut);">Jarak ke Ibu Kota Kabupaten</span>
                                <span class="text-sm font-medium">{{ $profil->jarak_ke_kabupaten_km }} km</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection