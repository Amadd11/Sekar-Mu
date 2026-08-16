@php
    $user = auth()->user();
    $latestApp = \App\Models\Application::where('user_id', $user->id)->latest()->first();
    if (!$latestApp && ($user->isAdmin() || $user->isReviewer())) {
        $latestApp = \App\Models\Application::latest()->first();
    }
@endphp

<!-- Left Sidebar -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-[#174668] text-white flex flex-col transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:translate-x-0 shrink-0 shadow-lg border-r border-[#133a57]"
>
    <!-- Brand Header -->
    <div class="p-5 border-b border-[#1f557c]/60 bg-[#133e5f]">
        <div class="flex items-center gap-3">
            <div class="text-3xl select-none">🌸</div>
            <div>
                <div class="text-xl font-black tracking-tight text-white leading-tight">Sekar-Mu</div>
                <div class="text-[10px] font-bold tracking-widest text-pink-300 uppercase mt-0.5">BUNGA SEKAR 🌸</div>
            </div>
        </div>
        <p class="text-[10px] text-teal-100/70 mt-3 leading-relaxed">
            Sistem Evaluasi dan Akreditasi Komite Etik Penelitian Kesehatan
        </p>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-6 text-xs custom-scrollbar">
        <!-- Section: UTAMA -->
        <div>
            <div class="px-3 text-[10px] font-bold tracking-wider text-teal-200/60 uppercase mb-2">
                Utama
            </div>
            <nav class="space-y-1">
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#225c84] text-white font-bold shadow-2xs border-l-3 border-teal-400' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="text-sm">📊</span>
                    <span>Dashboard</span>
                </a>

                @hasanyrole('reviewer|admin')
                    <a
                        href="{{ route('reviews.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('reviews.*') ? 'bg-[#225c84] text-white font-bold shadow-2xs border-l-3 border-teal-400' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="text-sm">🔍</span>
                        <span>Portal Telaah Etik</span>
                    </a>
                @endhasanyrole
            </nav>
        </div>

        <!-- Section: FORMULIR PENGAJUAN -->
        <div>
            <div class="px-3 text-[10px] font-bold tracking-wider text-teal-200/60 uppercase mb-2">
                Formulir Pengajuan
            </div>
            <nav class="space-y-1">
                <a
                    href="{{ route('applications.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('applications.index') ? 'bg-[#225c84] text-white font-bold border-l-3 border-teal-400' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="text-sm">📄</span>
                    <span>Surat Pengajuan (B01-01)</span>
                </a>

                @if ($latestApp)
                    <a
                        href="{{ route('applications.information', $latestApp) }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('applications.information') ? 'bg-[#225c84] text-white font-bold border-l-3 border-teal-400' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="text-sm">📝</span>
                        <span>Formulir Aplikasi (B01-02)</span>
                    </a>

                    <a
                        href="{{ route('applications.self-assessment', $latestApp) }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('applications.self-assessment') ? 'bg-[#225c84] text-white font-bold border-l-3 border-teal-400' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="text-sm">📋</span>
                        <span>Evaluasi Diri (B01-03)</span>
                    </a>

                    <a
                        href="{{ route('applications.protocols', $latestApp) }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('applications.protocols') ? 'bg-[#225c84] text-white font-bold border-l-3 border-teal-400' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="text-sm">📑</span>
                        <span>List Protokol (B01-04)</span>
                    </a>

                    <a
                        href="{{ route('applications.documents', $latestApp) }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('applications.documents') ? 'bg-[#225c84] text-white font-bold border-l-3 border-teal-400' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="text-sm">📁</span>
                        <span>Dokumen Lampiran</span>
                    </a>
                @else
                    <a
                        href="{{ route('applications.create') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition text-teal-100/80 hover:bg-[#1f5379] hover:text-white"
                        wire:navigate
                    >
                        <span class="text-sm">📝</span>
                        <span>Buat Pengajuan Baru</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Section: BORANG EVALUASI DIRI -->
        <div>
            <div class="px-3 text-[10px] font-bold tracking-wider text-teal-200/60 uppercase mb-2">
                Borang Evaluasi Diri
            </div>
            <nav class="space-y-1">
                @if ($latestApp)
                    <a href="{{ route('applications.self-assessment', $latestApp) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-teal-100/80 hover:bg-[#1f5379] hover:text-white transition" wire:navigate>
                        <span class="text-sm">🏛️</span>
                        <span>A — Struktur & Komposisi</span>
                    </a>
                    <a href="{{ route('applications.self-assessment', $latestApp) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-teal-100/80 hover:bg-[#1f5379] hover:text-white transition" wire:navigate>
                        <span class="text-sm">📜</span>
                        <span>B — Kepatuhan Kebijakan</span>
                    </a>
                    <a href="{{ route('applications.self-assessment', $latestApp) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-teal-100/80 hover:bg-[#1f5379] hover:text-white transition" wire:navigate>
                        <span class="text-sm">🔍</span>
                        <span>C — Kelengkapan Telaah</span>
                    </a>
                    <a href="{{ route('applications.self-assessment', $latestApp) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-teal-100/80 hover:bg-[#1f5379] hover:text-white transition" wire:navigate>
                        <span class="text-sm">📢</span>
                        <span>D — Komunikasi Keputusan</span>
                    </a>
                    <a href="{{ route('applications.self-assessment', $latestApp) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-teal-100/80 hover:bg-[#1f5379] hover:text-white transition" wire:navigate>
                        <span class="text-sm">📁</span>
                        <span>E — Dokumentasi & Arsip</span>
                    </a>
                @else
                    <a href="{{ route('applications.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-teal-100/80 hover:bg-[#1f5379] hover:text-white transition" wire:navigate>
                        <span class="text-sm">📋</span>
                        <span>Mulai Evaluasi Diri</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Section: LAPORAN -->
        <div>
            <div class="px-3 text-[10px] font-bold tracking-wider text-teal-200/60 uppercase mb-2">
                Laporan
            </div>
            <nav class="space-y-1">
                <a href="{{ route('applications.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-teal-100/80 hover:bg-[#1f5379] hover:text-white transition" wire:navigate>
                    <span class="text-sm">📊</span>
                    <span>Rekapitulasi & Laporan</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- User Footer Card -->
    <div class="p-3 border-t border-[#1f557c]/60 bg-[#133e5f]">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-teal-200/70 truncate capitalize">{{ auth()->user()->roles->first()?->name ?? 'User' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar" class="p-1.5 rounded-lg text-teal-200/80 hover:text-red-300 hover:bg-red-950/40 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
