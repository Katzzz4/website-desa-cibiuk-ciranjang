@extends('layouts.admin')

@section('title', 'Data Infografis Penduduk')

@section('header-action')
    <a href="{{ route('admin.infografis.data.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tambah Data
    </a>
@endsection

@section('content')
<x-nav-infografis />

<div class="space-y-4">
    <div class="flex items-center justify-between">
    </div>

    <form method="GET" class="bg-white rounded-2xl border border-black/5 p-4 flex flex-wrap gap-3">
        <select name="kategori_id" onchange="this.form.submit()" class="rounded-lg border-black/10 text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($kategoriList as $k)
                <option value="{{ $k->id }}" @selected($kategoriAktif == $k->id)>{{ $k->nama }}</option>
            @endforeach
        </select>
        @if($kategoriAktif)
            <a href="{{ route('admin.infografis.data.index') }}" class="text-sm text-black/40 underline underline-offset-2 self-center">Reset</a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Label</th>
                    <th class="px-5 py-3">Dusun</th>
                    <th class="px-5 py-3">L</th>
                    <th class="px-5 py-3">P</th>
                    <th class="px-5 py-3">Tahun</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($data as $d)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-5 py-3 text-black/60">{{ $d->kategori->nama ?? '-' }}</td>
                        <td class="px-5 py-3 font-medium">{{ $d->label }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $d->dusun->nama ?? '-' }}</td>
                        <td class="px-5 py-3">{{ number_format($d->jumlah_laki) }}</td>
                        <td class="px-5 py-3">{{ number_format($d->jumlah_perempuan) }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $d->tahun }}</td>
                        <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                            <x-aksi.edit :href="route('admin.infografis.data.edit', $d)" />
                            <x-aksi.hapus :action="route('admin.infografis.data.destroy', $d)"
                                          judul="Hapus baris data ini?"
                                          konfirmasi="Angka pada grafik infografis akan ikut berubah setelah data dihapus."
                                          :nama="$d->label" />
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-10 text-black/40">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $data->links() }}
</div>
@endsection