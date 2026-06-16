@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 text-sm font-medium text-brand-600 border-b-2 border-brand-600 transition-colors'
            : 'inline-flex items-center px-3 py-2 text-sm font-medium text-warm-600 border-b-2 border-transparent hover:text-brand-600 hover:border-brand-300 transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
