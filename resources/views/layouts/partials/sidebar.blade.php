@php
    $user = auth()->user();
    $latestApp = \App\Models\SuratPengajuan::where('user_id', $user->id)->latest()->first();
    if (!$latestApp) {
        $latestApp = \App\Models\SuratPengajuan::latest()->first();
    }
@endphp

<!-- Left Sidebar (Classic Sekar-Mu Navy Theme) -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 w-[280px] bg-[#174668] text-white flex flex-col transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen shrink-0 shadow-lg border-r border-[#133a57]"
>
    <!-- Brand Header -->
    <div class="p-5 border-b border-[#1f557c]/60 bg-[#133e5f]">
        <div class="flex items-center gap-3">
            <div class="text-3xl select-none">🌸</div>
            <div>
                <div class="text-xl font-black tracking-tight text-white leading-tight font-display">Sekar-Mu</div>
                <div class="text-[10px] font-bold tracking-widest text-pink-300 uppercase mt-0.5">BUNGA SEKAR 🌸</div>
            </div>
        </div>
        <p class="text-[10px] text-teal-100/80 mt-2.5 leading-relaxed">
            Sistem Evaluasi dan Akreditasi Komite Etik Penelitian Kesehatan
        </p>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-5 px-3 space-y-6 text-xs custom-scrollbar">
        <!-- Section: UTAMA -->
        <div>
            <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Utama</p>
            <nav class="space-y-1">
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('dashboard') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('dashboard') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">dashboard</span>
                    <span>Dashboard</span>
                </a>

                @hasanyrole('reviewer|admin')
                    <a
                        href="{{ route('penilaian.index') }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('penilaian.*') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('penilaian.*') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">clinical_notes</span>
                        <span>Portal Penilaian</span>
                    </a>
                @endhasanyrole
            </nav>
        </div>

        <!-- Section: BORANG PENGAJUAN (B01) -->
        @hasanyrole('applicant|ketua_kepk|anggota_kepk|admin')
        <div>
            <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Borang Pengajuan (B01)</p>
            <nav class="space-y-1">
                <a
                    href="{{ route('pengajuan.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('pengajuan.index') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('pengajuan.index') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">description</span>
                    <span>B01-01: Surat Pengajuan</span>
                </a>

                @if ($latestApp)
                    <a
                        href="{{ route('pengajuan.evaluasi-diri', $latestApp) }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('pengajuan.evaluasi-diri') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('pengajuan.evaluasi-diri') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}" style="font-variation-settings: 'FILL' 1;">fact_check</span>
                        <span>B01-03: Evaluasi Diri</span>
                    </a>

                    <a
                        href="{{ route('pengajuan.list-protokol', $latestApp) }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('pengajuan.list-protokol') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('pengajuan.list-protokol') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">list_alt</span>
                        <span>B01-04: List Protokol Riset</span>
                    </a>

                    <a
                        href="{{ route('pengajuan.dokumen', $latestApp) }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('pengajuan.dokumen') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('pengajuan.dokumen') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">folder</span>
                        <span>Dokumen Lampiran</span>
                    </a>
                @else
                    <a
                        href="{{ route('pengajuan.index', ['create' => 1]) }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition text-teal-100/80 hover:bg-[#1f5379] hover:text-white"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] text-teal-300">add_circle</span>
                        <span>Buat Pengajuan Baru</span>
                    </a>
                @endif
            </nav>
        </div>
        @endhasanyrole

        <!-- Section: HASIL & PELAPORAN -->
        <div>
            <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Hasil & Pelaporan</p>
            <nav class="space-y-1">
                @if ($latestApp)
                    <a
                        href="{{ route('pengajuan.show', $latestApp) }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('pengajuan.show') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('pengajuan.show') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">verified</span>
                        <span>Hasil Akreditasi</span>
                    </a>
                @endif
            </nav>
        </div>
    </div>

    <!-- User Profile Footer -->
    <div class="p-3.5 border-t border-[#1f557c]/60 bg-[#133e5f]">
        <div class="flex items-center justify-between px-3 py-2 bg-[#174668]/80 rounded-xl hover:bg-[#225c84] transition-colors">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-2xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-teal-200/70 truncate capitalize mt-1 leading-none">{{ auth()->user()->roles->first()?->name ?? 'User' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar" class="p-1 rounded-lg text-teal-200 hover:text-white hover:bg-[#1f5379] transition">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>