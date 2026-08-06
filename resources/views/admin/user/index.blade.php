@extends('layouts.admin')

@section('title', 'Kelola Akun')

@section('header-action')
    <a href="{{ route('admin.user.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tambah Akun
    </a>
@endsection

@section('content')
@php $peran = \App\Http\Controllers\Admin\UserController::PERAN; @endphp

<div class="space-y-4">

    @if($errors->any())
        <div class="rounded-lg px-4 py-3 text-sm" style="background: #FBEAEA; color: #96261F;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Peran</th>
                    <th class="px-5 py-3">Dusun</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @foreach ($pengguna as $u)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-5 py-3">
                            <span class="font-medium">{{ $u->name }}</span>
                            @if($u->id === auth()->id())
                                <span class="text-xs text-black/40">(Anda)</span>
                            @endif
                            @if($u->no_hp)
                                <p class="text-xs text-black/45 mt-0.5">{{ $u->no_hp }}</p>
                            @endif
                        </div></td>
                        <td class="px-5 py-3 text-black/60">{{ $u->email }}</td>
                        <td class="px-5 py-3">
                            @php
                                $gaya = match ($u->role) {
                                    'superadmin' => 'background:#E7F3EC; color:#0E5C3A;',
                                    'admin'      => 'background:#E8F0FB; color:#1B4B8F;',
                                    'kadus'      => 'background:#FEF6E7; color:#92600E;',
                                    default      => 'background:#F0F1F0; color:#5D635F;',
                                };
                            @endphp
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium" style="{{ $gaya }}">
                                {{ $peran[$u->role][0] ?? ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-black/60">{{ $u->dusun->nama ?? '-' }}</td>
                        <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                            <x-aksi.edit :href="route('admin.user.edit', $u)" />
                            @if($u->id !== auth()->id())
                                <x-aksi.hapus :action="route('admin.user.destroy', $u)"
                                          judul="Hapus akun ini?"
                                          konfirmasi="Pemilik akun tidak akan bisa masuk ke dashboard lagi. Tindakan ini tidak dapat dibatalkan."
                                          :nama="$u->name" />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $pengguna->links() }}

    {{-- Keterangan peran --}}
    <div class="bg-white rounded-2xl border border-black/5 p-5">
        <p class="text-xs uppercase tracking-widest text-black/40 mb-3">Keterangan Peran</p>
        <div class="space-y-2 text-sm">
            @foreach ($peran as $kunci => [$label, $keterangan])
                <div class="flex gap-3">
                    <span class="font-medium shrink-0 w-28">{{ $label }}</span>
                    <span class="text-black/55">{{ $keterangan }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection