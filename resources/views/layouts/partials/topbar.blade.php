@php
    $routeName = request()->route()?->getName() ?? '';
    
    // Dynamic Page Title & Breadcrumb mapping
    $pageTitle = 'Dashboard';
    $parentSection = 'Utama';

    if (str_starts_with($routeName, 'pengajuan.evaluasi-diri')) {
        $parentSection = 'Borang Akreditasi';
        $pageTitle = 'Evaluasi Diri';
    } elseif (str_starts_with($routeName, 'pengajuan.dokumen')) {
        $parentSection = 'Berkas Pengajuan';
        $pageTitle = 'Dokumen Bukti Dukung';
    } elseif (str_starts_with($routeName, 'pengajuan.list-protokol')) {
        $parentSection = 'Berkas Pengajuan';
        $pageTitle = 'Daftar Protokol Riset';
    } elseif (str_starts_with($routeName, 'pengajuan.formulir-aplikasi')) {
        $parentSection = 'Berkas Pengajuan';
        $pageTitle = 'Formulir Aplikasi';
    } elseif (str_starts_with($routeName, 'pengajuan.profil')) {
        $parentSection = 'Berkas Pengajuan';
        $pageTitle = 'Profil & Keanggotaan KEPK';
    } elseif (str_starts_with($routeName, 'pengajuan.matriks')) {
        $parentSection = 'Hasil & Pelaporan';
        $pageTitle = 'Matriks Tabulasi';
    } elseif (str_starts_with($routeName, 'pengajuan.show')) {
        $parentSection = 'Hasil & Pelaporan';
        $pageTitle = 'Hasil Akreditasi';
    } elseif (str_starts_with($routeName, 'pengajuan.create')) {
        $parentSection = 'Permohonan Baru';
        $pageTitle = 'Buat Pengajuan Akreditasi';
    } elseif (str_starts_with($routeName, 'pengajuan.')) {
        $parentSection = 'Pengajuan';
        $pageTitle = 'Daftar Permohonan Akreditasi';
    } elseif (str_starts_with($routeName, 'penilaian.show')) {
        $parentSection = 'Penilaian Etik';
        $pageTitle = 'Lembar Kerja Asesor';
    } elseif (str_starts_with($routeName, 'penilaian.tugaskan')) {
        $parentSection = 'Penilaian Etik';
        $pageTitle = 'Penugasan Tim Asesor';
    } elseif (str_starts_with($routeName, 'penilaian.')) {
        $parentSection = 'Penilaian Etik';
        $pageTitle = 'Lembar Kerja Penilaian Asesor';
    } elseif (str_starts_with($routeName, 'admin.kriteria')) {
        $parentSection = 'Master Data';
        $pageTitle = 'Kriteria & Acuan Standar';
    } elseif (str_starts_with($routeName, 'admin.users')) {
        $parentSection = 'Master Data';
        $pageTitle = 'Manajemen Pengguna';
    }
@endphp

<!-- Modern Topbar -->
<header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
    <!-- Left: Mobile Menu & Dynamic Breadcrumb -->
    <div class="flex items-center gap-3">
        <button
            type="button"
            @click="sidebarOpen = true"
            class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 lg:hidden focus:outline-none cursor-pointer"
            aria-label="Buka Menu Sidebar"
        >
            <span class="material-symbols-outlined text-[22px]">menu</span>
        </button>

        <div class="flex items-center gap-2">
            <div class="hidden sm:flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                <span>{{ $parentSection }}</span>
                <span class="material-symbols-outlined text-[13px] text-slate-300">chevron_right</span>
            </div>
            <h1 class="text-sm sm:text-base font-extrabold text-slate-900 font-display tracking-tight">
                {{ $pageTitle }}
            </h1>
        </div>
    </div>

    <!-- Right: Status Tag, User Profile & Quick Logout -->
    <div class="flex items-center gap-3">
        <!-- Standar Tag -->
        <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] font-medium text-slate-600">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
            <span>Standar WHO & KEPPKN</span>
        </div>

        <div class="h-5 w-px bg-slate-200 hidden md:block"></div>

        <!-- User Profile Pill -->
        <div class="flex items-center gap-2.5 px-2.5 py-1 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
            <div class="w-7 h-7 rounded-lg bg-primary-700 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="text-left hidden sm:block">
                <div class="text-xs font-bold text-slate-900 leading-tight truncate max-w-[130px]">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider leading-tight mt-0.5">
                    {{ auth()->user()->roles->first()?->name ?? 'User' }}
                </div>
            </div>
        </div>

        <!-- Logout Action -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                title="Keluar / Logout"
                class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer flex items-center justify-center border border-transparent hover:border-rose-100"
            >
                <span class="material-symbols-outlined text-[20px]">logout</span>
            </button>
        </form>
    </div>
</header>
