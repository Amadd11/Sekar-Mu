@props([
    'skor' => null,
    'size' => 'md',
    'showLabel' => false,
    'showPercentage' => false,
])

@php
    $skorUpper = strtoupper(trim((string) $skor));

    $configs = [
        'A' => [
            'label' => 'Nilai A (Lengkap / Memenuhi)',
            'short' => 'A',
            'pct' => '100%',
            'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            'dot' => 'bg-emerald-500',
        ],
        'B' => [
            'label' => 'Nilai B (Sebagian)',
            'short' => 'B',
            'pct' => '50%',
            'bg' => 'bg-amber-50 text-amber-700 border-amber-200/80',
            'dot' => 'bg-amber-500',
        ],
        'C' => [
            'label' => 'Nilai C (Kurang / Tidak Memenuhi)',
            'short' => 'C',
            'pct' => '0%',
            'bg' => 'bg-rose-50 text-rose-700 border-rose-200/80',
            'dot' => 'bg-rose-500',
        ],
        'D' => [
            'label' => 'Nilai D (Tidak Dapat Dinilai)',
            'short' => 'D',
            'pct' => 'N/A',
            'bg' => 'bg-slate-100 text-slate-600 border-slate-200',
            'dot' => 'bg-slate-400',
        ],
    ];

    $cfg = $configs[$skorUpper] ?? [
        'label' => 'Belum Dinilai',
        'short' => '-',
        'pct' => '-',
        'bg' => 'bg-slate-100 text-slate-500 border-slate-200',
        'dot' => 'bg-slate-300',
    ];

    $sizeClasses = match($size) {
        'sm' => 'text-[10px] px-2 py-0.5 gap-1',
        'lg' => 'text-sm px-3.5 py-1.5 gap-2 font-bold',
        default => 'text-xs px-2.5 py-1 gap-1.5',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-lg font-bold border shadow-2xs {$cfg['bg']} {$sizeClasses}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }} shrink-0"></span>
    <span class="font-mono">{{ $cfg['short'] }}</span>
    @if ($showPercentage && $skorUpper)
        <span class="font-normal opacity-80 text-[10px]">({{ $cfg['pct'] }})</span>
    @endif
    @if ($showLabel && $skorUpper)
        <span class="font-normal opacity-90">• {{ $cfg['label'] }}</span>
    @endif
</span>
