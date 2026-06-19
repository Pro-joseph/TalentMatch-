@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-3 py-2 text-sm font-medium text-brand-600 relative transition duration-200 ease-golden'
            : 'inline-flex items-center px-3 py-2 text-sm font-medium text-slate-500 relative transition duration-200 ease-golden hover:text-slate-950';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    @if ($active ?? false)
        <span class="nav-indicator"></span>
    @endif
</a>
