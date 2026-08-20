@props([
    'label',
    'value',
    'subtext' => null,
    'icon' => null,
    'valueColor' => 'text-primary-700',
    'bgColor' => 'bg-white',
])

<div class="{{ $bgColor }} border border-slate-200/80 rounded-2xl p-5 shadow-2xs transition hover:shadow-xs">
    <div class="flex items-center justify-between">
        <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">{{ $label }}</span>
        @if ($icon)
            <span class="text-base">{{ $icon }}</span>
        @endif
    </div>
    <div class="font-display text-2xl font-black {{ $valueColor }} mt-1.5 leading-none">
        {{ $value }}
    </div>
    @if ($subtext)
        <div class="text-[11px] text-slate-400 font-medium mt-1.5 leading-snug">
            {{ $subtext }}
        </div>
    @endif
</div>
