@extends('layouts.admin')

@section('title', 'Potensi Desa')

@section('header-action')
    <a href="{{ route('admin.potensi.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tambah Potensi
    </a>
@endsection

@section('content')
<div class="space-y-4">

    <form method="GET" class="bg-white rounded-2xl border border-black/5 p-4 flex flex-wrap gap-3">
        <select name="jenis" onchange="this.form.submit()" class="rounded-lg border-black/10 text-sm">
            <option value="">Semua Jenis</option>
            @foreach (\App\Models\PotensiDesa::JENIS as $val => $label)
                <option value="{{ $val }}" @selected(request('jenis') == $val)>{{ $label }}</option>
            @endforeach
        </select>
        @if(request('jenis'))
            <a href="{{ route('admin.potensi.index') }}" class="text-sm text-black/40 underline underline-offset-2 self-center">Reset</a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                    <th class="px-5 py-3">Foto</th>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Jenis</th>
                    <th class="px-5 py-3">Kontak</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($potensi as $p)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-5 py-3">
                            @if($p->foto_path)
                                <img src="{{ Storage::url($p->foto_path) }}" class="w-14 h-10 object-cover rounded-lg">
                            @else
                                <div class="w-14 h-10 rounded-lg bg-black/[0.04]"></div>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium">{{ $p->nama }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $p->label_jenis }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $p->kontak ?? '-' }}</td>
                        <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                            <x-aksi.edit :href="route('admin.potensi.edit', $p)" />
                            <x-aksi.hapus :action="route('admin.potensi.destroy', $p)"
                                          judul="Hapus potensi desa ini?"
                                          konfirmasi="Data beserta fotonya akan terhapus dan tidak dapat dikembalikan."
                                          :nama="$p->nama" />
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-black/40">Belum ada data potensi desa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $potensi->links() }}
</div>
@endsection