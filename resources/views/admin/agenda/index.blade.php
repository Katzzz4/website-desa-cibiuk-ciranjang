@extends('layouts.admin')

@section('title', 'Agenda Kegiatan')

@section('header-action')
    <a href="{{ route('admin.agenda.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tambah Agenda
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                <th class="px-5 py-3">Judul</th>
                <th class="px-5 py-3">Tanggal Mulai</th>
                <th class="px-5 py-3">Lokasi</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-black/5">
            @forelse ($agenda as $a)
                <tr class="hover:bg-black/[0.02]">
                    <td class="px-5 py-3 font-medium">{{ $a->judul }}</td>
                    <td class="px-5 py-3 text-black/60">{{ $a->tanggal_mulai->format('d M Y, H:i') }}</td>
                    <td class="px-5 py-3 text-black/60">{{ $a->lokasi ?? '-' }}</td>
                    <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                        <x-aksi.edit :href="route('admin.agenda.edit', $a)" />
                        <x-aksi.hapus :action="route('admin.agenda.destroy', $a)"
                                          judul="Hapus agenda ini?"
                                          konfirmasi="Agenda akan hilang dari jadwal kegiatan desa dan tidak dapat dikembalikan."
                                          :nama="$a->judul" />
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-10 text-black/40">Belum ada agenda.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $agenda->links() }}
@endsection