<!-- Flash Alerts -->
@if (session('status'))
<div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms x-init="setTimeout(() => show = false, 5000)" class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
    <div class="flex items-center gap-2 font-semibold">
        <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
        <span>{{ session('status') }}</span>
    </div>
    <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg hover:bg-emerald-100/60 transition cursor-pointer" title="Tutup">
        <span class="material-symbols-outlined text-[16px] block">close</span>
    </button>
</div>
@endif

@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms x-init="setTimeout(() => show = false, 7000)" class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
    <div class="flex items-center gap-2 font-semibold">
        <span class="material-symbols-outlined text-rose-600 text-[18px]">error</span>
        <span>{{ session('error') }}</span>
    </div>
    <button type="button" @click="show = false" class="text-rose-400 hover:text-rose-600 p-1 rounded-lg hover:bg-rose-100/60 transition cursor-pointer" title="Tutup">
        <span class="material-symbols-outlined text-[16px] block">close</span>
    </button>
</div>
@endif

<!-- Hero Accreditation Status Card (Minimalist & Clean) -->
<div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
    <div class="p-6 sm:p-7 space-y-5">
        <!-- Header Meta Info & Back Button -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition" wire:navigate>
                    <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                    <span>Daftar Pengajuan</span>
                </a>
                <span class="text-slate-300 font-light">•</span>
                <span class="font-mono text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 border border-slate-200/80">
                    No. {{ $suratPengajuan->formatted_id }}
                </span>
                <x-pengajuan.status-badge :status="$suratPengajuan->status" />
            </div>

            @if (! $isAdmin && $suratPengajuan->isDraft())
            @can('delete', $suratPengajuan)
            <button
                type="button"
                wire:click="konfirmasiHapus"
                class="px-2.5 py-1 text-xs font-medium text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer">
                Hapus Draft
            </button>
            @endcan
            @endif
        </div>

        <!-- Main Title & Score Block -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 pt-1">
            <div class="space-y-1.5 flex-1 max-w-3xl">
                <div class="text-[11px] font-mono text-slate-400 uppercase tracking-wider">
                    {{ $suratPengajuan->kepk->name ?? 'Komisi Etik Penelitian Kesehatan' }}
                    @if ($suratPengajuan->kepk?->institusi)
                    • {{ $suratPengajuan->kepk->institusi->name }}
                    @endif
                </div>
                <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? $suratPengajuan->kepk->name }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    {{ $metrics['prediction']['description'] }}
                </p>
            </div>

            <!-- Minimalist Score Card -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="px-5 py-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center min-w-[140px]">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Skor Kepatuhan
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-900 mt-0.5">
                        {{ $metrics['overall_compliance'] }}%
                    </div>
                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">
                        {{ $metrics['total_answered'] }}/{{ $metrics['total_items'] }} Butir Dinilai
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Navigation Tabs -->
        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-primary-700 hover:bg-primary-600 active:bg-primary-800 transition shadow-2xs cursor-pointer" wire:navigate>
                    <span class="material-symbols-outlined text-[16px]">fact_check</span>
                    <span>Buka Evaluasi Diri</span>
                </a>
                <a href="{{ route('pengajuan.dokumen', $suratPengajuan) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/80 transition cursor-pointer" wire:navigate>
                    <span class="material-symbols-outlined text-[16px] text-slate-400">folder</span>
                    <span>Dokumen Bukti</span>
                </a>
                <a href="{{ route('pengajuan.list-protokol', $suratPengajuan) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/80 transition cursor-pointer" wire:navigate>
                    <span class="material-symbols-outlined text-[16px] text-slate-400">list_alt</span>
                    <span>List Protokol Riset</span>
                </a>
                @can('update', $suratPengajuan)
                <a href="{{ route('pengajuan.formulir-aplikasi', $suratPengajuan) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/80 transition cursor-pointer" wire:navigate>
                    <span class="material-symbols-outlined text-[16px] text-slate-400">edit_document</span>
                    <span>Edit Berkas</span>
                </a>
                @endcan

                <a href="{{ route('pengajuan.matriks', $suratPengajuan) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/80 transition cursor-pointer" wire:navigate>
                    <span class="material-symbols-outlined text-[16px] text-slate-400">table_chart</span>
                    <span>Matriks Tabulasi</span>
                </a>
            </div>
        </div>

        <!-- Admin Control Strip (Clean & Minimalist) -->
        @if ($isAdmin)
        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 text-slate-800">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-[18px] text-slate-400">admin_panel_settings</span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-xs text-slate-800">Panel Keputusan Admin</span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-200/60 text-slate-600 font-medium">Administrator</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            Sahkan status akreditasi KEPK atau tugaskan tim asesor penilai.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 pt-1 lg:pt-0">
                    <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-100 border border-slate-200/80 transition flex items-center gap-1.5 cursor-pointer shadow-2xs" wire:navigate>
                        <span class="material-symbols-outlined text-[15px] text-slate-500">person_add</span>
                        <span>Tugaskan Asesor</span>
                    </a>

                    @if ($canDecide)
                    <div class="h-4 w-px bg-slate-200 mx-0.5 hidden sm:block"></div>

                    @if ($suratPengajuan->isInProgress())
                    {{-- Status: Dalam Proses Evaluasi -> Opsi Keputusan: ACC atau Tolak --}}
                    <button
                        type="button"
                        wire:click="bukaModalAcc"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition flex items-center gap-1.5 cursor-pointer shadow-2xs">
                        <span class="material-symbols-outlined text-[15px]">check_circle</span>
                        <span>ACC (Terakreditasi)</span>
                    </button>

                    <button
                        type="button"
                        wire:click="bukaModalReject"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 bg-white hover:bg-rose-50 border border-slate-200/80 hover:border-rose-200 transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[15px]">cancel</span>
                        <span>Tolak</span>
                    </button>
                    @elseif ($suratPengajuan->isApproved())
                    {{-- Status: Terakreditasi -> Tampilkan indikator & Opsi Buka Kembali --}}
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80">
                        <span class="material-symbols-outlined text-[15px]">verified</span>
                        <span>Telah Disahkan</span>
                    </span>

                    <button
                        type="button"
                        wire:click="bukaModalReopen"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-100 border border-slate-200/80 transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[15px] text-slate-500">lock_open</span>
                        <span>Buka Kembali Evaluasi</span>
                    </button>
                    @elseif ($suratPengajuan->isRejected())
                    {{-- Status: Tidak Lolos -> Tampilkan indikator & Opsi Buka Kembali --}}
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200/80">
                        <span class="material-symbols-outlined text-[15px]">cancel</span>
                        <span>Status: Tidak Lolos</span>
                    </span>

                    <button
                        type="button"
                        wire:click="bukaModalReopen"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-100 border border-slate-200/80 transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[15px] text-slate-500">lock_open</span>
                        <span>Buka Kembali Evaluasi</span>
                    </button>
                    @endif

                    @can('delete', $suratPengajuan)
                    <button
                        type="button"
                        wire:click="konfirmasiHapus"
                        title="Hapus Berkas Pengajuan"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                    @endcan
                    @endif
                </div>
            </div>

            @if ($suratPengajuan->penilaianEtik->isNotEmpty() && $suratPengajuan->isInProgress())
            <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-1.5 text-slate-600 text-[11px]">
                    <span class="material-symbols-outlined text-slate-400 text-[15px]">rate_review</span>
                    <span><strong>{{ $suratPengajuan->penilaianEtik->count() }} Asesor</strong> telah memberikan telaah</span>
                </div>
                <div class="flex items-center gap-1.5 flex-wrap">
                    @foreach ($suratPengajuan->penilaianEtik as $p)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border shadow-2xs {{ $p->badge_rekomendasi }}">
                        <span class="font-normal opacity-80">{{ $p->penilai->name ?? 'Asesor' }}:</span>
                        <span>{{ $p->label_rekomendasi }}</span>
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- 4 Metric Stat Cards (Minimalist & Clean) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
    <!-- Metric 1: Skor Kepatuhan -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Kepatuhan Total</span>
            <span class="material-symbols-outlined text-[16px] text-slate-400">trending_up</span>
        </div>
        <div class="mt-2.5">
            <div class="text-2xl font-bold text-slate-900 font-display">
                {{ $metrics['overall_compliance'] }}%
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 font-mono">
                {{ $metrics['total_answered'] }}/{{ $metrics['total_items'] }} butir terisi
            </p>
        </div>
    </div>

    <!-- Metric 2: Nilai A -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Nilai A (100%)</span>
            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
        </div>
        <div class="mt-2.5">
            <div class="text-2xl font-bold text-slate-900 font-display">
                {{ $metrics['score_counts']['A'] }}
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 font-mono">
                Terpenuhi Penuh
            </p>
        </div>
    </div>

    <!-- Metric 3: Nilai B -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Nilai B (50%)</span>
            <span class="material-symbols-outlined text-[16px] text-amber-500">change_circle</span>
        </div>
        <div class="mt-2.5">
            <div class="text-2xl font-bold text-slate-900 font-display">
                {{ $metrics['score_counts']['B'] }}
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 font-mono">
                Terpenuhi Sebagian
            </p>
        </div>
    </div>

    <!-- Metric 4: Nilai C -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Nilai C (0%)</span>
            <span class="material-symbols-outlined text-[16px] text-slate-400">cancel</span>
        </div>
        <div class="mt-2.5">
            <div class="text-2xl font-bold text-slate-900 font-display">
                {{ $metrics['score_counts']['C'] }}
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 font-mono">
                Tidak Terpenuhi
            </p>
        </div>
    </div>
</div>