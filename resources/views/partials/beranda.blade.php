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
                <div class="reveal-kanan kartu p-6 flex flex-col">

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