@props(['score' => 0])

@php
    $score = max(0, min(100, (int) $score));
    $color = $score >= 70 ? 'bg-emerald-500' : ($score >= 40 ? 'bg-amber-500' : 'bg-red-500');
    $bgColor = $score >= 70 ? 'bg-emerald-100' : ($score >= 40 ? 'bg-amber-100' : 'bg-red-100');
@endphp

<div class="flex items-center gap-2">
    <div class="{{ $bgColor }} rounded-full h-2 flex-1 overflow-hidden" role="progressbar" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100" aria-label="Score : {{ $score }}%">
        <div class="{{ $color }} h-full rounded-full transition-all duration-500" style="width: {{ $score }}%"></div>
    </div>
    <span class="text-xs font-semibold tabular-nums {{ $score >= 70 ? 'text-emerald-600' : ($score >= 40 ? 'text-amber-600' : 'text-red-600') }}">
        {{ $score }}%
    </span>
</div>
