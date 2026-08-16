@props([
    'name' => '',
    'answered' => 0,
    'total' => 0,
    'percentage' => 0,
])

<div>
    <div class="flex justify-between items-center mb-1 text-slate-700 font-medium text-xs">
        <span>{{ $name }}</span>
        <span class="text-slate-400 font-mono text-[11px]">{{ $answered }}/{{ $total }} ({{ $percentage }}%)</span>
    </div>
    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
        <div class="bg-teal-600 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
    </div>
</div>
