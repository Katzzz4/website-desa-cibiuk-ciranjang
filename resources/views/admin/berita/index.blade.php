@extends('layouts.admin')

@section('title', 'Berita & Pengumuman')

@section('header-action')
    <a href="{{ route('admin.berita.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tulis Baru
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                <th class="px-5 py-3">Judul</th>
                <th class="px-5 py-3">Kategori</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Tanggal</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-black/5">
            @forelse ($berita as $b)
                <tr class="hover:bg-black/[0.02]">
                    <td class="px-5 py-3 font-medium">{{ $b->judul }}</td>
                    <td class="px-5 py-3 text-black/60">{{ ucfirst($b->kategori) }}</td>
                    <td class="px-5 py-3">
                        @if($b->tanggal_publish && $b->tanggal_publish <= now())
                            <span class="text-xs px-2.5 py-1 rounded-full badge-status-selesai">Publish</span>
                        @else
                            <span class="text-xs px-2.5 py-1 rounded-full badge-status-menunggu">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-black/60">{{ $b->tanggal_publish?->format('d M Y') ?? '-' }}</td>
                    <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                        <x-aksi.edit :href="route('admin.berita.edit', $b)" />
                        <x-aksi.hapus :action="route('admin.berita.destroy', $b)"
                                          judul="Hapus berita ini?"
                                          konfirmasi="Berita akan hilang dari situs desa dan tidak dapat dikembalikan."
                                          :nama="$b->judul" />
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-10 text-black/40">Belum ada berita.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $berita->links() }}
@endsection