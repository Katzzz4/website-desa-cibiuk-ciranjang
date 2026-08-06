@extends('layouts.admin')

@section('title', 'Ringkasan Penduduk')

@section('content')
<x-nav-infografis />


<form method="POST" action="{{ route('admin.infografis.ringkasan.update') }}" class="max-w-xl space-y-5">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Tahun Data</label>
            <input type="number" name="tahun" value="{{ old('tahun', $ringkasan->tahun ?? date('Y')) }}" required class="w-full rounded-lg border-black/10 text-sm">
            <p class="text-xs text-black/40 mt-1">Kalau tahun ini belum ada, akan otomatis dibuat baru.</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Total Laki-laki</label>
                <input type="number" name="total_laki" value="{{ old('total_laki', $ringkasan->total_laki ?? 0) }}" required class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Total Perempuan</label>
                <input type="number" name="total_perempuan" value="{{ old('total_perempuan', $ringkasan->total_perempuan ?? 0) }}" required class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Jumlah KK</label>
                <input type="number" name="total_kk" value="{{ old('total_kk', $ringkasan->total_kk ?? '') }}" class="w-full rounded-lg border-black/10 text-sm">
            </div>
        </div>

        <hr class="border-black/5">
        <p class="text-xs text-black/50 uppercase tracking-wide">Mutasi Penduduk</p>

        <div class="grid sm:grid-cols-2 gap-4">
            @foreach ([['lahir', 'Lahir'], ['mati', 'Meninggal'], ['datang', 'Datang'], ['pergi', 'Pindah Keluar']] as [$key, $label])
                <div class="grid grid-cols-2 gap-2 items-end">
                    <div>
                        <label class="text-xs text-black/50 mb-1 block">{{ $label }} (L)</label>
                        <input type="number" name="{{ $key }}_laki" value="{{ old("{$key}_laki", $ringkasan->{"{$key}_laki"} ?? 0) }}" class="w-full rounded-lg border-black/10 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-black/50 mb-1 block">{{ $label }} (P)</label>
                        <input type="number" name="{{ $key }}_perempuan" value="{{ old("{$key}_perempuan", $ringkasan->{"{$key}_perempuan"} ?? 0) }}" class="w-full rounded-lg border-black/10 text-sm">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-tombol.simpan />
</form>
@endsection