@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('content')
@php $peran = \App\Http\Controllers\Admin\UserController::PERAN; @endphp

<form method="POST" action="{{ route('admin.user.store') }}" class="max-w-lg space-y-5">
    @csrf

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100"
                   class="w-full rounded-lg border-black/10 text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required maxlength="150"
                   class="w-full rounded-lg border-black/10 text-sm">
            <p class="text-xs text-black/40 mt-1">Dipakai untuk masuk ke dashboard.</p>
            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Nomor HP (opsional)</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" maxlength="20"
                   class="w-full rounded-lg border-black/10 text-sm">
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Peran</label>
            <select name="role" id="pilih-peran" required class="w-full rounded-lg border-black/10 text-sm">
                @foreach ($peran as $kunci => [$label, $keterangan])
                    <option value="{{ $kunci }}" @selected(old('role', 'admin') === $kunci)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="mt-2 space-y-1">
                @foreach ($peran as $kunci => [$label, $keterangan])
                    <p class="text-xs text-black/45 ket-peran" data-peran="{{ $kunci }}" style="display:none;">{{ $keterangan }}</p>
                @endforeach
            </div>
            @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div id="pilih-dusun" style="display:none;">
            <label class="text-xs text-black/50 mb-1 block">Dusun yang Ditangani</label>
            <select name="dusun_id" class="w-full rounded-lg border-black/10 text-sm">
                <option value="">- Belum ditentukan -</option>
                @foreach ($dusun as $d)
                    <option value="{{ $d->id }}" @selected(old('dusun_id') == $d->id)>{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <x-kolom-sandi name="password" label="Kata Sandi" required
                       petunjuk="Minimal 8 karakter. Sampaikan ke pemilik akun dan minta segera diganti." />

        <x-kolom-sandi name="password_confirmation" label="Ulangi Kata Sandi" required />
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Buat Akun" />
        <x-tombol.batal :href="route('admin.user.index')" />
    </div>
</form>

<script>
    const pilihPeran = document.getElementById('pilih-peran');
    const kotakDusun = document.getElementById('pilih-dusun');
    const ketPeran = document.querySelectorAll('.ket-peran');

    function perbarui() {
        kotakDusun.style.display = pilihPeran.value === 'kadus' ? '' : 'none';
        ketPeran.forEach((el) => {
            el.style.display = el.dataset.peran === pilihPeran.value ? '' : 'none';
        });
    }

    pilihPeran.addEventListener('change', perbarui);
    perbarui();
</script>
@endsection