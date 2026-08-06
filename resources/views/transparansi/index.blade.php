@extends('layouts.publik')

@section('title', 'Transparansi Pengaduan')

@section('meta_judul', 'Transparansi Penanganan Pengaduan Warga')
@section('meta_deskripsi', 'Lihat berapa laporan warga yang masuk, berapa yang sudah diselesaikan, dan berapa lama rata-rata waktu penanganannya.')
@section('main-class', '')

@section('content')

{{-- ============ HERO ============ --}}
<section class="text-white" style="background: var(--sawah-dark);">
    <div class="wadah py-14 sm:py-16">
        <p class="label-bagian" style="color: rgba(255,255,255,0.6);">Akuntabilitas Pelayanan</p>
        <h1 class="font-display text-3xl sm:text-4xl font-bold mt-2 leading-tight">
            Transparansi Penanganan Pengaduan
        </h1>
        <p class="text-sm sm:text-base text-white/70 mt-4 max-w-2xl leading-relaxed">
            Halaman ini menampilkan bagaimana laporan warga ditangani oleh Pemerintah Desa
            {{ $profil->nama_desa ?? 'Cibiuk' }}. Angka diperbarui otomatis setiap kali ada
            laporan masuk atau selesai ditindaklanjuti.
        </p>
    </div>
</section>

@if($total === 0)
    <section class="wadah py-16">
        <div class="kartu p-10 text-center">
            <p class="font-display text-lg font-bold mb-1">Belum ada laporan masuk</p>
            <p class="text-sm" style="color: var(--lembut);">
                Statistik akan muncul di sini setelah warga mulai menyampaikan pengaduan.
            </p>
            <a href="{{ route('pengaduan.create') }}"
               class="tombol-utama inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-lg text-sm font-semibold">
                Sampaikan Pengaduan
            </a>
        </div>
    </section>
@else

{{-- ============ ANGKA UTAMA ============ --}}
<section style="background: var(--kertas);">
    <div class="wadah py-12">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-px rounded-xl overflow-hidden"
             style="background: var(--garis); border: 1px solid var(--garis);">

            <div class="bg-white px-5 py-6">
                <p class="font-display text-3xl font-bold" style="color: var(--sawah-dark);">
                    <span data-angka="{{ $total }}" data-desimal="0">0</span>
                </p>
                <p class="text-xs mt-1.5" style="color: var(--lembut);">Laporan Masuk</p>
            </div>

            <div class="bg-white px-5 py-6">
                <p class="font-display text-3xl font-bold" style="color: #157F4F;">
                    <span data-angka="{{ $selesai }}" data-desimal="0">0</span>
                </p>
                <p class="text-xs mt-1.5" style="color: var(--lembut);">Selesai Ditangani</p>
            </div>

            <div class="bg-white px-5 py-6">
                <p class="font-display text-3xl font-bold" style="color: var(--sawah-dark);">
                    <span data-angka="{{ $persenSelesai }}" data-desimal="0">0</span><span class="text-xl">%</span>
                </p>
                <p class="text-xs mt-1.5" style="color: var(--lembut);">Tingkat Penyelesaian</p>
                <div class="mt-2 h-1.5 rounded-full overflow-hidden" style="background: var(--garis);">
                    <div class="h-full rounded-full" style="width: {{ $persenSelesai }}%; background: #157F4F;"></div>
                </div>
            </div>

            <div class="bg-white px-5 py-6">
                <p class="font-display text-3xl font-bold" style="color: var(--sawah-dark);">
                    {{ $rataRataHari ? number_format($rataRataHari, 1, ',', '.') : '—' }}
                    <span class="text-base font-medium" style="color: var(--lembut);">hari</span>
                </p>
                <p class="text-xs mt-1.5" style="color: var(--lembut);">Rata-rata Penyelesaian</p>
            </div>
        </div>

        {{-- rincian status --}}
        <div class="flex flex-wrap gap-2 mt-5 text-xs">
            @php
                $statusRinci = [
                    ['Menunggu verifikasi', $menunggu, '#FEF6E7', '#92600E'],
                    ['Sedang diproses',     $diproses, '#E8F0FB', '#1B4B8F'],
                    ['Selesai',             $selesai,  '#E7F3EC', '#0E5C3A'],
                    ['Tidak dapat ditindaklanjuti', $ditolak, '#FBEAEA', '#96261F'],
                ];
            @endphp
            @foreach ($statusRinci as [$label, $jumlah, $bg, $fg])
                <span class="px-3 py-1.5 rounded-full font-medium" style="background: {{ $bg }}; color: {{ $fg }};">
                    {{ $label }}: {{ number_format($jumlah, 0, ',', '.') }}
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ TREN BULANAN ============ --}}
<section class="border-t" style="border-color: var(--garis);">
    <div class="wadah py-14">
        <div class="reveal-kiri mb-6">
            <p class="label-bagian">Perkembangan</p>
            <h2 class="font-display text-2xl font-bold mt-1.5">Laporan Masuk dan Selesai</h2>
            <p class="text-sm mt-2 max-w-xl leading-relaxed" style="color: var(--lembut);">
                Perbandingan jumlah laporan yang masuk dengan yang berhasil diselesaikan
                selama dua belas bulan terakhir.
            </p>
        </div>

        <div class="kartu p-5 sm:p-6">
            <div style="height: 300px;">
                <canvas id="grafik-tren"></canvas>
            </div>
        </div>
    </div>
