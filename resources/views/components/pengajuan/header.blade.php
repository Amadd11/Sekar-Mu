@props([
    'surat',
    'title',
    'subtitle' => null,
])

<div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden">
    <!-- Decorative subtle glow -->
    <div class="absolute -top-20 -right-20 w-48 h-48 bg-primary-500/5 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10">
        <div class="flex items-center gap-2.5 mb-2.5 flex-wrap">
            <span class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200 shrink-0">
                #APP-{{ str_pad($surat->id, 5, '0', STR_PAD_LEFT) }}
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold border whitespace-nowrap shrink-0 {{ \App\Models\SuratPengajuan::statusBadgeClasses($surat->status) }}">
                <span class="material-symbols-outlined text-[14px]">{{ \App\Models\SuratPengajuan::statusIcon($surat->status) }}</span>
                <span>{{ \App\Models\SuratPengajuan::statusLabel($surat->status) }}</span>
            </span>
        </div>
        <h1 class="font-display text-xl font-extrabold text-slate-900 leading-tight tracking-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>

    @if (isset($actions))
        <div class="relative z-10 flex items-center gap-2 flex-wrap">
            {{ $actions }}
        </div>
    @endif
</div>
