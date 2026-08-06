@extends('layouts.publik')

@section('title', 'Lacak Laporan')

@section('meta_judul', 'Lacak Status Laporan Anda')
@section('meta_deskripsi', 'Masukkan nomor tiket untuk melihat perkembangan penanganan laporan yang Anda sampaikan kepada pemerintah desa.')

@section('content')
<div class="max-w-3xl mx-auto">
<div class="space-y-8">

    <div>
        <p class="text-xs font-medium tracking-[0.2em] uppercase" style="color: var(--talang);">Lacak Perkembangan</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold mt-1">Cek Status Laporan Anda</h1>
        <p class="text-sm text-black/50 mt-2">Masukkan nomor tiket yang Anda terima saat pertama kali mengirim laporan.</p>
    </div>

    <form method="POST" action="{{ route('pengaduan.lacak') }}" class="bg-white rounded-2xl border border-black/5 p-5 flex flex-col sm:flex-row gap-3">
        @csrf
        <input type="text" name="no_tiket" required
               value="{{ old('no_tiket', request('no_tiket')) }}"
               placeholder="Contoh: LPR-20260727-0001"
               class="flex-1 rounded-lg border-black/10 text-sm font-mono-tiket">
        <button class="px-6 py-2.5 rounded-lg text-sm text-white shrink-0" style="background: var(--sawah-dark);">Lacak</button>
    </form>

    @if ($errors->any())
        <div class="rounded-xl p-4 text-sm" style="background:#FBEAEA; color:#96261F;">
            {{ $errors->first('no_tiket') }}
        </div>
    @endif

    @if (isset($laporan))
        @php
            $statusLabel = [
                'menunggu' => 'Menunggu Verifikasi',
                'diproses' => 'Diproses',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak',
            ];
            $warna = [
                'menunggu' => ['bg' => '#FEF6E7', 'fg' => '#92600E', 'solid' => '#C98A16'],
                'diproses' => ['bg' => '#E8F0FB', 'fg' => '#1B4B8F', 'solid' => '#2563A8'],
                'selesai'  => ['bg' => '#E7F3EC', 'fg' => '#0E5C3A', 'solid' => '#157F4F'],
                'ditolak'  => ['bg' => '#FBEAEA', 'fg' => '#96261F', 'solid' => '#C0392B'],
            ];
            $c = $warna[$laporan->status];

            // urutan tahapan normal; laporan ditolak keluar dari alur ini
            $tahapan = ['menunggu' => 'Menunggu Verifikasi', 'diproses' => 'Diproses', 'selesai' => 'Selesai'];
            $urutanSaatIni = array_search($laporan->status, array_keys($tahapan));
            $ditolak = $laporan->status === 'ditolak';
        @endphp

        <div class="bg-white rounded-2xl border border-black/5 p-6 sm:p-8 reveal">

            {{-- KEPALA --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <span class="font-mono-tiket text-xs text-black/40">{{ $laporan->no_tiket }}</span>
                <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: {{ $c['bg'] }}; color: {{ $c['fg'] }};">
                    {{ $statusLabel[$laporan->status] }}
                </span>
            </div>

            {{-- ============ STEPPER ============ --}}
            @if($ditolak)
                <div class="rounded-2xl p-5 mb-6" style="background: {{ $c['bg'] }}; color: {{ $c['fg'] }};">
                    <p class="font-display text-base font-semibold mb-1">Laporan Ditolak</p>
                    @if($laporan->alasan_tolak)
                        <p class="text-sm opacity-90">{{ $laporan->alasan_tolak }}</p>
                    @else
                        <p class="text-sm opacity-90">Tidak ada alasan yang dicantumkan.</p>
                    @endif
                </div>
            @else
                <div class="mb-8">
                    {{-- versi horizontal (layar sedang ke atas) --}}
                    <div class="hidden sm:flex items-start">
                        @foreach ($tahapan as $kunci => $label)
                            @php
                                $index = $loop->index;
                                $tercapai = $index <= $urutanSaatIni;
                                $aktif = $index === $urutanSaatIni;
                                $solid = $warna[$kunci]['solid'];
                            @endphp

                            <div class="flex-1 flex flex-col items-center relative">
                                {{-- garis penghubung --}}
                                @unless($loop->first)
                                    <div class="absolute top-[15px] right-1/2 w-full h-[3px] rounded-full"
                                         style="background: {{ $tercapai ? $solid : 'rgba(0,0,0,0.08)' }};"></div>
                                @endunless

                                <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold
                                            {{ $aktif ? 'ring-4' : '' }}"
                                     style="background: {{ $tercapai ? $solid : '#E5E8E6' }};
                                            color: {{ $tercapai ? '#fff' : 'rgba(0,0,0,0.35)' }};
                                            {{ $aktif ? '--tw-ring-color: ' . $solid . '33;' : '' }}">
                                    @if($tercapai && !$aktif)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>

                                <p class="text-xs mt-2.5 text-center px-1 {{ $aktif ? 'font-semibold' : 'text-black/45' }}"
                                   style="{{ $aktif ? 'color: ' . $solid . ';' : '' }}">
                                    {{ $label }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- versi vertikal (HP) --}}
                    <div class="sm:hidden space-y-0">
                        @foreach ($tahapan as $kunci => $label)
                            @php
                                $index = $loop->index;
                                $tercapai = $index <= $urutanSaatIni;
                                $aktif = $index === $urutanSaatIni;
                                $solid = $warna[$kunci]['solid'];
                            @endphp
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-semibold shrink-0"
                                         style="background: {{ $tercapai ? $solid : '#E5E8E6' }}; color: {{ $tercapai ? '#fff' : 'rgba(0,0,0,0.35)' }};">
                                        @if($tercapai && !$aktif)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    @unless($loop->last)
                                        <div class="w-[3px] flex-1 min-h-[26px] rounded-full my-1"
                                             style="background: {{ $index < $urutanSaatIni ? $solid : 'rgba(0,0,0,0.08)' }};"></div>
                                    @endunless
                                </div>
                                <p class="text-sm pt-1 pb-3 {{ $aktif ? 'font-semibold' : 'text-black/45' }}"
                                   style="{{ $aktif ? 'color: ' . $solid . ';' : '' }}">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- RINGKASAN LAPORAN --}}
            <h2 class="font-display text-lg font-semibold mb-1">{{ $laporan->judul }}</h2>
            <p class="text-sm text-black/50 mb-6">
                {{ $laporan->kategori->nama ?? '-' }}
                @if($laporan->dusun) · Dusun {{ $laporan->dusun->nama }} @endif
                · Dilaporkan {{ $laporan->created_at->translatedFormat('d M Y') }}
            </p>

            {{-- RIWAYAT --}}
            <div class="pt-5 border-t border-black/5">
                <p class="text-xs uppercase tracking-widest text-black/35 mb-4">Riwayat Penanganan</p>
                <div class="space-y-4">
                    @foreach ($laporan->tanggapan as $t)
                        <div class="flex gap-3 text-sm">
                            <div class="w-2 h-2 rounded-full mt-1.5 shrink-0"
                                 style="background: {{ $warna[$t->status_baru]['solid'] ?? 'var(--padi)' }};"></div>
                            <div>
                                <p class="font-medium">{{ $statusLabel[$t->status_baru] ?? ucfirst($t->status_baru) }}</p>
                                @if($t->isi_tanggapan)
                                    <p class="text-black/60 mt-0.5">{{ $t->isi_tanggapan }}</p>
                                @endif
                                <p class="text-xs text-black/40 mt-0.5">{{ $t->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
</div>
@endsection