</section>

{{-- ============ SEBARAN ============ --}}
<section class="border-t" style="border-color: var(--garis); background: var(--kertas);">
    <div class="wadah py-14 grid lg:grid-cols-2 gap-6">

        <div>
            <h2 class="font-display text-xl font-bold mb-4">Jenis Laporan Terbanyak</h2>
            <div class="kartu p-5">
                <div style="height: {{ max(200, $perKategori->count() * 42) }}px;">
                    <canvas id="grafik-kategori"></canvas>
                </div>
            </div>
        </div>

        <div>
            <h2 class="font-display text-xl font-bold mb-4">Sebaran per Dusun</h2>
            <div class="kartu p-5">
                <div style="height: {{ max(200, $perDusun->count() * 52) }}px;">
                    <canvas id="grafik-dusun"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ PENANGANAN TERAKHIR ============ --}}
@if($penangananTerakhir->count())
    <section class="border-t" style="border-color: var(--garis);">
        <div class="wadah py-14">
            <div class="reveal-kiri mb-6">
                <p class="label-bagian">Tindak Lanjut</p>
                <h2 class="font-display text-2xl font-bold mt-1.5">Penanganan Terakhir</h2>
                <p class="text-sm mt-2 max-w-xl leading-relaxed" style="color: var(--lembut);">
                    Laporan yang paling baru selesai ditangani. Demi menjaga privasi warga,
                    identitas pelapor dan lokasi rincinya tidak ditampilkan.
                </p>
            </div>

            <div class="kartu overflow-hidden">
                @foreach ($penangananTerakhir as $p)
                    <div class="flex flex-wrap items-center gap-3 px-5 py-4 {{ !$loop->last ? 'border-b' : '' }}"
                         style="border-color: var(--garis);">

                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full shrink-0"
                              style="background:#E7F3EC; color:#0E5C3A;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold">{{ $p['kategori'] }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--lembut);">
                                @if($p['dusun']) Dusun {{ $p['dusun'] }} &middot; @endif
                                Selesai {{ $p['selesai']->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <span class="text-xs px-2.5 py-1 rounded-full shrink-0"
                              style="background: var(--sawah-light); color: var(--sawah-dark);">
                            {{ $p['lama'] < 1 ? 'Kurang dari sehari' : $p['lama'] . ' hari' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endif

{{-- ============ AJAKAN ============ --}}
<section class="border-t" style="border-color: var(--garis); background: var(--kertas);">
    <div class="wadah py-12">
        <div class="kartu p-6 sm:p-8 flex flex-wrap items-center justify-between gap-5">
            <div class="max-w-md">
                <h2 class="font-display text-lg font-bold">Punya keluhan yang belum tersampaikan?</h2>
                <p class="text-sm mt-2 leading-relaxed" style="color: var(--lembut);">
                    Setiap laporan mendapat nomor tiket sehingga Anda dapat memantau
                    perkembangannya sendiri, tanpa perlu datang ke kantor desa.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('pengaduan.create') }}"
                   class="tombol-utama inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold">
                    Buat Laporan
                </a>
                <a href="{{ route('pengaduan.lacak.form') }}"
                   class="tombol-garis inline-flex items-center gap-2 bg-white px-5 py-2.5 rounded-lg text-sm font-medium">
                    Lacak Laporan
                </a>
            </div>
        </div>
    </div>
</section>

@if($total > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const angka = (v) => Number(v).toLocaleString('id-ID');

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#5D635F';

    const tren = @json($tren);

    new Chart(document.getElementById('grafik-tren'), {
        data: {
            labels: tren.map(t => t.label),
            datasets: [
                {
                    type: 'bar',
                    label: 'Laporan Masuk',
                    data: tren.map(t => t.masuk),
                    backgroundColor: '#0E5C3A',
                    borderRadius: 5,
                    order: 2,
                },
                {
                    type: 'line',
                    label: 'Selesai Ditangani',
                    data: tren.map(t => t.selesai),
                    borderColor: '#C98A16',
                    backgroundColor: '#C98A16',
                    borderWidth: 2.5,
                    tension: 0.35,
                    pointRadius: 3,
                    order: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } },
                tooltip: { callbacks: { label: (c) => ' ' + c.dataset.label + ': ' + angka(c.parsed.y) } },
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0, callback: angka }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } },
            },
        },
    });

    function grafikBatang(idKanvas, data, warna) {
        new Chart(document.getElementById(idKanvas), {
            type: 'bar',
            data: {
                labels: data.map(d => d.label),
                datasets: [{ data: data.map(d => d.total), backgroundColor: warna, borderRadius: 5 }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (c) => ' ' + angka(c.parsed.x) + ' laporan' } },
                },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0, callback: angka }, grid: { color: 'rgba(0,0,0,0.05)' } },
                    y: { grid: { display: false }, ticks: { autoSkip: false, font: { size: 11 } } },
                },
            },
        });
    }

    grafikBatang('grafik-kategori', @json($perKategori), '#0E5C3A');
    grafikBatang('grafik-dusun', @json($perDusun), '#157F4F');
</script>
@endif
@endsection