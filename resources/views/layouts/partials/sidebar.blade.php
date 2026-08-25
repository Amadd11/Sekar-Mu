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
        <!-- Section: UTAMA (All Roles) -->
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
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('penilaian.index') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('penilaian.index') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">clinical_notes</span>
                        <span>Daftar Penugasan</span>
                    </a>
                @endhasanyrole
            </nav>
        </div>

        <!-- Section: WORKSPACE PENILAIAN ASESOR (Reviewer Only) -->
        @hasrole('reviewer')
        @php
            $targetPenilaian = request()->route('suratPengajuan') ?? $latestApp;
            $rawPenilaianTab = request()->query('tab', 'penilaian');
            $currentPenilaianTab = in_array($rawPenilaianTab, ['dokumen', 'berkas', 'protokol'])
                ? 'dokumen'
                : (in_array($rawPenilaianTab, ['rekomendasi', 'catatan']) ? 'rekomendasi' : 'penilaian');
        @endphp
        @if ($targetPenilaian)
        <div>
            <div class="px-3 flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-teal-200/70 uppercase tracking-wider">Penilaian Asesor</p>
                <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-teal-400/20 text-teal-200">{{ $targetPenilaian->formatted_id }}</span>
            </div>
            <nav class="space-y-1">
                <a
                    href="{{ route('penilaian.show', [$targetPenilaian, 'tab' => 'penilaian']) }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ (request()->routeIs('penilaian.show') && $currentPenilaianTab === 'penilaian') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[20px] {{ (request()->routeIs('penilaian.show') && $currentPenilaianTab === 'penilaian') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}" style="font-variation-settings: 'FILL' 1;">fact_check</span>
                    <span>Penilaian</span>
                </a>

                <a
                    href="{{ route('penilaian.show', [$targetPenilaian, 'tab' => 'dokumen']) }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ (request()->routeIs('penilaian.show') && $currentPenilaianTab === 'dokumen') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[20px] {{ (request()->routeIs('penilaian.show') && $currentPenilaianTab === 'dokumen') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">folder</span>
                    <span>Dokumen</span>
                </a>

                <a
                    href="{{ route('penilaian.show', [$targetPenilaian, 'tab' => 'rekomendasi']) }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ (request()->routeIs('penilaian.show') && $currentPenilaianTab === 'rekomendasi') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[20px] {{ (request()->routeIs('penilaian.show') && $currentPenilaianTab === 'rekomendasi') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">gavel</span>
                    <span>Form Rekomendasi</span>
                </a>
            </nav>
        </div>
        @endif
        @endhasrole

        <!-- Section: BORANG PENGAJUAN B01 (Applicant, Ketua/Anggota KEPK, Admin) -->
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
                @elseif (auth()->user()->can('create', \App\Models\SuratPengajuan::class))
                    <a
                        href="{{ route('pengajuan.create') }}"
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
        @if ($latestApp && auth()->user()->can('view', $latestApp))
            <div>
                <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Hasil & Pelaporan</p>
                <nav class="space-y-1">
                    <a
                        href="{{ route('pengajuan.show', $latestApp) }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('pengajuan.show') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                        wire:navigate
                    >
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('pengajuan.show') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">verified</span>
                        <span>Hasil Akreditasi</span>
                    </a>
                </nav>
            </div>
        @endif

        <!-- Section: MASTER DATA & PENGATURAN (Admin Only) -->
        @hasrole('admin')
        <div>
            <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Master Data & Pengaturan</p>
            <nav class="space-y-1">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('admin.users.*') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('admin.users.*') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">manage_accounts</span>
                    <span>Manajemen Akun</span>
                </a>

                <a
                    href="{{ route('admin.kriteria.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition group {{ request()->routeIs('admin.kriteria.*') ? 'bg-[#225c84] text-white font-bold border-l-4 border-teal-300 shadow-2xs' : 'text-teal-100/80 hover:bg-[#1f5379] hover:text-white' }}"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('admin.kriteria.*') ? 'text-teal-300' : 'text-teal-200/70 group-hover:text-white' }}">tune</span>
                    <span>Kriteria & Acuan (164)</span>
                </a>
            </nav>
        </div>
        @endhasrole
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
                <button type="submit" title="Keluar" class="p-1 rounded-lg text-teal-200 hover:text-white hover:bg-[#1f5379] transition cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>