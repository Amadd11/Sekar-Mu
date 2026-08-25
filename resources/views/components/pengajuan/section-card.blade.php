@props([
    'icon',
    'title',
])

<div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-700 text-[20px]">{{ $icon }}</span>
            <h3 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">{{ $title }}</h3>
        </div>
        @if (isset($action))
            {{ $action }}
        @endif
    </div>
    {{ $slot }}
</div>
