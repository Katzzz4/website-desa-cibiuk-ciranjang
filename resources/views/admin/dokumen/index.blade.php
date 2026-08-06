@extends('layouts.admin')

@section('title', 'Dokumen Desa')

@section('header-action')
    <a href="{{ route('admin.dokumen.create') }}" class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">
        + Unggah Dokumen
    </a>
@endsection

@section('content')
<div class="space-y-4">

    <form method="GET" class="bg-white rounded-2xl border border-black/5 p-4 flex flex-wrap gap-3">
        <select name="klasifikasi" onchange="this.form.submit()" class="rounded-lg border-black/10 text-sm">
            <option value="">Semua Klasifikasi</option>
            @foreach (\App\Models\Dokumen::KLASIFIKASI as $kunci => $k)
                <option value="{{ $kunci }}" @selected(request('klasifikasi') == $kunci)>{{ $k['label'] }}</option>
            @endforeach
        </select>
        @if(request('klasifikasi'))
            <a href="{{ route('admin.dokumen.index') }}" class="text-sm text-black/40 underline underline-offset-2 self-center">Reset</a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                    <th class="px-5 py-3">Nama Dokumen</th>
                    <th class="px-5 py-3">Klasifikasi</th>
                    <th class="px-5 py-3">Jenis</th>
                    <th class="px-5 py-3">Berkas</th>
                    <th class="px-5 py-3">Diunggah</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($dokumen as $d)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-5 py-3 font-medium">{{ $d->nama }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2.5 py-1 rounded-full"
                                  style="background: var(--sawah-light); color: var(--sawah-dark);">
                                {{ $d->label_klasifikasi }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-black/60">{{ $d->label_kategori }}</td>
                        <td class="px-5 py-3 text-black/60">
                            <span class="uppercase">{{ $d->ekstensi }}</span>
                            @if($d->ukuran_terbaca)
                                <span class="text-black/40">&middot; {{ $d->ukuran_terbaca }}</span>
                            @else
                                <span class="text-red-600 text-xs">&middot; berkas hilang</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-black/60">{{ $d->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3"><div class="flex items-center justify-end gap-2 flex-wrap">
                            <x-aksi.tautan :href="route('dokumen.unduh', $d)" label="Unduh" ikon="unduh" />
                            <x-aksi.edit :href="route('admin.dokumen.edit', $d)" />
                            <x-aksi.hapus :action="route('admin.dokumen.destroy', $d)"
                                          judul="Hapus dokumen ini?"
                                          konfirmasi="Berkasnya ikut terhapus dari server, sehingga warga tidak dapat mengunduhnya lagi."
                                          :nama="$d->nama" />
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-black/40">Belum ada dokumen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $dokumen->links() }}
</div>
@endsection