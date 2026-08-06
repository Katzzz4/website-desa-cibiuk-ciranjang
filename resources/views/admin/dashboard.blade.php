@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- PERINGATAN TUNGGAKAN --}}
    @if($tunggakan > 0)
        <a href="{{ route('admin.laporan.index', ['status' => 'menunggu']) }}"
           class="block rounded-2xl p-4 sm:p-5" style="background: #FEF6E7; color: #92600E;">
            <p class="font-display text-base font-semibold">
                {{ $tunggakan }} laporan belum tuntas lebih dari {{ $batasTunggakan }} hari
            </p>
            <p class="text-sm opacity-80 mt-0.5">Klik untuk melihat daftar laporan yang perlu segera ditindaklanjuti.</p>
        </a>
    @endif

    {{-- KARTU RINGKASAN --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-2xl p-5 border border-black/5">
            <p class="text-xs text-black/50 mb-1">Total Laporan</p>
            <p class="font-display text-3xl font-semibold">{{ number_format($totalLaporan, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-black/5">
            <p class="text-xs text-black/50 mb-1">Persentase Selesai</p>
            <p class="font-display text-3xl font-semibold">
                {{ number_format($persenSelesai, 1, ',', '.') }}<span class="text-lg">%</span>
            </p>
            <div class="mt-2 h-1.5 rounded-full bg-black/[0.06] overflow-hidden">
                <div class="h-full rounded-full" style="width: {{ $persenSelesai }}%; background: #157F4F;"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-black/5">
            <p class="text-xs text-black/50 mb-1">Rata-rata Penyelesaian</p>
            <p class="font-display text-3xl font-semibold">
                {{ $rataRataHari ? number_format($rataRataHari, 1, ',', '.') : '—' }}
                <span class="text-sm font-sans font-normal text-black/50">hari</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-black/5">
            <p class="text-xs text-black/50 mb-1">Perlu Ditindaklanjuti</p>
            <p class="font-display text-3xl font-semibold">{{ $menunggu + $diproses }}</p>
            <p class="text-xs text-black/40 mt-1">{{ $menunggu }} menunggu · {{ $diproses }} diproses</p>
        </div>
    </div>

    @if($totalLaporan === 0)
        <div class="bg-white rounded-2xl border border-black/5 p-10 text-center">
            <p class="font-display text-lg font-semibold mb-1">Belum ada laporan masuk</p>
            <p class="text-sm text-black/50">Grafik statistik akan muncul setelah warga mulai mengirimkan pengaduan.</p>
        </div>
    @else

    {{-- TREN BULANAN --}}
    <div class="bg-white rounded-2xl border border-black/5 p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
            <h2 class="font-display text-base font-semibold">Tren Laporan 12 Bulan Terakhir</h2>
        </div>
        <p class="text-xs text-black/40 mb-4 ml-3.5">Perbandingan laporan yang masuk dengan yang berhasil diselesaikan.</p>
        <div style="height: 300px;">
            <canvas id="chart-tren"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- KOMPOSISI STATUS --}}
        <div class="bg-white rounded-2xl border border-black/5 p-5 sm:p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
                <h2 class="font-display text-base font-semibold">Komposisi Status</h2>
            </div>
            <div style="height: 260px;">
                <canvas id="chart-status"></canvas>
            </div>
        </div>

        {{-- PER DUSUN --}}
        <div class="bg-white rounded-2xl border border-black/5 p-5 sm:p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
                <h2 class="font-display text-base font-semibold">Laporan per Dusun</h2>
            </div>
            <div style="height: {{ max(200, $perDusun->count() * 52) }}px;">
                <canvas id="chart-dusun"></canvas>
            </div>
        </div>
    </div>

    {{-- PER KATEGORI --}}
    <div class="bg-white rounded-2xl border border-black/5 p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
            <h2 class="font-display text-base font-semibold">Laporan per Kategori</h2>
        </div>
        <div style="height: {{ max(220, $perKategori->count() * 42) }}px;">
            <canvas id="chart-kategori"></canvas>
        </div>
    </div>

    @endif

    {{-- LAPORAN TERBARU --}}
    <div class="bg-white rounded-2xl border border-black/5 p-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display text-base font-semibold">Laporan Terbaru</h2>
            <a href="{{ route('admin.laporan.index') }}" class="text-sm underline underline-offset-2" style="color: var(--talang);">Lihat semua</a>
        </div>
        <div class="divide-y divide-black/5">
            @forelse ($laporanTerbaru as $l)
                <a href="{{ route('admin.laporan.show', $l) }}" class="flex items-center justify-between gap-3 py-3 hover:bg-black/[0.02] -mx-2 px-2 rounded-lg">
                    <div class="min-w-0">
                        <p class="font-mono-tiket text-xs text-black/40">{{ $l->no_tiket }}</p>
                        <p class="text-sm font-medium truncate">{{ $l->judul }}</p>
                        <p class="text-xs text-black/50">
                            {{ $l->kategori->nama ?? '-' }}
                            @if($l->dusun) · Dusun {{ $l->dusun->nama }} @endif
                        </p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full shrink-0 badge-status-{{ $l->status }}">
                        {{ ucfirst($l->status) }}
                    </span>
                </a>
            @empty
                <p class="text-sm text-black/40 py-6 text-center">Belum ada laporan masuk.</p>
            @endforelse
        </div>
    </div>
</div>

@if($totalLaporan > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const fmt = (v) => Number(v).toLocaleString('id-ID');

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#6B6B63';

    // ---------- TREN BULANAN ----------
    const tren = @json($tren);

    new Chart(document.getElementById('chart-tren'), {
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
                    borderColor: '#157F4F',
                    backgroundColor: '#157F4F',
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
                tooltip: { callbacks: { label: (c) => ' ' + c.dataset.label + ': ' + fmt(c.parsed.y) } },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, callback: fmt },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                },
                x: { grid: { display: false } },
            },
        },
    });

    // ---------- KOMPOSISI STATUS ----------
    new Chart(document.getElementById('chart-status'), {
        type: 'doughnut',
        data: {
            labels: ['Menunggu Verifikasi', 'Diproses', 'Selesai', 'Ditolak'],
            datasets: [{
                data: [{{ $menunggu }}, {{ $diproses }}, {{ $selesai }}, {{ $ditolak }}],
                backgroundColor: ['#C98A16', '#2563A8', '#157F4F', '#C0392B'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } },
                tooltip: {
                    callbacks: {
                        label: (c) => {
                            const total = c.dataset.data.reduce((a, b) => a + b, 0);
                            const persen = total ? ((c.parsed / total) * 100).toFixed(1).replace('.', ',') : 0;
                            return ' ' + c.label + ': ' + fmt(c.parsed) + ' (' + persen + '%)';
                        },
                    },
                },
            },
        },
    });

    // ---------- PER DUSUN ----------
    const dusun = @json($perDusun);

    new Chart(document.getElementById('chart-dusun'), {
        type: 'bar',
        data: {
            labels: dusun.map(d => d.label),
            datasets: [{
                data: dusun.map(d => d.total),
                backgroundColor: '#2563A8',
                borderRadius: 5,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (c) => ' ' + fmt(c.parsed.x) + ' laporan' } },
            },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0, callback: fmt }, grid: { color: 'rgba(0,0,0,0.05)' } },
                y: { grid: { display: false }, ticks: { autoSkip: false } },
            },
        },
    });

    // ---------- PER KATEGORI ----------
    const kategori = @json($perKategori);

    new Chart(document.getElementById('chart-kategori'), {
        type: 'bar',
        data: {
            labels: kategori.map(k => k.label),
            datasets: [{
                data: kategori.map(k => k.total),
                backgroundColor: '#0E5C3A',
                borderRadius: 5,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (c) => ' ' + fmt(c.parsed.x) + ' laporan' } },
            },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0, callback: fmt }, grid: { color: 'rgba(0,0,0,0.05)' } },
                y: { grid: { display: false }, ticks: { autoSkip: false, font: { size: 11 } } },
            },
        },
    });
</script>
@endif
@endsection