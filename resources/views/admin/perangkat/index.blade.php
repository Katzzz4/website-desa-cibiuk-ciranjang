@extends('layouts.admin')

@section('title', 'Struktur Organisasi')

@section('header-action')
    <a href="{{ route('admin.perangkat.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tambah Perangkat
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                <th class="px-5 py-3">Nama</th>
                <th class="px-5 py-3">Jabatan</th>
                <th class="px-5 py-3">Tupoksi</th>
                <th class="px-5 py-3">Atasan</th>
                <th class="px-5 py-3">Dusun</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-black/5">
            @forelse ($perangkat as $p)
                <tr class="hover:bg-black/[0.02]">
                    <td class="px-5 py-3 font-medium">{{ $p->nama }}</td>
                    <td class="px-5 py-3 text-black/60">{{ $p->jabatan }}</td>
                    <td class="px-5 py-3">
                        @if($p->tupoksi)
                            <span class="text-xs text-black/55">{{ \Illuminate\Support\Str::limit($p->tupoksi, 60) }}</span>
                        @else
                            <span class="text-xs px-2.5 py-1 rounded-full badge-status-menunggu">Belum diisi</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-black/60">{{ $p->atasan_jabatan ?? '-' }}</td>
                    <td class="px-5 py-3 text-black/60">{{ $p->dusun->nama ?? '-' }}</td>
                    <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                        <x-aksi.edit :href="route('admin.perangkat.edit', $p)" />
                        <x-aksi.hapus :action="route('admin.perangkat.destroy', $p)"
                                          judul="Hapus perangkat desa ini?"
                                          konfirmasi="Data akan hilang dari bagan struktur organisasi di halaman profil desa."
                                          :nama="$p->nama" />
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-10 text-black/40">Belum ada data perangkat desa.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection