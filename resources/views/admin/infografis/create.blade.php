@extends('layouts.admin')

@section('title', 'Tambah Data Penduduk')

@section('content')
<form method="POST" action="{{ route('admin.infografis.data.store') }}" class="max-w-lg space-y-5">
    @csrf
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Kategori</label>
            <select name="penduduk_kategori_id" required class="w-full rounded-lg border-black/10 text-sm">
                <option value="">Pilih kategori...</option>
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}" @selected(old('penduduk_kategori_id') == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Label</label>
            <input type="text" name="label" value="{{ old('label') }}" required placeholder="Contoh: Petani/Pekebun, SLTA, 0-4 Tahun" class="w-full rounded-lg border-black/10 text-sm">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Jumlah Laki-laki</label>
                <input type="number" name="jumlah_laki" value="{{ old('jumlah_laki', 0) }}" required min="0" class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Jumlah Perempuan</label>
                <input type="number" name="jumlah_perempuan" value="{{ old('jumlah_perempuan', 0) }}" required min="0" class="w-full rounded-lg border-black/10 text-sm">
            </div>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Dusun (opsional, kosongkan jika data desa keseluruhan)</label>
            <select name="dusun_id" class="w-full rounded-lg border-black/10 text-sm">
                <option value="">- Data Desa Keseluruhan -</option>
                @foreach ($dusun as $d)
                    <option value="{{ $d->id }}" @selected(old('dusun_id') == $d->id)>{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Tahun</label>
            <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required class="w-full rounded-lg border-black/10 text-sm">
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan />
        <x-tombol.batal :href="route('admin.infografis.data.index')" />
    </div>
</form>
@endsection