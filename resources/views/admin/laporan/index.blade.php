@extends('layouts.admin')

@section('title', 'Pengaduan Masyarakat')

@section('header-action')
    <a href="{{ route('admin.laporan.rekap') }}" target="_blank" rel="noopener"
       class="btn-aksi">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.7" stroke="currentColor" class="w-3.5 h-3.5 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5z" />
        </svg>
        Cetak Rekap
    </a>
@endsection

@section('content')
@php
    $pengguna = auth()->user();
@endphp

@if($pengguna->role === 'kadus')
    @if($pengguna->dusun_id)
        <div class="mb-5 rounded-xl px-4 py-3 text-sm flex items-start gap-2.5"
             style="background: var(--sawah-light); color: var(--sawah-dark);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.7" stroke="currentColor" class="w-4 h-4 shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <span>
                Anda masuk sebagai Kepala Dusun, jadi yang ditampilkan hanya laporan
                di <strong>Dusun {{ $pengguna->dusun->nama ?? '-' }}</strong>.
            </span>
        </div>
    @else
        <div class="mb-5 rounded-xl px-4 py-3 text-sm flex items-start gap-2.5"
             style="background: #FEF6E7; color: #92600E;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.7" stroke="currentColor" class="w-4 h-4 shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span>
                Akun Anda belum ditautkan ke dusun mana pun, sehingga belum ada laporan yang dapat ditampilkan.
                Silakan hubungi Super Admin desa untuk menetapkan dusun Anda.
            </span>
        </div>
    @endif
@endif

<div class="space-y-6">

    {{-- FILTER --}}
    <form method="GET" class="bg-white rounded-2xl border border-black/5 p-4 flex flex-wrap gap-3 items-center">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari no. tiket atau judul..."
               class="rounded-lg border-black/10 text-sm flex-1 min-w-[200px]">

        <select name="status" class="rounded-lg border-black/10 text-sm">
            <option value="">Semua Status</option>
            @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $label)
                <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
            @endforeach
        </select>

        <button class="px-4 py-2 rounded-lg text-sm text-white" style="background: var(--sawah-dark);">Terapkan</button>
        @if(request()->hasAny(['cari','status','kategori_laporan_id','dusun_id']))
            <a href="{{ route('admin.laporan.index') }}" class="text-sm text-black/40 underline underline-offset-2">Reset</a>
        @endif
    </form>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-black/40 border-b border-black/5">
                    <th class="px-5 py-3">No. Tiket</th>
                    <th class="px-5 py-3">Judul</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Dusun</th>
                    <th class="px-5 py-3">Tanggal</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($laporan as $l)
                    <tr class="hover:bg-black/[0.02] cursor-pointer" onclick="window.location='{{ route('admin.laporan.show', $l) }}'">
                        <td class="px-5 py-3 font-mono-tiket text-xs text-black/60">{{ $l->no_tiket }}</td>
                        <td class="px-5 py-3 font-medium">{{ $l->judul }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $l->kategori->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $l->dusun->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $l->tanggal_kejadian->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2.5 py-1 rounded-full badge-status-{{ $l->status }}">
                                {{ ucfirst($l->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-black/40">Belum ada laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $laporan->links() }}
</div>
@endsection