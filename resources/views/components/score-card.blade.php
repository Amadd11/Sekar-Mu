@props([
    'title' => '',
    'count' => 0,
    'color' => 'slate', // slate, emerald, amber, rose
])

@php
    $colorClasses = match ($color) {
        'emerald' => 'text-emerald-600',
        'amber' => 'text-amber-500',
        'rose' => 'text-rose-600',
        default => 'text-slate-800',
    };
@endphp

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs']) }}>
    <div class="text-3xl font-black {{ $colorClasses }}">{{ $count }}</div>
    <div class="text-xs font-semibold text-slate-500 mt-1">{{ $title }}</div>
</div>
