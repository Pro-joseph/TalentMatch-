@props(['score' => 0, 'size' => 48])

@php
    $score = max(0, min(100, (int) $score));
    $r = 14;
    $circumference = 2 * pi() * $r;
    $offset = $circumference - ($score / 100) * $circumference;
    $color = $score >= 70 ? 'text-emerald-500' : ($score >= 40 ? 'text-amber-500' : 'text-red-500');
    $strokeColor = $score >= 70 ? '#10b981' : ($score >= 40 ? '#f59e0b' : '#ef4444');
@endphp

<svg class="{{ $color }}" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" role="img" aria-label="Score de matching : {{ $score }}%">
    <circle cx="16" cy="16" r="{{ $r }}" fill="none" stroke="currentColor" stroke-width="3" opacity="0.15" />
    <circle cx="16" cy="16" r="{{ $r }}" fill="none" stroke="{{ $strokeColor }}" stroke-width="3" stroke-linecap="round"
            stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" transform="rotate(-90 16 16)" />
    <text x="16" y="16" text-anchor="middle" dominant-baseline="central" font-size="8" font-weight="700" fill="currentColor">
        {{ $score }}
    </text>
</svg>
