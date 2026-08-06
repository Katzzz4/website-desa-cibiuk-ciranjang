@props(['label' => 'Simpan'])
 
<button type="submit" {{ $attributes->merge(['class' => 'tombol-simpan']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
    </svg>
    <span>{{ $label }}</span>
</button>
