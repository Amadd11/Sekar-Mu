@props([
    'status' => 'draft',
    'icon' => true,
])

@php
    $label = \App\Models\SuratPengajuan::statusLabel($status);
    $iconName = \App\Models\SuratPengajuan::statusIcon($status);
    $badgeClasses = \App\Models\SuratPengajuan::statusBadgeClasses($status);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border whitespace-nowrap {$badgeClasses}"]) }}>
    @if ($icon)
        <span class="material-symbols-outlined text-[14px]">{{ $iconName }}</span>
    @endif
    <span>{{ $label }}</span>
</span>
