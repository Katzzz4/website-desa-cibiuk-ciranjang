@extends('layouts.publik')

@section('title', 'Dokumen Desa')
@section('main-class', '')

@section('meta_judul', 'Dokumen Desa Cibiuk')
@section('meta_deskripsi', 'Unduh peraturan desa, surat keputusan, serta format surat untuk keperluan administrasi warga.')

@section('content')

{{-- ============ HEADER ============ --}}
<section class="border-b" style="border-color: var(--garis); background: var(--kertas);">
    <div class="wadah py-12 text-center">
        <p class="label-bagian">Informasi Desa</p>
        <h1 class="font-display text-2xl sm:text-3xl font-bold mt-1.5">Dokumen Desa</h1>
        <p class="text-sm mt-3 max-w-xl mx-auto leading-relaxed" style="color: var(--lembut);">
            Silakan pilih jenis dokumen yang Anda cari.
        </p>
    </div>
</section>

{{-- ============ PILIHAN KLASIFIKASI ============ --}}
<section class="wadah py-14">
    <div class="grid sm:grid-cols-2 gap-5 max-w-4xl mx-auto">

        @php
            $ikon = [
                'produk_hukum' => 'M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z',
                'surat_menyurat' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
            ];
        @endphp

        @foreach (\App\Models\Dokumen::KLASIFIKASI as $kunci => $k)
            @php $total = $jumlah[$kunci] ?? 0; @endphp

            <a href="{{ route('dokumen.daftar', $kunci) }}"
               class="reveal-skala kartu kartu-tautan p-7 sm:p-9 flex flex-col items-center text-center"
               style="transition-delay: {{ $loop->index * 70 }}ms;">

                <span class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-5"
                      style="background: var(--sawah-light); color: var(--sawah-dark);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.4" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ikon[$kunci] }}" />
                    </svg>
                </span>

                <h2 class="font-display text-lg sm:text-xl font-bold">{{ $k['label'] }}</h2>

                <p class="text-sm mt-2.5 leading-relaxed" style="color: var(--lembut);">
                    {{ $k['ket'] }}
                </p>

                <span class="mt-5 text-xs px-3 py-1.5 rounded-full font-medium"
                      style="background: var(--sawah-light); color: var(--sawah-dark);">
                    @if($total > 0)
                        {{ $total }} dokumen tersedia
                    @else
                        Belum ada dokumen
                    @endif
                </span>
            </a>
        @endforeach
    </div>

    <p class="text-center text-xs mt-8" style="color: var(--lembut);">
        Tidak menemukan yang Anda cari?
        <a href="{{ route('pencarian.index') }}" class="hover:underline underline-offset-4" style="color: var(--padi);">
            Coba pencarian
        </a>
        atau hubungi kantor desa.
    </p>
</section>
@endsection