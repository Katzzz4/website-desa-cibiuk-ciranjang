@extends('layouts.publik')

@section('title', 'Profil Desa')

@section('meta_judul', 'Profil Desa ' . ($profil->nama_desa ?? 'Cibiuk'))
@section('meta_deskripsi', \Illuminate\Support\Str::limit(strip_tags($profil->sejarah ?? 'Sejarah, visi misi, kondisi geografis, dan struktur organisasi pemerintah desa.'), 180))

@section('content')
<div class="space-y-10">

    {{-- HERO --}}
    <div class="reveal-skala relative overflow-hidden rounded-3xl text-white terrace-texture p-8 sm:p-10" style="background: linear-gradient(150deg, var(--sawah-dark), var(--sawah-darker));">
        <p class="text-xs font-medium tracking-widest uppercase" style="color: var(--padi-light);">Selamat Datang di Profil</p>
        <h1 class="font-display text-4xl sm:text-5xl font-semibold mt-2 leading-tight">
            Desa {{ $profil->nama_desa ?? 'Cibiuk' }}
        </h1>
        <p class="text-sm sm:text-base text-white/70 mt-3 max-w-md">
            Kecamatan {{ $profil->kecamatan ?? '-' }}, Kabupaten {{ $profil->kabupaten ?? '-' }}, {{ $profil->provinsi ?? '-' }}
        </p>

        {{-- QUICK STATS --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-8">
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">
                    {{ $ringkasan ? number_format($ringkasan->total_laki + $ringkasan->total_perempuan, 0, ',', '.') : '-' }}
                </p>
                <p class="text-xs text-white/60 mt-0.5">Jiwa Penduduk</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">{{ $dusun->count() }}</p>
                <p class="text-xs text-white/60 mt-0.5">Dusun</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">
                    {{ $profil?->luas_wilayah_ha ? number_format($profil->luas_wilayah_ha, 0, ',', '.') : '-' }}
                </p>
                <p class="text-xs text-white/60 mt-0.5">Hektar Wilayah</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">{{ $ringkasan->tahun ?? '-' }}</p>
                <p class="text-xs text-white/60 mt-0.5">Data Per Tahun</p>
            </div>
        </div>
    </div>

    {{-- SEJARAH --}}
    <div class="reveal-kiri bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
            <h2 class="font-display text-lg font-semibold">Sejarah Singkat</h2>
        </div>
        <p class="text-sm leading-relaxed text-black/70">{{ $profil->sejarah ?? 'Belum ada data sejarah.' }}</p>
    </div>

    {{-- VISI MISI --}}
    <div class="reveal-kanan rounded-2xl p-6 sm:p-8 text-white terrace-texture" style="background: var(--sawah-dark);">
        <p class="text-xs uppercase tracking-widest" style="color: var(--padi-light);">Visi</p>
        <p class="font-display text-xl sm:text-2xl font-semibold leading-snug mt-2 mb-7">
            &ldquo;{{ $profil->visi ?? '-' }}&rdquo;
        </p>

        <p class="text-xs uppercase tracking-widest mb-3" style="color: var(--padi-light);">Misi</p>
        <ol class="space-y-3 text-sm text-white/85">
            @forelse (($profil->misi ?? []) as $i => $poin)
                <li class="flex gap-3">
                    <span class="font-mono-tiket text-xs shrink-0 w-6 h-6 flex items-center justify-center rounded-full mt-0.5"
                          style="background: rgba(255,255,255,0.16); color: var(--padi-light);">
                        {{ $i + 1 }}
                    </span>
                    <span class="pt-0.5">{{ $poin }}</span>
                </li>
            @empty
                <li>Belum ada data misi.</li>
            @endforelse
        </ol>
    </div>

    {{-- PETA SOSIAL DESA --}}
    <div class="reveal bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
            <h2 class="font-display text-lg font-semibold">Peta Sosial Desa</h2>
        </div>

        @if($profil?->peta_wilayah_path)
            <p class="text-sm text-black/50 mb-5 ml-3.5">
                Pembagian wilayah RT dan RW di Desa {{ $profil->nama_desa ?? 'Cibiuk' }}.
            </p>

            <a href="{{ Storage::url($profil->peta_wilayah_path) }}" target="_blank" rel="noopener" class="block group">
                <img src="{{ Storage::url($profil->peta_wilayah_path) }}"
                     alt="Peta sosial Desa {{ $profil->nama_desa ?? 'Cibiuk' }}"
                     class="rounded-xl w-full border border-black/5">
                <p class="text-xs text-black/40 mt-3 group-hover:text-black/60 transition">
                    Klik gambar untuk melihat ukuran penuh
                </p>
            </a>
        @else
            <div class="mt-4 rounded-xl border border-dashed p-8 text-center" style="border-color: var(--garis);">
                <p class="text-sm text-black/45">Peta sosial desa belum tersedia.</p>
                <p class="text-xs text-black/35 mt-1">
                    Dapat diunggah melalui Dashboard &rarr; Profil Desa &rarr; Media.
                </p>
            </div>
        @endif
    </div>

    {{-- GEOGRAFIS --}}
    <div class="reveal-kiri bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
        <div class="flex items-center gap-2 mb-5">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
            <h2 class="font-display text-lg font-semibold">Kondisi Geografis</h2>
        </div>

        <div class="grid sm:grid-cols-2 gap-8">
            <div>
                <p class="text-black/40 text-xs uppercase tracking-widest mb-3">Batas Wilayah</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between border-b border-black/5 pb-2">
                        <span class="text-black/50">Utara</span><span class="font-medium text-right">{{ $profil->batas_utara ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-black/5 pb-2">
                        <span class="text-black/50">Selatan</span><span class="font-medium text-right">{{ $profil->batas_selatan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-black/5 pb-2">
                        <span class="text-black/50">Timur</span><span class="font-medium text-right">{{ $profil->batas_timur ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-black/50">Barat</span><span class="font-medium text-right">{{ $profil->batas_barat ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-black/40 text-xs uppercase tracking-widest mb-3">Jarak Tempuh</p>
                <div class="space-y-2 text-sm mb-5">
                    <div class="flex justify-between border-b border-black/5 pb-2">
                        <span class="text-black/50">Ke Ibu Kota Kabupaten</span><span class="font-medium">{{ $profil->jarak_ke_kabupaten_km ?? '-' }} km</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-black/50">Ke Ibu Kota Kecamatan</span><span class="font-medium">{{ $profil->jarak_ke_kecamatan_km ?? '-' }} km</span>
                    </div>
                </div>

                @if($dusun->count())
                    <p class="text-black/40 text-xs uppercase tracking-widest mb-3">Jarak Antar Dusun</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($dusun as $d)
                            <span class="text-xs px-3 py-1.5 rounded-full bg-[var(--sawah-light)] text-[var(--sawah-dark)]">
                                {{ $d->nama }} · {{ $d->jarak_ke_desa_km }} km
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- STRUKTUR ORGANISASI & TUPOKSI --}}
    @include('partials.struktur-organisasi')

</div>
@endsection