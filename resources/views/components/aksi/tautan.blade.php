@props(['href', 'label' => 'Lihat', 'ikon' => 'lihat', 'baru' => false])

@php
    $path = match ($ikon) {
        'unduh' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
        'data'  => 'M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5',
        default => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    };
@endphp

<a href="{{ $href }}"
   @if($baru) target="_blank" rel="noopener" @endif
   {{ $attributes->merge(['class' => 'btn-aksi btn-aksi-netral']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="1.7" stroke="currentColor" class="w-3.5 h-3.5 shrink-0">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
    </svg>
    <span>{{ $label }}</span>
</a>