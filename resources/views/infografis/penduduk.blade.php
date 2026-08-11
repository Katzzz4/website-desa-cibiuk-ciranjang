@extends('layouts.publik')

@section('title', 'Infografis Penduduk')

@section('content')
<div class="space-y-10">

    {{-- HERO --}}
    <div class="reveal-skala relative overflow-hidden rounded-3xl text-white terrace-texture p-8 sm:p-10" style="background: linear-gradient(150deg, var(--sawah-dark), var(--sawah-darker));">
        <p class="text-xs font-medium tracking-widest uppercase" style="color: var(--padi-light);">Data Kependudukan</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold mt-2 leading-tight">Infografis Penduduk</h1>
        <p class="text-sm text-white/70 mt-2">Desa Cibiuk, tahun data {{ $ringkasan->tahun ?? '-' }}</p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-8">
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">{{ $ringkasan ? number_format($ringkasan->total_laki + $ringkasan->total_perempuan, 0, ',', '.') : '-' }}</p>
                <p class="text-xs text-white/60 mt-0.5">Total Penduduk</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">{{ $ringkasan ? number_format($ringkasan->total_laki, 0, ',', '.') : '-' }}</p>
                <p class="text-xs text-white/60 mt-0.5">Laki-laki</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">{{ $ringkasan ? number_format($ringkasan->total_perempuan, 0, ',', '.') : '-' }}</p>
                <p class="text-xs text-white/60 mt-0.5">Perempuan</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <p class="font-display text-2xl font-semibold">{{ $ringkasan?->total_kk ? number_format($ringkasan->total_kk, 0, ',', '.') : '—' }}</p>
                <p class="text-xs text-white/60 mt-0.5">Kepala Keluarga</p>
            </div>
        </div>
    </div>

    {{-- MUTASI PENDUDUK --}}
    @if($ringkasan)
        <div class="reveal bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
            <div class="flex items-center gap-2 mb-5">
                <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
                <h2 class="font-display text-lg font-semibold">Perkembangan Penduduk {{ $ringkasan->tahun }}</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="font-display text-xl font-semibold" style="color: var(--sawah-dark);">{{ $ringkasan->lahir_laki + $ringkasan->lahir_perempuan }}</p>
                    <p class="text-xs text-black/50 mt-1">Lahir</p>
                </div>
                <div>
                    <p class="font-display text-xl font-semibold" style="color: var(--sawah-dark);">{{ $ringkasan->mati_laki + $ringkasan->mati_perempuan }}</p>
                    <p class="text-xs text-black/50 mt-1">Meninggal</p>
                </div>
                <div>
                    <p class="font-display text-xl font-semibold" style="color: var(--sawah-dark);">{{ $ringkasan->datang_laki + $ringkasan->datang_perempuan }}</p>
                    <p class="text-xs text-black/50 mt-1">Datang</p>
                </div>
                <div>
                    <p class="font-display text-xl font-semibold" style="color: var(--sawah-dark);">{{ $ringkasan->pergi_laki + $ringkasan->pergi_perempuan }}</p>
                    <p class="text-xs text-black/50 mt-1">Pindah Keluar</p>
                </div>
            </div>
        </div>
    @endif

    {{-- DONUT L/P --}}
    <div class="reveal bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
        <div class="flex items-center gap-2 mb-5">
            <span class="w-1.5 h-5 rounded-full" style="background: var(--padi);"></span>
            <h2 class="font-display text-lg font-semibold">Berdasarkan Jenis Kelamin</h2>
        </div>
        <div class="max-w-xs mx-auto" style="height: 280px;">
            <canvas id="chart-gender"></canvas>
        </div>
    </div>

    {{-- CHART PER KATEGORI --}}
    @foreach ($kategori as $k)
        @if($k->data->count())
            <div class="reveal bg-white rounded-2xl border border-black/5 p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-1.5 h-5 rounded-full" style="background: var(--talang);"></span>
                    <h2 class="font-display text-lg font-semibold">Berdasarkan {{ $k->nama }}</h2>
                </div>

                <div style="height: {{ max(240, $k->data->count() * 30) }}px;">
                    <canvas id="chart-kategori-{{ $k->id }}"></canvas>
                </div>

                {{-- tabel pendukung, berguna kalau kategori punya banyak label --}}
                <div class="mt-5 grid sm:grid-cols-2 gap-x-8 text-sm">
                    @foreach ($k->data as $d)
                        <div class="flex justify-between border-b border-black/5 py-1.5">
                            <span class="text-black/60">{{ $d->label }}</span>
                            <span class="font-medium">{{ number_format($d->jumlah_total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const paletteWarna = ['#0E5C3A', '#157F4F', '#2563A8', '#8A5A1E', '#5C7A5F', '#B5843A', '#3E6B6E', '#6E8F72'];

    @if($ringkasan)
    new Chart(document.getElementById('chart-gender'), {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [{{ $ringkasan->total_laki }}, {{ $ringkasan->total_perempuan }}],
                backgroundColor: ['#0E5C3A', '#157F4F'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: (ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const persen = ((ctx.parsed / total) * 100).toFixed(1).replace('.', ',');
                            return ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID') + ' (' + persen + '%)';
                        }
                    }
                }
            },
            cutout: '65%',
        }
    });
    @endif

    @foreach ($kategori as $k)
        @if($k->data->count())
        new Chart(document.getElementById('chart-kategori-{{ $k->id }}'), {
            type: 'bar',
            data: {
                labels: @json($k->data->pluck('label')),
                datasets: [{
                    data: @json($k->data->pluck('jumlah_total')),
                    backgroundColor: '{{ ['mata-pencaharian' => '#0E5C3A', 'pendidikan' => '#2563A8', 'agama' => '#157F4F'][$k->slug] ?? '#0E5C3A' }}',
                    borderRadius: 6,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false, // ikuti tinggi container, bukan rasio bawaan
                layout: { padding: { right: 12 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' ' + ctx.parsed.x.toLocaleString('id-ID') + ' orang',
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { callback: (v) => v.toLocaleString('id-ID') },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                    },
                    y: {
                        grid: { display: false },
                        ticks: { autoSkip: false, font: { size: 11 } },
                    },
                },
            }
        });
        @endif
    @endforeach
</script>
@endsection