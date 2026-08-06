{{--
    Tombol Hapus untuk baris tabel, sudah termasuk form dan dialog konfirmasi.

    Pemakaian:
        <x-aksi.hapus :action="route('admin.berita.destroy', $b)"
                      judul="Hapus berita ini?"
                      konfirmasi="Berita yang dihapus tidak dapat dikembalikan."
                      :nama="$b->judul" />

    Dialog konfirmasinya berada di layouts/admin.blade.php agar cukup satu
    untuk seluruh halaman, tidak diulang di setiap baris tabel.
--}}
@props([
    'action',
    'judul' => 'Hapus data ini?',
    'konfirmasi' => 'Data yang dihapus tidak dapat dikembalikan.',
    'nama' => null,
    'label' => 'Hapus',
])

<form method="POST" action="{{ $action }}" class="inline"
      data-konfirmasi-hapus
      data-judul="{{ $judul }}"
      data-pesan="{{ $konfirmasi }}"
      @if($nama) data-nama="{{ $nama }}" @endif>
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'btn-aksi btn-aksi-hapus']) }}>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.7" stroke="currentColor" class="w-3.5 h-3.5 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>
        <span>{{ $label }}</span>
    </button>
</form>