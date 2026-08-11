{{-- STRUKTUR ORGANISASI --}}
<div class="bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
    <div class="flex items-center gap-2 mb-1">
        <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
        <h2 class="font-display text-lg font-semibold">Struktur Organisasi Pemerintah Desa</h2>
    </div>
    <p class="text-sm text-black/50 mb-7 ml-3.5">
        Dipimpin oleh {{ $perangkat->firstWhere('jabatan', 'Kepala Desa')->nama ?? '-' }} sebagai Kepala Desa.
        Lihat tugas pokok dan fungsi tiap bagian untuk mengetahui kebutuhan Anda harus disampaikan ke mana.
    </p>

    <div class="space-y-6">
        @php
            $kepalaDesa = $perangkat->firstWhere('atasan_jabatan', null);
            $lapisKedua = $perangkat->where('atasan_jabatan', $kepalaDesa->jabatan ?? '___');
            $lapisKetiga = $perangkat->whereIn('atasan_jabatan', $lapisKedua->pluck('jabatan')->all());

            $inisial = fn($nama) => collect(explode(' ', trim($nama)))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('');
        @endphp

        @if($kepalaDesa)
            <div class="flex justify-center">
                <div class="text-center rounded-xl px-6 py-5 max-w-md" style="background: var(--sawah-dark);">
                    @if($kepalaDesa->foto_path)
                        <img src="{{ Storage::url($kepalaDesa->foto_path) }}" alt="{{ $kepalaDesa->nama }}"
                            class="w-16 h-16 rounded-full object-cover mx-auto mb-3 border-2 border-white/20">
                    @else
                        <div class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center text-sm font-semibold"
                            style="background: rgba(255,255,255,0.12); color: var(--padi-light);">
                            {{ $inisial($kepalaDesa->nama) }}
                        </div>
                    @endif
                    <p class="text-sm font-semibold text-white">{{ $kepalaDesa->nama }}</p>
                    <p class="text-xs mt-0.5" style="color: var(--padi-light);">{{ $kepalaDesa->jabatan }}</p>
                    @if($kepalaDesa->tupoksi)
                        <p class="text-xs text-white/70 mt-2.5 leading-relaxed text-left">{{ $kepalaDesa->tupoksi }}</p>
                    @endif
                </div>
            </div>
        @endif

        @if($lapisKedua->count())
            <div class="flex flex-wrap justify-center gap-3 pt-2">
                @foreach ($lapisKedua as $p)
                    <div class="rounded-xl px-4 py-4 border-2 text-center w-full sm:w-[calc(50%-0.375rem)] lg:w-[calc(33.333%-0.5rem)]"
                        style="border-color: var(--sawah-light); background: var(--sawah-light);">
                        @if($p->foto_path)
                            <img src="{{ Storage::url($p->foto_path) }}" alt="{{ $p->nama }}"
                                class="w-12 h-12 rounded-full object-cover mx-auto mb-2.5 border-2 border-white">
                        @else
                            <div class="w-12 h-12 rounded-full mx-auto mb-2.5 flex items-center justify-center text-xs font-semibold border-2 border-white"
                                style="background: var(--sawah-dark); color: white;">
                                {{ $inisial($p->nama) }}
                            </div>
                        @endif
                        <p class="text-sm font-medium" style="color: var(--sawah-dark);">{{ $p->nama }}</p>
                        <p class="text-xs text-black/50 mt-0.5">
                            {{ $p->jabatan }}
                            @if($p->dusun) · {{ $p->dusun->nama }} @endif
                        </p>
                        @if($p->tupoksi)
                            <p class="text-xs text-black/60 mt-2 leading-relaxed text-left">{{ $p->tupoksi }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if($lapisKetiga->count())
            <div class="flex flex-wrap justify-center gap-2.5 pt-4 border-t border-dashed border-black/10">
                @foreach ($lapisKetiga as $p)
                    <div
                        class="rounded-lg px-3.5 py-3 bg-black/[0.02] text-center w-full sm:w-[calc(50%-0.313rem)] lg:w-[calc(33.333%-0.417rem)]">
                        @if($p->foto_path)
                            <img src="{{ Storage::url($p->foto_path) }}" alt="{{ $p->nama }}"
                                class="w-9 h-9 rounded-full object-cover mx-auto mb-2">
                        @else
                            <div class="w-9 h-9 rounded-full mx-auto mb-2 flex items-center justify-center text-[10px] font-semibold text-white"
                                style="background: var(--sawah-dark);">
                                {{ $inisial($p->nama) }}
                            </div>
                        @endif
                        <p class="text-xs font-medium">{{ $p->nama }}</p>
                        <p class="text-[11px] text-black/50">{{ $p->jabatan }}</p>
                        @if($p->tupoksi)
                            <p class="text-[11px] text-black/55 mt-1.5 leading-relaxed text-left">{{ $p->tupoksi }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>