@props([
    'surat',
    'title',
    'subtitle' => null,
])

<div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
    <!-- Top Gradient Accent Bar -->
    <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>

    <div class="p-6 sm:p-7 relative z-10 space-y-4">
        <!-- Top Meta Strip -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="bg-slate-100 text-slate-700 font-mono text-xs px-3 py-1 rounded-lg font-bold border border-slate-200 shadow-2xs">
                    {{ $surat->formatted_id }}
                </span>
                <x-pengajuan.status-badge :status="$surat->status" />
            </div>
        </div>

        <!-- Main Title & Action Buttons Row -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
            <div class="space-y-1 max-w-3xl">
                <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                    {{ $title }}
                </h1>
                @if ($subtitle)
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            @if (isset($actions))
                <div class="flex items-center gap-2.5 w-full sm:w-auto shrink-0 flex-wrap">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</div>
