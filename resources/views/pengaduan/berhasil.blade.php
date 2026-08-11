@extends('layouts.publik')

@section('title', 'Laporan Terkirim')

@section('content')
<div class="max-w-3xl mx-auto">
<div class="reveal-skala bg-white rounded-2xl border border-black/5 p-8 text-center">
    <p class="text-xs font-medium tracking-wide uppercase mb-2" style="color: var(--talang);">Laporan Berhasil Dikirim</p>
    <h1 class="font-display text-2xl font-semibold mb-4">Terima kasih atas laporan Anda</h1>

    <p class="text-sm text-black/50 mb-1">Simpan nomor tiket ini untuk melacak status laporan Anda:</p>
    <div class="inline-block font-mono-tiket text-2xl font-medium px-6 py-3 rounded-xl my-3" style="background: var(--kertas); border: 1px dashed var(--padi);">
        {{ $laporan->no_tiket }}
    </div>

    <p class="text-sm text-black/50 mb-6">
        Petugas desa akan memverifikasi laporan Anda. Anda dapat mengecek perkembangannya kapan saja
        menggunakan nomor tiket di atas.
    </p>

    <div class="flex justify-center gap-3">
        <a href="{{ route('pengaduan.lacak.form') }}?no_tiket={{ $laporan->no_tiket }}"
           class="px-5 py-2.5 rounded-xl text-sm text-white" style="background: var(--sawah-dark);">
            Lacak Laporan Ini
        </a>
        <a href="{{ route('pengaduan.create') }}"
           class="px-5 py-2.5 rounded-xl text-sm border border-black/10">
            Buat Laporan Lain
        </a>
    </div>
</div>
</div>
@endsection