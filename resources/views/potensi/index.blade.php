@extends('layouts.publik')

@section('title', 'Potensi Desa')

@section('content')
<div class="space-y-8">

    <div>
        <p class="text-xs font-medium tracking-widest uppercase" style="color: var(--talang);">Profil Desa</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold mt-1">Potensi Desa</h1>
        <p class="text-sm text-black/50 mt-2">
            Usaha, hasil pertanian, peternakan, kerajinan, dan potensi wisata yang ada di Desa Cibiuk.
        </p>
    </div>

    {{-- FILTER JENIS --}}
    @if(count($jenisTersedia) > 1)
        <div class="flex flex-wrap gap-2 text-sm">
            <a href="{{ route('potensi.index') }}"
               class="px-4 py-2 rounded-full transition {{ !$jenis ? 'text-white' : 'bg-white border border-black/10 text-black/60' }}"
               style="{{ !$jenis ? 'background: var(--sawah-dark);' : '' }}">
                Semua
            </a>
            @foreach (\App\Models\PotensiDesa::JENIS as $val => $label)
                @if(in_array($val, $jenisTersedia))
                    <a href="{{ route('potensi.index', ['jenis' => $val]) }}"
                       class="px-4 py-2 rounded-full transition {{ $jenis === $val ? 'text-white' : 'bg-white border border-black/10 text-black/60' }}"
                       style="{{ $jenis === $val ? 'background: var(--sawah-dark);' : '' }}">
                        {{ $label }}
                    </a>
                @endif
            @endforeach
        </div>
    @endif

    {{-- GRID POTENSI --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($potensi as $p)
            <div class="bg-white rounded-2xl border border-black/5 overflow-hidden flex flex-col">
                <div class="aspect-[16/10] bg-[var(--sawah-light)] overflow-hidden shrink-0">
                    @if($p->foto_path)
                        <img src="{{ Storage::url($p->foto_path) }}" alt="{{ $p->nama }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center font-display text-3xl"
                             style="color: var(--sawah-dark); opacity: 0.25;">DC</div>
                    @endif
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <span class="text-[11px] px-2 py-0.5 rounded-full font-medium self-start"
                          style="background: var(--sawah-light); color: var(--sawah-dark);">
                        {{ $p->label_jenis }}
                    </span>

                    <h2 class="font-display text-base font-semibold mt-2 leading-snug">{{ $p->nama }}</h2>

                    @if($p->deskripsi)
                        <p class="text-sm text-black/60 mt-2 leading-relaxed flex-1">{{ $p->deskripsi }}</p>
                    @endif

                    @if($p->link_wa)
                        <a href="{{ $p->link_wa }}" target="_blank" rel="noopener"
                           class="mt-4 text-sm px-4 py-2 rounded-lg text-center text-white self-stretch"
                           style="background: var(--talang);">
                            Hubungi via WhatsApp
                        </a>
                    @elseif($p->kontak)
                        <p class="mt-4 text-sm text-black/50">Kontak: {{ $p->kontak }}</p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-black/40 col-span-full text-center py-10">Belum ada data potensi desa.</p>
        @endforelse
    </div>

    {{ $potensi->links() }}
</div>
@endsection