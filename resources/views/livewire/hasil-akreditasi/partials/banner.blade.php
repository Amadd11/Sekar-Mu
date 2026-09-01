<!-- Flash Alerts -->
@if (session('status'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
    <div class="flex items-center gap-2 font-semibold">
        <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
        <span>{{ session('status') }}</span>
    </div>
</div>
@endif

@if (session('error'))
<div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
    <div class="flex items-center gap-2 font-semibold">
        <span class="material-symbols-outlined text-rose-600 text-[18px]">error</span>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

<!-- Hero Accreditation Status & Decision Card -->
<div class="rounded-3xl border overflow-hidden shadow-sm transition-all {{ $suratPengajuan->isApproved() ? 'bg-gradient-to-r from-emerald-900 via-teal-900 to-[#174668] text-white border-emerald-700' : ($suratPengajuan->isRejected() ? 'bg-gradient-to-r from-rose-950 via-slate-900 to-slate-900 text-white border-rose-800' : 'bg-white border-slate-200/90 text-slate-900') }}">
    <!-- Top Accent Strip -->
    <div class="h-1.5 {{ $suratPengajuan->isApproved() ? 'bg-emerald-400' : ($suratPengajuan->isRejected() ? 'bg-rose-500' : 'bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]') }}"></div>

    <div class="p-6 sm:p-8 space-y-6">
        <!-- Header Meta Info & Back Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-xl transition {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}" wire:navigate>
                    <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                    <span>Daftar Pengajuan</span>
                </a>
                <span class="font-mono text-xs font-bold px-3 py-1.5 rounded-xl border {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 text-white border-white/20' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                    No. {{ $suratPengajuan->formatted_id }}
                </span>
                <x-pengajuan.status-badge :status="$suratPengajuan->status" />
            </div>

            <div class="flex items-center gap-2">
                @if ($suratPengajuan->isInProgress())
                @can('delete', $suratPengajuan)
                <button
                    type="button"
                    wire:click="hapusDraft"
                    wire:confirm="Yakin ingin menghapus berkas pengajuan ini secara permanen?"
                    class="px-3.5 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl border border-rose-200 transition cursor-pointer">
                    Hapus
                </button>
                @endcan
                @endif
            </div>
        </div>

        <!-- Main Title & Score Block -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pt-2">
            <div class="space-y-2 flex-1 max-w-3xl">
                <div class="text-xs font-mono font-semibold uppercase tracking-wider {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-teal-200' : 'text-primary-700' }}">
                    {{ $suratPengajuan->kepk->name ?? 'Komisi Etik Penelitian Kesehatan' }} • {{ $suratPengajuan->kepk->institusi->name ?? '-' }}
                </div>
                <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-white' : 'text-slate-900' }}">
                    {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? $suratPengajuan->kepk->name }}
                </h1>
                <p class="text-xs sm:text-sm leading-relaxed {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-slate-200' : 'text-slate-600' }}">
                    {{ $metrics['prediction']['description'] }}
                </p>
            </div>

            <!-- Highlight Score Card -->
            <div class="flex items-center gap-4 shrink-0">
                <div class="p-4 sm:p-5 rounded-2xl text-center min-w-[150px] {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 border border-white/20' : 'bg-slate-50 border border-slate-200/80 shadow-2xs' }}">
                    <div class="text-[11px] font-bold uppercase tracking-wider {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-teal-200' : 'text-slate-500' }}">
                        Skor Kepatuhan
                    </div>
                    <div class="text-3xl sm:text-4xl font-black font-mono mt-1 {{ $suratPengajuan->isApproved() ? 'text-emerald-300' : ($suratPengajuan->isRejected() ? 'text-rose-300' : 'text-primary-700') }}">
                        {{ $metrics['overall_compliance'] }}%
                    </div>
                    <div class="text-[10px] font-mono mt-1 opacity-80">
                        {{ $metrics['total_answered'] }}/{{ $metrics['total_items'] }} Butir Dinilai
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Navigation Pills -->
        <div class="pt-4 border-t flex flex-wrap items-center justify-between gap-4 {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'border-white/10' : 'border-slate-100' }}">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 active:bg-primary-800 transition shadow-xs cursor-pointer" wire:navigate>
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">fact_check</span>
                    <span>Buka Evaluasi Diri</span>
                </a>
                <a href="{{ route('pengajuan.dokumen', $suratPengajuan) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 text-white hover:bg-white/20 border border-white/20' : 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200' }}" wire:navigate>
                    <span class="material-symbols-outlined text-[18px]">folder</span>
                    <span>Dokumen Bukti</span>
                </a>
                <a href="{{ route('pengajuan.list-protokol', $suratPengajuan) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 text-white hover:bg-white/20 border border-white/20' : 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200' }}" wire:navigate>
                    <span class="material-symbols-outlined text-[18px]">list_alt</span>
                    <span>List Protokol Riset</span>
                </a>
                @if (! auth()->user()->isAsessor() || auth()->user()->isAdmin())
                @if (! $suratPengajuan->isApproved())
                <a href="{{ route('pengajuan.formulir-aplikasi', $suratPengajuan) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 text-white hover:bg-white/20 border border-white/20' : 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200' }}" wire:navigate>
                    <span class="material-symbols-outlined text-[18px]">edit_document</span>
                    <span>Edit Berkas</span>
                </a>
                @endif
                <a href="{{ route('pengajuan.matriks', $suratPengajuan) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 text-white hover:bg-white/20 border border-white/20' : 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200' }}" wire:navigate>
                    <span class="material-symbols-outlined text-[18px]">table_chart</span>
                    <span>Matriks Tabulasi</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Admin Control Strip -->
        @if ($isAdmin)
        @php
        $isHeroDark = $suratPengajuan->isApproved() || $suratPengajuan->isRejected();
        @endphp
        <div class="p-4 sm:p-5 rounded-2xl transition-all {{ $isHeroDark ? 'bg-white/10 backdrop-blur-md border border-white/15 text-white' : 'bg-gradient-to-r from-slate-50 via-slate-50/80 to-teal-50/30 border border-slate-200/90 text-slate-900 shadow-2xs' }}">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl {{ $isHeroDark ? 'bg-white/15 text-teal-300' : 'bg-primary-100 text-primary-800' }} flex items-center justify-center shrink-0 shadow-2xs">
                        <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-xs sm:text-sm tracking-tight {{ $isHeroDark ? 'text-white' : 'text-slate-900' }}">
                                Panel Keputusan Administrator
                            </h4>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $isHeroDark ? 'bg-white/15 text-teal-200 border border-white/20' : 'bg-primary-50 text-primary-700 border border-primary-200/70' }}">
                                Hak Akses Admin
                            </span>
                        </div>
                        <p class="text-[11px] mt-0.5 leading-relaxed {{ $isHeroDark ? 'text-white/75' : 'text-slate-500' }}">
                            Sahkan status akreditasi KEPK atau atur penugasan tim asesor penilai.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 pt-1 lg:pt-0">
                    <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs {{ $isHeroDark ? 'bg-white/15 hover:bg-white/25 text-white border border-white/25' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-300/80' }}" wire:navigate>
                        <span class="material-symbols-outlined text-[16px]">person_add</span>
                        <span>Tugaskan Asesor</span>
                    </a>

                    @if ($canDecide)
                    <div class="h-6 w-px {{ $isHeroDark ? 'bg-white/20' : 'bg-slate-200' }} mx-1 hidden sm:block"></div>

                    <button
                        type="button"
                        wire:click="putuskanStatus('approved')"
                        wire:confirm="Yakin ingin MENYETUJUI (ACC / Terakreditasi) permohonan akreditasi ini?"
                        class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 active:scale-95 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/25 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span>ACC (Terakreditasi)</span>
                    </button>

                    <button
                        type="button"
                        wire:click="putuskanStatus('rejected')"
                        wire:confirm="Yakin ingin MENOLAK (Tidak Lolos) permohonan akreditasi ini?"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 active:scale-95 cursor-pointer {{ $isHeroDark ? 'bg-rose-500/20 hover:bg-rose-600 text-rose-200 hover:text-white border border-rose-500/30' : 'bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 hover:border-rose-600' }}">
                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                        <span>Tolak</span>
                    </button>

                    @if (! $suratPengajuan->isInProgress())
                    <button
                        type="button"
                        wire:click="putuskanStatus('in_progress')"
                        wire:confirm="Buka kembali pengajuan ke status Proses Evaluasi?"
                        class="px-3.5 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer {{ $isHeroDark ? 'bg-white/10 hover:bg-white/20 text-slate-200 border border-white/20' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200' }}">
                        <span class="material-symbols-outlined text-[15px]">lock_open</span>
                        <span>Buka Kembali</span>
                    </button>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- 4 Metric Stat Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Metric 1: Skor Kepatuhan -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
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

    <!-- Metric 2: Nilai A -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai A (100%)</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-display">
                {{ $metrics['score_counts']['A'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5 font-mono">
                Terpenuhi Penuh
            </p>
        </div>
    </div>

    <!-- Metric 3: Nilai B -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai B (50%)</span>
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">change_circle</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-amber-500 font-display">
                {{ $metrics['score_counts']['B'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5 font-mono">
                Terpenuhi Sebagian
            </p>
        </div>
    </div>

    <!-- Metric 4: Nilai C -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai C (0%)</span>
            <div class="w-8 h-8 rounded-xl {{ $metrics['score_counts']['C'] > 0 ? 'bg-rose-50 text-rose-700' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">cancel</span>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black font-display {{ $metrics['score_counts']['C'] > 0 ? 'text-rose-600' : 'text-slate-600' }}">
                {{ $metrics['score_counts']['C'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5 font-mono">
                Tidak Terpenuhi
            </p>
        </div>
    </div>
</div>