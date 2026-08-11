@extends('layouts.admin')

@section('title', 'Edit Perangkat Desa')

@section('content')
@php $isiTupoksi = $perangkat->tupoksi; @endphp
<form method="POST" action="{{ route('admin.perangkat.update', $perangkat) }}" enctype="multipart/form-data" class="max-w-xl space-y-5">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $perangkat->nama) }}" required class="w-full rounded-lg border-black/10 text-sm">
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan', $perangkat->jabatan) }}" required list="daftar-jabatan" class="w-full rounded-lg border-black/10 text-sm">
            <datalist id="daftar-jabatan">
                @foreach ($jabatanList as $j)
                    <option value="{{ $j }}">
                @endforeach
            </datalist>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Tugas Pokok dan Fungsi</label>
            <textarea name="tupoksi" rows="4" maxlength="1000"
                      placeholder="Contoh: Melayani administrasi kependudukan, pengurusan surat pengantar, dan pencatatan data warga."
                      class="w-full rounded-lg border-black/10 text-sm">{{ old('tupoksi', $isiTupoksi) }}</textarea>
            <p class="text-xs text-black/40 mt-1">
                Ditampilkan pada bagan struktur organisasi, agar warga tahu urusannya
                harus disampaikan ke bagian mana. Boleh dikosongkan.
            </p>
            @error('tupoksi') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Atasan Langsung</label>
            <input type="text" name="atasan_jabatan" value="{{ old('atasan_jabatan', $perangkat->atasan_jabatan) }}" list="daftar-jabatan" class="w-full rounded-lg border-black/10 text-sm">
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Dusun</label>
            <select name="dusun_id" class="w-full rounded-lg border-black/10 text-sm">
                <option value="">- Tidak ada -</option>
                @foreach ($dusun as $d)
                    <option value="{{ $d->id }}" @selected(old('dusun_id', $perangkat->dusun_id) == $d->id)>{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Urutan Tampil</label>
            <input type="number" name="urutan" value="{{ old('urutan', $perangkat->urutan) }}" class="w-full rounded-lg border-black/10 text-sm">
        </div>
        <div>
            <label class="text-xs text-black/50 mb-1 block">Foto</label>
            @if($perangkat->foto_path)
                <img src="{{ Storage::url($perangkat->foto_path) }}" class="w-20 h-20 object-cover rounded-lg mb-2">
            @endif
            <input type="file" name="foto" accept="image/*" class="w-full text-sm">
        </div>
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.perangkat.index')" />
    </div>
</form>
@endsection