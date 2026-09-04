<!-- 1. Top Header Banner -->
<div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
    <!-- Top Gradient Accent Bar -->
    <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>

    <div class="p-6 sm:p-7 relative z-10 space-y-4">
        <!-- Top Meta Strip -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="bg-primary-50 text-primary-700 font-mono text-xs px-3 py-1 rounded-lg font-bold border border-primary-200/70 shadow-2xs">
                    {{ $suratPengajuan->formatted_id }}
                </span>
                <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold border {{ $metrics['prediction']['badge_class'] }}">
                    {{ $metrics['prediction']['type'] }} ({{ $metrics['overall_compliance'] }}%)
                </span>
            </div>
        </div>

        <!-- Main Title & Action Buttons Row -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
            <div class="space-y-1 max-w-3xl">
                <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                    Workspace Asesor: {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'Borang Akreditasi KEPK' }}
                </h1>
                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                    Pemohon: <strong class="text-slate-700">{{ $suratPengajuan->user->name }}</strong> • KEPK: {{ $suratPengajuan->kepk->name ?? '-' }} ({{ $suratPengajuan->kepk->institusi->name ?? '-' }})
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap w-full sm:w-auto shrink-0">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition shadow-2xs" wire:navigate>
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Alert Notifications -->
@if (session('status'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
    <div class="flex items-center gap-2 font-semibold">
        <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
        <span>{{ session('status') }}</span>
    </div>
</div>
@endif

@if (session('action_status'))
<div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
    <div class="flex items-center gap-2 font-semibold">
        <span class="material-symbols-outlined text-[18px] text-blue-600">info</span>
        <span>{{ session('action_status') }}</span>
    </div>
</div>
@endif

<!-- 2. Four KPI Metric Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1: Kepatuhan Total -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kepatuhan Total</span>
            <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">trending_up</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-primary-700 font-display">
                {{ $metrics['overall_compliance'] }}%
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5 font-mono">
                {{ $metrics['total_answered'] }}/{{ $metrics['total_items'] }} butir terisi
            </p>
        </div>
    </div>

    <!-- Card 2: Prediksi Akreditasi -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Prediksi Akreditasi</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-xl sm:text-2xl font-black text-emerald-700 font-display truncate">
                {{ $metrics['prediction']['type'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Nilai C: {{ $metrics['counts']['C'] }} (Batas Tipe B: ≤5)
            </p>
        </div>
    </div>

    <!-- Card 3: Nilai C (Kurang) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai C (Kurang)</span>
            <div class="w-8 h-8 rounded-xl {{ $metrics['counts']['C'] > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">cancel</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black font-display {{ $metrics['counts']['C'] > 0 ? 'text-rose-600' : 'text-slate-700' }}">
                {{ $metrics['counts']['C'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Butir bernilai 0%
            </p>
        </div>
    </div>

    <!-- Card 4: Kemajuan Asesmen Asesor -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kemajuan Asesmen</span>
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-blue-700 font-display">
                {{ $metrics['total_answered'] }}<span class="text-base text-slate-400 font-normal">/{{ $metrics['total_items'] }}</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                {{ $metrics['completion_percentage'] }}% butir telah dinilai asesor
            </p>
        </div>
    </div>
</div>
