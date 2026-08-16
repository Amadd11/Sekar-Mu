@php
    $user = auth()->user();
    $latestApp = \App\Models\Application::where('user_id', $user->id)->latest()->first();
    if (!$latestApp && ($user->isAdmin() || $user->isReviewer())) {
        $latestApp = \App\Models\Application::latest()->first();
    }
@endphp

<!-- Topbar -->
<header class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
    <div class="flex items-center gap-3">
        <button
            type="button"
            @click="sidebarOpen = true"
            class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 lg:hidden focus:outline-none"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <div class="flex items-center gap-2">
            <span class="text-xl select-none">🌸</span>
            <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                Sekar-Mu — Dashboard Akreditasi KEPK
            </h1>
        </div>
    </div>

    <!-- Top Action Buttons -->
    <div class="flex items-center gap-2">
        @if ($latestApp)
            <a href="{{ route('applications.self-assessment', $latestApp) }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 transition shadow-2xs" wire:navigate>
                <span>💾</span>
                <span>Simpan Data</span>
            </a>
        @endif
        <a href="{{ route('applications.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#1a4a6e] hover:bg-[#133e5f] transition shadow-2xs" wire:navigate>
            <span>📑</span>
            <span>Daftar Pengajuan</span>
        </a>
    </div>
</header>
