{{--
    Tab navigasi antar halaman pengelolaan Infografis Penduduk.
    Halaman aktif dikenali otomatis dari nama route, jadi cukup dipanggil:
        <x-nav-infografis />
--}}
@php
    $tab = [
        [
            'label' => 'Ringkasan',
            'ket'   => 'Total penduduk & mutasi',
            'url'   => route('admin.infografis.ringkasan'),
            'aktif' => request()->routeIs('admin.infografis.ringkasan*'),
            'ikon'  => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
        ],
        [
            'label' => 'Isi Data',
            'ket'   => 'Angka per kategori',
            'url'   => route('admin.infografis.data.index'),
            'aktif' => request()->routeIs('admin.infografis.data.*'),
            'ikon'  => 'M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5',
        ],
        [
            'label' => 'Kategori',
            'ket'   => 'Jenis grafik yang tampil',
            'url'   => route('admin.infografis.kategori.index'),
            'aktif' => request()->routeIs('admin.infografis.kategori.*'),
            'ikon'  => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z M6 6h.008v.008H6V6z',
        ],
    ];
@endphp

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="flex gap-2 overflow-x-auto pb-1 -mb-1">
        @foreach ($tab as $t)
            <a href="{{ $t['url'] }}"
               class="group flex items-start gap-3 px-4 py-3 rounded-xl border shrink-0 min-w-[168px] transition"
               @style([
                   'background: var(--sawah-dark); border-color: var(--sawah-dark);' => $t['aktif'],
                   'background: #fff; border-color: var(--garis);' => !$t['aktif'],
               ])>

                <span class="mt-0.5 shrink-0"
                      @style([
                          'color: var(--padi-light);' => $t['aktif'],
                          'color: var(--talang);' => !$t['aktif'],
                      ])>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['ikon'] }}" />
                    </svg>
                </span>

                <span class="text-left">
                    <span class="block text-sm font-semibold leading-tight"
                          @style([
                              'color: #fff;' => $t['aktif'],
                              'color: var(--ink);' => !$t['aktif'],
                          ])>
                        {{ $t['label'] }}
                    </span>
                    <span class="block text-[11px] mt-0.5 leading-tight"
                          @style([
                              'color: rgba(255,255,255,0.6);' => $t['aktif'],
                              'color: var(--lembut);' => !$t['aktif'],
                          ])>
                        {{ $t['ket'] }}
                    </span>
                </span>
            </a>
        @endforeach
    </div>
</div>