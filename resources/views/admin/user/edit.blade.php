@extends('layouts.admin')

@section('title', 'Edit Akun')

@section('content')
@php $peran = \App\Http\Controllers\Admin\UserController::PERAN; @endphp

<form method="POST" action="{{ route('admin.user.update', $user) }}" class="max-w-lg space-y-5">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="100"
                   class="w-full rounded-lg border-black/10 text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="150"
                   class="w-full rounded-lg border-black/10 text-sm">
            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs text-black/50 mb-1 block">Nomor HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" maxlength="20"
                   class="w-full rounded-lg border-black/10 text-sm">
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <div>
            <label class="text-xs text-black/50 mb-1 block">Peran</label>
            <select name="role" id="pilih-peran" required class="w-full rounded-lg border-black/10 text-sm">
                @foreach ($peran as $kunci => [$label, $keterangan])
                    <option value="{{ $kunci }}" @selected(old('role', $user->role) === $kunci)>{{ $label }}</option>
                @endforeach
            </select>
            @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

            @if($user->id === auth()->id())
                <p class="text-xs text-black/40 mt-1.5">
                    Ini akun Anda sendiri. Peran Super Admin tidak dapat diturunkan dari sini agar Anda tidak terkunci.
                </p>
            @endif
        </div>

        <div id="pilih-dusun" style="display:none;">
            <label class="text-xs text-black/50 mb-1 block">Dusun yang Ditangani</label>
            <select name="dusun_id" class="w-full rounded-lg border-black/10 text-sm">
                <option value="">- Belum ditentukan -</option>
                @foreach ($dusun as $d)
                    <option value="{{ $d->id }}" @selected(old('dusun_id', $user->dusun_id) == $d->id)>{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
        <p class="text-xs uppercase tracking-widest text-black/40">Ganti Kata Sandi</p>
        <p class="text-xs text-black/45 -mt-2">Kosongkan kedua kolom ini jika kata sandi tidak perlu diubah.</p>

        <x-kolom-sandi name="password" label="Kata Sandi Baru" />

        <x-kolom-sandi name="password_confirmation" label="Ulangi Kata Sandi Baru" />
    </div>

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.user.index')" />
    </div>
</form>

<script>
    const pilihPeran = document.getElementById('pilih-peran');
    const kotakDusun = document.getElementById('pilih-dusun');

    function perbarui() {
        kotakDusun.style.display = pilihPeran.value === 'kadus' ? '' : 'none';
    }

    pilihPeran.addEventListener('change', perbarui);
    perbarui();
</script>
@endsection