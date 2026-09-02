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
                <x-sidebar-link route="dashboard" icon="dashboard" label="Dashboard" />
            </nav>
        </div>

        <!-- Section: WORKSPACE PENILAIAN ASESOR (Asessor Only) -->
        @hasrole('asessor')
            @php
                $targetPenilaian = request()->route('suratPengajuan') ?? $latestApp;
            @endphp
            @if ($targetPenilaian)
                <div>
                    <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Penilaian Asesor</p>
                    <nav class="space-y-1">
                        <x-sidebar-link route="penilaian.show" :routeParam="$targetPenilaian" :queryParam="['tab' => 'penilaian']" icon="fact_check" label="Penilaian" :fillIcon="true" />
                        <x-sidebar-link route="penilaian.show" :routeParam="$targetPenilaian" :queryParam="['tab' => 'dokumen']" icon="folder" label="Dokumen" />
                        <x-sidebar-link route="penilaian.show" :routeParam="$targetPenilaian" :queryParam="['tab' => 'rekomendasi']" icon="gavel" label="Form Rekomendasi" />
                    </nav>
                </div>
            @endif
        @endhasrole

        <!-- Section: BORANG PENGAJUAN B01 (Ketua KEPK, Anggota & Admin) -->
        @hasanyrole('ketua_kepk|anggota|admin')
            <div>
                <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Borang Pengajuan (B01)</p>
                <nav class="space-y-1">
                    @if (! auth()->user()->isAnggota())
                        <x-sidebar-link route="pengajuan.index" icon="description" label="B01-01: Surat Pengajuan" />
                    @endif

                    @if ($latestApp)
                        @if (! auth()->user()->isAnggota())
                            <x-sidebar-link route="pengajuan.formulir-aplikasi" :routeParam="$latestApp" icon="apartment" label="B01-02: Formulir Aplikasi" />
                        @endif

                        <x-sidebar-link route="pengajuan.evaluasi-diri" :routeParam="$latestApp" icon="fact_check" label="B01-03: Evaluasi Diri" :fillIcon="true" />

                        @if (! auth()->user()->isAnggota())
                            <x-sidebar-link route="pengajuan.list-protokol" :routeParam="$latestApp" icon="list_alt" label="B01-04: List Protokol Riset" />
                        @endif

                        <x-sidebar-link route="pengajuan.dokumen" :routeParam="$latestApp" icon="folder" label="Dokumen Lampiran" />
                    @endif
                </nav>
            </div>
        @endhasanyrole

        <!-- Section: HASIL & PELAPORAN -->
        @if ($latestApp && (! auth()->user()->isAsessor() || auth()->user()->isAdmin()) && auth()->user()->can('view', $latestApp))
            <div>
                <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Hasil & Pelaporan</p>
                <nav class="space-y-1">
                    <x-sidebar-link route="pengajuan.show" :routeParam="$latestApp" icon="verified" label="Hasil Akreditasi" />
                    <x-sidebar-link route="pengajuan.matriks" :routeParam="$latestApp" icon="table_chart" label="Matriks Tabulasi" />
                </nav>
            </div>
        @endif

        <!-- Section: MASTER DATA & PENGATURAN (Admin Only) -->
        @hasrole('admin')
            <div>
                <p class="px-3 text-[10px] font-bold text-teal-200/70 uppercase tracking-wider mb-2">Master Data & Pengaturan</p>
                <nav class="space-y-1">
                    <x-sidebar-link route="admin.users.index" icon="manage_accounts" label="Manajemen Akun" />
                    <x-sidebar-link route="admin.kriteria.index" icon="tune" label="Kriteria & Acuan" />
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
            <button
                type="button"
                @click="logoutModalOpen = true"
                title="Keluar dari Aplikasi"
                class="p-1.5 rounded-lg text-teal-200 hover:text-white hover:bg-[#1f5379] transition cursor-pointer"
            >
                <span class="material-symbols-outlined text-[18px]">logout</span>
            </button>
        </div>
    </div>
</aside>