<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 leading-tight">
                    {{ __('Dashboard') }}
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Selamat datang kembali di Sistem Etik Komite Akreditasi & Riset (Sekar-Mu).
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                    {{ auth()->user()->hasRole('admin') ? 'bg-purple-100 text-purple-800 border border-purple-200' : (auth()->user()->hasRole('reviewer') ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-teal-100 text-teal-800 border border-teal-200') }}">
                    Peran: {{ auth()->user()->roles->first()?->name ?? 'User' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-primary-800 via-primary-700 to-teal-600 rounded-2xl p-6 sm:p-8 text-white shadow-sm relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <h2 class="text-xl sm:text-2xl font-bold text-white mb-2">
                    Halo, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-teal-100 text-sm leading-relaxed mb-6">
                    Kelola dan pantau seluruh proses pengajuan, telaah protokol etik penelitian, penilaian mandiri (self-assessment), dan berkas dokumen secara terintegrasi.
                </p>

                @hasanyrole('applicant|admin')
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-primary-800 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-teal-50 transition shadow-sm">
                            <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Pengajuan Baru
                        </a>
                    </div>
                @endhasanyrole
            </div>

            <!-- Decorative background shape -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- Quick Stats / Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="card p-5 hover:border-primary-300 transition duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Pengajuan Aktif</div>
                        <div class="text-2xl font-bold text-slate-900 mt-1">0</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-primary-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100">
                    Draft & Sedang Ditelaah
                </div>
            </div>

            <div class="card p-5 hover:border-primary-300 transition duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Butuh Revisi</div>
                        <div class="text-2xl font-bold text-orange-600 mt-1">0</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100">
                    Menunggu perbaikan pemohon
                </div>
            </div>

            <div class="card p-5 hover:border-primary-300 transition duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Disetujui</div>
                        <div class="text-2xl font-bold text-green-600 mt-1">0</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100">
                    Protokol etik disetujui
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
