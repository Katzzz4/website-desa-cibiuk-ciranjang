@extends('layouts.admin')

@section('title', 'Galeri Desa')

@section('header-action')
    <a href="{{ route('admin.galeri.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        Tambah Item
    </a>
@endsection

@section('content')
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    @forelse ($galeri as $g)
        <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
            <div class="aspect-square bg-[var(--kertas)]">
                @if($g->tipe === 'foto' && $g->file_path)
                    <img src="{{ Storage::url($g->file_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-xs text-black/40 px-2 text-center">
                        Video: {{ $g->judul }}
                    </div>
                @endif
            </div>
            <div class="p-3">
                <p class="text-xs font-medium truncate">{{ $g->judul }}</p>
                <p class="text-[11px] text-black/40 mb-2">{{ ucfirst($g->tipe) }}</p>
                <div class="flex justify-between text-xs">
                    <x-aksi.edit :href="route('admin.galeri.edit', $g)" />
                    <x-aksi.hapus :action="route('admin.galeri.destroy', $g)"
                                          judul="Hapus item galeri ini?"
                                          konfirmasi="Berkas foto atau video ikut terhapus dari server dan tidak dapat dikembalikan."
                                          :nama="$g->judul" />
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-black/40 col-span-full text-center py-10">Belum ada item galeri.</p>
    @endforelse
</div>

{{ $galeri->links() }}
@endsection