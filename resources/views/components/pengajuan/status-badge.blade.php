@props([
    'status' => 'draft',
    'icon' => true,
])

@php
    use App\Models\SuratPengajuan;

    $label = SuratPengajuan::statusLabel($status);
    $badgeClasses = SuratPengajuan::statusBadgeClasses($status);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold tracking-wide border shadow-2xs {$badgeClasses}"]) }}>
    @if ($icon)
        @if ($status === 'approved')
            <span class="material-symbols-outlined text-[14px] shrink-0 leading-none text-emerald-600 select-none">check_circle</span>
        @elseif ($status === 'rejected')
            <span class="material-symbols-outlined text-[14px] shrink-0 leading-none text-rose-600 select-none">cancel</span>
        @else
            <span class="relative flex h-2 w-2 shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
        @endif
    @endif
    <span class="leading-none">{{ $label }}</span>
</span>