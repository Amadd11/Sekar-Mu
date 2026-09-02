@props([
    'route',
    'routeParam' => null,
    'queryParam' => [],
    'icon',
    'label',
    'fillIcon' => false,
])

@php
    $href = $routeParam ? route($route, array_merge([$routeParam], $queryParam)) : route($route, $queryParam);
    $isActive = request()->routeIs($route);

    if (! empty($queryParam)) {
        foreach ($queryParam as $key => $val) {
            $currentVal = request()->query($key);
            if ($key === 'tab' && empty($currentVal)) {
                $currentVal = 'penilaian';
            }
            if ((string) $currentVal !== (string) $val) {
                $isActive = false;
                break;
            }
        }
    }

    $activeClass = 'bg-gradient-to-r from-[#225c84] to-[#1e5276] text-white font-bold border-l-4 border-teal-300 shadow-xs';
    $inactiveClass = 'text-teal-100/80 hover:bg-[#1f5379]/80 hover:text-white hover:translate-x-1';
    $iconActiveClass = 'text-teal-300 scale-105';
    $iconInactiveClass = 'text-teal-200/70 group-hover:text-teal-200 group-hover:scale-110';
@endphp

<a
    href="{{ $href }}"
    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 group {{ $isActive ? $activeClass : $inactiveClass }}"
    wire:navigate
>
    <span
        class="material-symbols-outlined text-[20px] transition-transform duration-200 {{ $isActive ? $iconActiveClass : $iconInactiveClass }}"
        @if($fillIcon) style="font-variation-settings: 'FILL' 1;" @endif
    >
        {{ $icon }}
    </span>
    <span class="tracking-tight">{{ $label }}</span>
</a>