@props(['active'])

@php
$classes = 'mr-8 inline-flex items-center justify-center text-white bg-white/20 border-white/30 hover:bg-blue-600 focus:bg-blue-600 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 transition-colors duration-200 m-1'
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
