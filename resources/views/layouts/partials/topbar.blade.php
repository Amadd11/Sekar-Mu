@php
    $user = auth()->user();
    $latestApp = \App\Models\SuratPengajuan::where('user_id', $user->id)->latest()->first();
    if (!$latestApp && ($user->isAdmin() || $user->isReviewer())) {
        $latestApp = \App\Models\SuratPengajuan::latest()->first();
    }
@endphp

<!-- Topbar (app.css theme) -->
<header class="h-20 bg-white border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
    <div class="flex items-center gap-3.5">
        <button
            type="button"
            @click="sidebarOpen = true"
            class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 lg:hidden focus:outline-none"
        >
            <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>
        <div class="flex items-center gap-2.5">
            <span class="text-2xl select-none">🌸</span>
            <h2 class="font-display text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-none">
                Sekar-Mu — Dashboard Akreditasi KEPK
            </h2>
        </div>
    </div>

    <!-- Top Action Buttons -->
    <div class="flex items-center gap-2.5">
        @if ($user->isReviewer() || $user->isAdmin())
            <a
                href="{{ route('penilaian.index') }}"
                class="bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white px-4 sm:px-5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-md shadow-primary-700/20 flex items-center gap-1.5"
                wire:navigate
            >
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                <span class="hidden sm:inline">Portal Penilaian</span>
            </a>
        @elseif ($user->isApplicant() && $latestApp)
            <a
                href="{{ route('pengajuan.evaluasi-diri', $latestApp) }}"
                class="bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white px-4 sm:px-5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-md shadow-primary-700/20 flex items-center gap-1.5"
                wire:navigate
            >
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">fact_check</span>
                <span class="hidden sm:inline">Evaluasi Diri</span>
            </a>
        @endif

        <a
            href="{{ route('pengajuan.index') }}"
            class="bg-white border border-slate-200 text-slate-800 px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-medium hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-2xs"
            wire:navigate
        >
            <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
            <span class="hidden md:inline">Daftar Pengajuan</span>
        </a>

        <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>

        <!-- User Profile & Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                title="Keluar / Logout"
                class="text-red-600 hover:bg-red-50 px-3 py-2 rounded-xl flex items-center gap-1.5 transition-all text-xs sm:text-sm font-medium border border-transparent hover:border-red-200"
            >
                <span class="material-symbols-outlined text-[18px]">logout</span>
                <span class="hidden sm:inline">Keluar</span>
            </button>
        </form>
    </div>
</header>
