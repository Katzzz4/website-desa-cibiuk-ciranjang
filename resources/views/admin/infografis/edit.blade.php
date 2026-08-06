@extends('layouts.admin')

@section('title', 'Edit Data Penduduk')

@section('content')
<form method="POST" action="{{ route('admin.infografis.data.update', $data) }}" class="max-w-lg space-y-5">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Kategori</label>
            <select name="penduduk_kategori_id" required class="w-full rounded-lg border-black/10 text-sm">
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}" @selected(old('penduduk_kategori_id', $data->penduduk_kategori_id) == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Label</label>
            <input type="text" name="label" value="{{ old('label', $data->label) }}" required class="w-full rounded-lg border-black/10 text-sm">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-black/50 mb-1 block">Jumlah Laki-laki</label>
                <input type="number" name="jumlah_laki" value="{{ old('jumlah_laki', $data->jumlah_laki) }}" required min="0" class="w-full rounded-lg border-black/10 text-sm">
            </div>
            <div>
                <label class="text-xs text-black/50 mb-1 block">Jumlah Perempuan</label>
                <input type="number" name="jumlah_perempuan" value="{{ old('jumlah_perempuan', $data->jumlah_perempuan) }}" required min="0" class="w-full rounded-lg border-black/10 text-sm">
            </div>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Dusun</label>
            <select name="dusun_id" class="w-full rounded-lg border-black/10 text-sm">
                <option value="">- Data Desa Keseluruhan -</option>
                @foreach ($dusun as $d)
                    <option value="{{ $d->id }}" @selected(old('dusun_id', $data->dusun_id) == $d->id)>{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Tahun</label>
            <input type="number" name="tahun" value="{{ old('tahun', $data->tahun) }}" required class="w-full rounded-lg border-black/10 text-sm">
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.infografis.data.index')" />
    </div>
</form>
@endsection