@extends('layouts.admin')

@section('title', 'Kategori Infografis')

@section('header-action')
    <a href="{{ route('admin.infografis.kategori.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Tambah Kategori
    </a>
@endsection

@section('content')
<x-nav-infografis />

<div class="space-y-4">

    <div class="bg-white rounded-2xl border border-black/5 p-5">
        <p class="text-sm text-black/60 leading-relaxed">
            Kategori menentukan grafik apa saja yang muncul di halaman Infografis Penduduk.
            Contoh kategori yang bisa ditambahkan: <em>Kelompok Umur</em>, <em>Status Perkawinan</em>,
            <em>Golongan Darah</em>, atau <em>Wajib Pilih</em>.
            Setelah kategori dibuat, isi angkanya melalui menu <strong>Isi Data per Kategori</strong>.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                    <th class="px-5 py-3">Urutan</th>
                    <th class="px-5 py-3">Nama Kategori</th>
                    <th class="px-5 py-3">Jumlah Baris Data</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($kategoriList as $k)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-5 py-3 text-black/45">{{ $k->urutan }}</td>
                        <td class="px-5 py-3 font-medium">{{ $k->nama }}</td>
                        <td class="px-5 py-3">
                            @if($k->data_count > 0)
                                <span class="text-black/60">{{ $k->data_count }} baris</span>
                            @else
                                <span class="text-xs px-2.5 py-1 rounded-full badge-status-menunggu">Belum ada data</span>
                            @endif
                        </td>
                        <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                            <x-aksi.tautan :href="route('admin.infografis.data.index', ['kategori_id' => $k->id])" label="Lihat Data" ikon="data" />
                            <x-aksi.edit :href="route('admin.infografis.kategori.edit', $k)" />
                            <x-aksi.hapus :action="route('admin.infografis.kategori.destroy', $k)"
                                          judul="Hapus kategori ini?"
                                          konfirmasi="{{ $k->data_count > 0
                                                ? 'Seluruh ' . $k->data_count . ' baris data di dalamnya ikut terhapus, dan grafiknya akan hilang dari halaman infografis publik.'
                                                : 'Kategori ini belum berisi data, jadi tidak ada angka yang ikut terhapus.' }}"
                                          :nama="$k->nama" />
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-10 text-black/40">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection