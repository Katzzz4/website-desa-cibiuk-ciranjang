{{--
    Pemisah antar bagian dengan motif anyaman khas Cibiuk.

    Cara pakai:
        <x-pemisah />                    → versi terang (untuk latar krem/putih)
        <x-pemisah gelap />              → versi untuk latar hijau tua
        <x-pemisah judul="Sorotan" />    → dengan teks di tengah
--}}
@props(['gelap' => false, 'judul' => null])

<div {{ $attributes->merge(['class' => 'flex items-center gap-4 py-2']) }}>
    <span class="h-px flex-1 {{ $gelap ? 'bg-white/15' : 'bg-black/10' }}"></span>

    @if($judul)
        <span class="font-display text-sm font-semibold shrink-0 {{ $gelap ? 'text-white/80' : 'text-black/60' }}">
            {{ $judul }}
        </span>
        <span class="h-2.5 w-14 rounded-sm shrink-0 {{ $gelap ? 'motif-anyaman-terang' : 'motif-anyaman' }}"></span>
    @else
        <span class="h-2.5 w-20 rounded-sm shrink-0 {{ $gelap ? 'motif-anyaman-terang' : 'motif-anyaman' }}"></span>
    @endif

    <span class="h-px flex-1 {{ $gelap ? 'bg-white/15' : 'bg-black/10' }}"></span>
</div>