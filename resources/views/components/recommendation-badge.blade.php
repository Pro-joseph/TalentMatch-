@props(['recommandation' => null])

@php
    $label = $recommandation instanceof \App\Recommandation ? $recommandation->label() : (string) $recommandation;
    $classes = match (true) {
        $recommandation instanceof \App\Recommandation && $recommandation === \App\Recommandation::Recommande => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-600/20',
        $recommandation instanceof \App\Recommandation && $recommandation === \App\Recommandation::Reserve => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-600/20',
        default => 'bg-red-50 text-red-700 border-red-200 ring-red-600/20',
    };
@endphp

<span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $classes }}">
    {{ $label }}
</span>
