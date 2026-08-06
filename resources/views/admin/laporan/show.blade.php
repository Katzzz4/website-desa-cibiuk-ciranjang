@extends('layouts.admin')

@section('title', 'Detail Laporan')

@section('content')
<a href="{{ route('admin.laporan.index') }}"
   class="inline-flex items-center gap-1.5 text-sm mb-5 hover:underline underline-offset-4"
   style="color: var(--talang);">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>
    Kembali ke Daftar Laporan
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- DETAIL --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="font-mono-tiket text-xs text-black/40">{{ $laporan->no_tiket }}</span>
                <span class="text-xs px-2.5 py-1 rounded-full badge-status-{{ $laporan->status }}">
                    {{ ucfirst($laporan->status) }}
                </span>
            </div>
            <h2 class="font-display text-xl font-semibold mb-1">{{ $laporan->judul }}</h2>
            <p class="text-sm text-black/50 mb-4">
                {{ $laporan->kategori->nama ?? '-' }}
                @if($laporan->dusun) · Dusun {{ $laporan->dusun->nama }} @endif
                · Kejadian {{ $laporan->tanggal_kejadian->format('d M Y') }}
            </p>
            <p class="text-sm leading-relaxed">{{ $laporan->deskripsi }}</p>

            @if($laporan->alamat_lokasi || $laporan->latitude)
                <div class="mt-4 text-sm text-black/60">
                    📍 {{ $laporan->alamat_lokasi ?? "{$laporan->latitude}, {$laporan->longitude}" }}
                </div>
            @endif

            @if($laporan->foto->count())
                <div class="mt-4 grid grid-cols-3 gap-3">
                    @foreach ($laporan->foto as $f)
                        <img src="{{ Storage::url($f->file_path) }}" class="rounded-lg aspect-square object-cover">
                    @endforeach
                </div>
            @endif

            <div class="mt-5 pt-4 border-t border-black/5 text-sm text-black/60">
                <strong>Pelapor:</strong>
                {{ $laporan->anonim ? 'Anonim' : ($laporan->nama_pelapor ?? '-') }}
                @if(!$laporan->anonim && $laporan->no_hp) · {{ $laporan->no_hp }} @endif
            </div>
        </div>

        {{-- RIWAYAT --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <h3 class="font-display text-base font-semibold mb-4">Riwayat Tanggapan</h3>
            <div class="space-y-4">
                @forelse ($laporan->tanggapan as $t)
                    <div class="flex gap-3 text-sm">
                        <span class="text-xs px-2 py-0.5 h-fit rounded-full badge-status-{{ $t->status_baru }}">
                            {{ ucfirst($t->status_baru) }}
                        </span>
                        <div>
                            @if($t->isi_tanggapan)
                                <p>{{ $t->isi_tanggapan }}</p>
                            @endif
                            <p class="text-xs text-black/40 mt-0.5">
                                {{ $t->user->name ?? 'Sistem' }} · {{ $t->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-black/40">Belum ada tanggapan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- FORM UPDATE STATUS --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6 h-fit">
        <h3 class="font-display text-base font-semibold mb-4">Ubah Status</h3>
        <form method="POST" action="{{ route('admin.laporan.update-status', $laporan) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="text-xs text-black/50 mb-1 block">Status</label>
                <select name="status" id="status-select" class="w-full rounded-lg border-black/10 text-sm">
                    @foreach (['menunggu' => 'Menunggu Verifikasi', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $label)
                        <option value="{{ $val }}" @selected($laporan->status == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-black/50 mb-1 block">Catatan / Tanggapan</label>
                <textarea name="isi_tanggapan" rows="3" class="w-full rounded-lg border-black/10 text-sm" placeholder="Opsional..."></textarea>
            </div>

            <div id="alasan-tolak-wrap" class="{{ $laporan->status == 'ditolak' ? '' : 'hidden' }}">
                <label class="text-xs text-black/50 mb-1 block">Alasan Ditolak</label>
                <textarea name="alasan_tolak" rows="2" class="w-full rounded-lg border-black/10 text-sm">{{ $laporan->alasan_tolak }}</textarea>
            </div>

            <div id="dokumentasi-wrap" class="{{ $laporan->status == 'selesai' ? '' : 'hidden' }}">
                <label class="text-xs text-black/50 mb-1 block">Foto Dokumentasi Selesai</label>
                <input type="file" name="dokumentasi_selesai" accept="image/*" class="w-full text-sm">
            </div>

            @error('status') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            @error('alasan_tolak') <p class="text-xs text-red-600">{{ $message }}</p> @enderror

            <x-tombol.simpan label="Simpan Perubahan" class="w-full justify-center" />
        </form>
    </div>
</div>

<script>
    const select = document.getElementById('status-select');
    const alasanWrap = document.getElementById('alasan-tolak-wrap');
    const dokWrap = document.getElementById('dokumentasi-wrap');
    select.addEventListener('change', () => {
        alasanWrap.classList.toggle('hidden', select.value !== 'ditolak');
        dokWrap.classList.toggle('hidden', select.value !== 'selesai');
    });
</script>
@endsection