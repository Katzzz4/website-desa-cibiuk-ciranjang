@extends('layouts.publik')

@section('title', 'Agenda Kegiatan')

@section('content')
<div class="space-y-10">

    <div>
        <p class="text-xs font-medium tracking-widest uppercase" style="color: var(--talang);">Informasi Desa</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold mt-1">Agenda Kegiatan</h1>
    </div>

    {{-- AGENDA MENDATANG --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
            <h2 class="font-display text-lg font-semibold">Akan Datang</h2>
        </div>

        <div class="space-y-3">
            @forelse ($mendatang as $a)
                <div class="bg-white rounded-2xl border border-black/5 p-5 flex gap-4">
                    <div class="shrink-0 w-16 text-center rounded-xl py-2" style="background: var(--sawah-light); color: var(--sawah-dark);">
                        <p class="text-xs uppercase font-medium">{{ $a->tanggal_mulai->translatedFormat('M') }}</p>
                        <p class="font-display text-xl font-semibold leading-none mt-0.5">{{ $a->tanggal_mulai->format('d') }}</p>
                    </div>
                    <div>
                        <h3 class="font-display text-base font-semibold">{{ $a->judul }}</h3>
                        <p class="text-xs text-black/50 mt-1">
                            {{ $a->tanggal_mulai->translatedFormat('d M Y, H:i') }}
                            @if($a->tanggal_selesai) – {{ $a->tanggal_selesai->translatedFormat('d M Y, H:i') }} @endif
                            @if($a->lokasi) · {{ $a->lokasi }} @endif
                        </p>
                        @if($a->deskripsi)
                            <p class="text-sm text-black/70 mt-2">{{ $a->deskripsi }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-black/40 text-center py-8">Belum ada agenda mendatang.</p>
            @endforelse
        </div>
    </div>

    {{-- AGENDA SUDAH LEWAT --}}
    @if($lampau->count())
        <div>
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
                <h2 class="font-display text-lg font-semibold">Kegiatan Sebelumnya</h2>
            </div>

            <div class="space-y-3">
                @foreach ($lampau as $a)
                    <div class="bg-white rounded-2xl border border-black/5 p-5 flex gap-4 opacity-70">
                        <div class="shrink-0 w-16 text-center rounded-xl py-2 bg-black/[0.04]">
                            <p class="text-xs uppercase font-medium text-black/50">{{ $a->tanggal_mulai->translatedFormat('M') }}</p>
                            <p class="font-display text-xl font-semibold leading-none mt-0.5">{{ $a->tanggal_mulai->format('d') }}</p>
                        </div>
                        <div>
                            <h3 class="font-display text-base font-semibold">{{ $a->judul }}</h3>
                            <p class="text-xs text-black/50 mt-1">
                                {{ $a->tanggal_mulai->translatedFormat('d M Y') }}
                                @if($a->lokasi) · {{ $a->lokasi }} @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $lampau->links() }}
        </div>
    @endif

</div>
@endsection