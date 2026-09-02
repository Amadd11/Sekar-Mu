<div class="space-y-6 max-w-7xl mx-auto pb-12">
    {{-- ========================================================================= --}}
    {{-- 1. DASHBOARD KHUSUS ASESOR AKREDITASI                                     --}}
    {{-- ========================================================================= --}}
    @if ($user->isAsessor() && ! $user->isAdmin())
        <!-- Banner Asesor -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
            <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>
            <div class="p-6 sm:p-7 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                        <span class="material-symbols-outlined text-[26px]">rate_review</span>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="bg-teal-50 text-teal-700 font-display text-[11px] px-2.5 py-0.5 rounded-md font-bold border border-teal-200/70">
                                Portal Asesor Akreditasi
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Ruang Kerja Aktif</span>
                            </span>
                        </div>
                        <h1 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight">
                            Selamat Datang, {{ $user->name }}
                        </h1>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed max-w-2xl">
                            Kelola antrean telaah borang KEPK, lakukan verifikasi bukti dukung butir standar, dan berikan rekomendasi independen secara terstruktur.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workload KPI Cards (Assessor Focus) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Total Penugasan</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 font-display">{{ $totalAssigned }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Berkas KEPK ditugaskan ke Anda</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-amber-600 font-bold uppercase tracking-wider">Perlu Diselesaikan</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 mt-1 font-display">{{ $inProgressCount }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Penilaian butir/rekomendasi berjalan</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-emerald-600 font-bold uppercase tracking-wider">Penilaian Rampung</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1 font-display">{{ $completedCount }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Seluruh butir dinilai & rekomendasi terkirim</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-primary-700 font-bold uppercase tracking-wider">Catatan & Temuan</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-primary-700 mt-1 font-display">{{ $totalFindingsCount }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Catatan temuan aktif yang Anda tulis</div>
            </div>
        </div>

        <!-- Main Grid: Task Queue (Left) & Support Widgets (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Left 2 Cols: Antrean Tugas Penugasan KEPK -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span>
                            <h3 class="text-sm font-bold text-slate-900">Antrean Tugas Penelaahan KEPK</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-mono">{{ $assignedSubmissions->count() }} Permohonan</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($assignedSubmissions as $item)
                            @php
                                $prog = $item->my_progress;
                            @endphp
                            <div class="p-5 hover:bg-slate-50/70 transition space-y-3.5">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded-md border border-primary-100">
                                                No. {{ $item->formatted_id }}
                                            </span>
                                            <span class="font-bold text-slate-900 text-sm">
                                                {{ $item->formulirAplikasi->nama_institusi ?? $item->kepk->name }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $item->kepk->name }} • {{ $item->kepk->institusi->name ?? '-' }}
                                        </div>
                                    </div>
                                    <x-pengajuan.status-badge :status="$item->status" />
                                </div>

                                <!-- Progress Bar Penilaian Pribadi -->
                                <div class="space-y-1.5 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-semibold text-slate-700 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px] text-slate-400">checklist</span>
                                            <span>Progres Penilaian Butir Anda</span>
                                        </span>
                                        <span class="font-mono font-bold text-slate-900">
                                            {{ $prog['scored_count'] }}/{{ $prog['total_items'] }} Butir
                                            <span class="text-primary-700">({{ $prog['percentage'] }}%)</span>
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                                        <div
                                            class="{{ $prog['percentage'] >= 100 ? 'bg-emerald-500' : 'bg-primary-700' }} h-2 rounded-full transition-all duration-500"
                                            style="width: {{ $prog['percentage'] }}%"></div>
                                    </div>
                                </div>

                                <!-- Meta Strip: Nilai A/B/C, Temuan, Rekomendasi, dan Tombol Aksi -->
                                <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            A: {{ $prog['count_a'] }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            B: {{ $prog['count_b'] }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            C: {{ $prog['count_c'] }}
                                        </span>
                                        @if ($prog['findings_count'] > 0)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            {{ $prog['findings_count'] }} Temuan
                                        </span>
                                        @endif
                                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold border {{ $prog['recommendation_badge'] }}">
                                            {{ $prog['recommendation_label'] }}
                                        </span>
                                    </div>

                                    <a
                                        href="{{ route('penilaian.show', $item) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl transition shadow-xs cursor-pointer {{ $prog['is_completed'] ? 'bg-slate-100 hover:bg-slate-200 text-slate-700' : 'bg-primary-700 hover:bg-primary-600 text-white shadow-primary-700/20' }}"
                                        wire:navigate>
                                        <span class="material-symbols-outlined text-[16px]">{{ $prog['is_completed'] ? 'visibility' : 'edit_note' }}</span>
                                        <span>{{ $prog['is_completed'] ? 'Buka Lembar Kerja' : 'Lanjutkan Penilaian' }}</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-slate-400 space-y-2">
                                <span class="material-symbols-outlined text-[40px] text-slate-300">fact_check</span>
                                <p class="font-semibold text-slate-600 text-xs">Belum ada berkas KEPK yang ditugaskan kepada Anda saat ini.</p>
                                <p class="text-[11px] text-slate-400">Penugasan berkas dilakukan oleh Administrator sistem.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Panduan Rubrik Skor & Catatan Terkini -->
            <div class="space-y-4">
                <!-- Cheatsheet Rubrik Penilaian -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                        <span class="material-symbols-outlined text-[18px] text-primary-700">menu_book</span>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Panduan Rubrik Skor (WHO & KEPPKN)</h3>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100 space-y-1">
                            <div class="flex items-center gap-1.5 font-bold text-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Nilai A (100% - Terpenuhi Lengkap)</span>
                            </div>
                            <p class="text-[11px] text-emerald-900/80 leading-relaxed">
                                Seluruh kriteria butir terpenuhi secara substantif dan didukung dokumen bukti (SK, SOP, Arsip) yang valid dan memadai.
                            </p>
                        </div>

                        <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-100 space-y-1">
                            <div class="flex items-center gap-1.5 font-bold text-amber-800">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>Nilai B (50% - Terpenuhi Sebagian)</span>
                            </div>
                            <p class="text-[11px] text-amber-900/80 leading-relaxed">
                                Kriteria telah dilaksanakan namun dokumen bukti belum lengkap, masih dalam proses penetapan, atau memerlukan perbaikan minor.
                            </p>
                        </div>

                        <div class="p-2.5 rounded-xl bg-rose-50/70 border border-rose-100 space-y-1">
                            <div class="flex items-center gap-1.5 font-bold text-rose-800">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>Nilai C (0% - Belum Terpenuhi)</span>
                            </div>
                            <p class="text-[11px] text-rose-900/80 leading-relaxed">
                                Belum dilaksanakan, tidak ada dokumen bukti pendukung, atau belum sesuai dengan pedoman etika yang berlaku.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Catatan & Temuan Terkini -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary-700">history_edu</span>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Ulasan & Temuan Terkini</h3>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        @forelse ($recentFindings as $finding)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="font-mono font-bold text-primary-700">
                                        {{ $finding->butir?->kode ?? 'Butir #' . $finding->butir_evaluasi_id }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $finding->updated_at?->diffForHumans() ?? 'Baru saja' }}
                                    </span>
                                </div>
                                @if ($finding->temuan)
                                    <p class="text-rose-700 text-[11px] font-medium leading-relaxed">
                                        <span class="font-bold">Temuan:</span> "{{ \Illuminate\Support\Str::limit($finding->temuan, 85) }}"
                                    </p>
                                @elseif ($finding->catatan)
                                    <p class="text-slate-600 text-[11px] leading-relaxed italic">
                                        "{{ \Illuminate\Support\Str::limit($finding->catatan, 85) }}"
                                    </p>
                                @endif
                                <div class="text-[10px] text-slate-400 truncate">
                                    {{ $finding->suratPengajuan->formulirAplikasi->nama_institusi ?? $finding->suratPengajuan->kepk->name }}
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-slate-400 italic text-xs">
                                Belum ada catatan atau temuan yang Anda tulis.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    {{-- ========================================================================= --}}
    {{-- 2. DASHBOARD KHUSUS ADMINISTRATOR (SUPER ADMIN)                           --}}
    {{-- ========================================================================= --}}
    @elseif ($user->isAdmin())
        <!-- Banner Admin -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
            <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>
            <div class="p-6 sm:p-7 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-700 border border-primary-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                        <span class="material-symbols-outlined text-[26px]">shield</span>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="bg-primary-50 text-primary-700 font-display text-[11px] px-2.5 py-0.5 rounded-md font-bold border border-primary-200/70">
                                Pusat Kontrol Administrator KEPK
                            </span>
                        </div>
                        <h1 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight">
                            Selamat Datang, {{ $user->name }}
                        </h1>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed max-w-2xl">
                            Pantau seluruh proses akreditasi KEPK, penugasan tim penilai independen, kelola instrumen butir evaluasi, dan sahkan hasil akreditasi secara terpusat.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition flex items-center gap-1.5 cursor-pointer" wire:navigate>
                        <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                        <span>Kelola Akun</span>
                    </a>
                    <a href="{{ route('admin.kriteria.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition flex items-center gap-1.5 cursor-pointer" wire:navigate>
                        <span class="material-symbols-outlined text-[16px]">rule</span>
                        <span>Kriteria & Acuan</span>
                    </a>
                    <a href="{{ route('pengajuan.index') }}" class="px-4 py-2.5 bg-primary-700 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer" wire:navigate>
                        <span class="material-symbols-outlined text-[16px]">folder_shared</span>
                        <span>Daftar Pengajuan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 6 Admin Metric KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Total Berkas</div>
                <div class="text-2xl font-extrabold text-slate-800 mt-1 font-display">{{ $totalAll }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Permohonan KEPK</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Proses Evaluasi</div>
                <div class="text-2xl font-extrabold text-blue-600 mt-1 font-display">{{ $inProgressAll }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Sedang dinilai/diisi</div>
            </div>

            <div class="bg-white border {{ $needAssign > 0 ? 'border-amber-300 bg-amber-50/40' : 'border-slate-200/80' }} rounded-2xl p-4 shadow-xs">
                <div class="text-[10px] text-amber-700 font-bold uppercase tracking-wider flex items-center gap-1">
                    <span>Belum Ada Asesor</span>
                    @if ($needAssign > 0)
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                    @endif
                </div>
                <div class="text-2xl font-extrabold text-amber-600 mt-1 font-display">{{ $needAssign }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Perlu ditugaskan</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider">Terakreditasi</div>
                <div class="text-2xl font-extrabold text-emerald-600 mt-1 font-display">{{ $approvedAll }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Resmi disahkan (ACC)</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[10px] text-slate-600 font-bold uppercase tracking-wider">Total KEPK</div>
                <div class="text-2xl font-extrabold text-primary-700 mt-1 font-display">{{ $totalKepk ?? 0 }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Komite terdaftar</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[10px] text-slate-600 font-bold uppercase tracking-wider">Asesor Aktif</div>
                <div class="text-2xl font-extrabold text-teal-600 mt-1 font-display">{{ $totalAsesor ?? 0 }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Tim penilai etik</div>
            </div>
        </div>

        <!-- Hasil Penilaian & Progress Akreditasi Komprehensif untuk Admin -->
        @if ($latestSubmission && $latestMetrics)
            <!-- Card Hasil Keputusan & Skor Akreditasi KEPK Terkini -->
            <div class="rounded-2xl border overflow-hidden shadow-xs {{ $latestSubmission->isApproved() ? 'bg-gradient-to-r from-emerald-900 via-teal-900 to-[#174668] text-white border-emerald-700' : ($latestSubmission->isRejected() ? 'bg-gradient-to-r from-rose-900 to-slate-900 text-white border-rose-800' : 'bg-white border-slate-200/90 text-slate-900') }}">
                <div class="p-6 sm:p-7 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="space-y-2.5 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $latestSubmission->isApproved() ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30' : ($latestSubmission->isRejected() ? 'bg-rose-500/20 text-rose-200 border border-rose-400/30' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                                <span class="material-symbols-outlined text-[16px]">{{ $latestSubmission->status_icon }}</span>
                                <span>{{ $latestSubmission->isApproved() ? 'HASIL KEPUTUSAN AKREDITASI RESMI (ACC)' : ($latestSubmission->isRejected() ? 'HASIL KEPUTUSAN: TIDAK LOLOS' : 'HASIL PENILAIAN & PROGRES AKREDITASI') }}</span>
                            </span>
                            <span class="font-mono text-xs {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'text-slate-300' : 'text-slate-500' }}">
                                Berkas: {{ $latestSubmission->formatted_id }} • {{ $latestSubmission->formulirAplikasi->nama_institusi ?? $latestSubmission->kepk->name }}
                            </span>
                        </div>

                        <div class="flex items-baseline gap-3 flex-wrap pt-1">
                            <h2 class="text-2xl sm:text-3xl font-black font-display {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'text-white' : 'text-slate-900' }}">
                                {{ $latestMetrics['prediction']['type'] }}
                            </h2>
                            <span class="text-xs px-3 py-1 rounded-lg font-extrabold font-mono {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'bg-white/20 text-white' : 'bg-primary-50 text-primary-700 border border-primary-200/80' }}">
                                Skor Kepatuhan: {{ $latestMetrics['overall_compliance'] }}%
                            </span>
                            <span class="text-xs font-mono {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'text-slate-200' : 'text-slate-500' }}">
                                ({{ $latestMetrics['total_answered'] }}/{{ $latestMetrics['total_items'] }} Butir Dinilai)
                            </span>
                        </div>

                        <p class="text-xs sm:text-sm leading-relaxed {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'text-slate-200' : 'text-slate-600' }} max-w-3xl">
                            {{ $latestMetrics['prediction']['description'] }}
                        </p>
                    </div>

                    <!-- Mini Stat Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 shrink-0">
                        <div class="p-3.5 rounded-xl text-center {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'bg-white/10 border border-white/10' : 'bg-slate-50 border border-slate-200/70' }}">
                            <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'text-emerald-300' : 'text-emerald-600' }}">{{ $latestMetrics['counts']['A'] }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-80 mt-0.5">Nilai A (100%)</div>
                        </div>

                        <div class="p-3.5 rounded-xl text-center {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'bg-white/10 border border-white/10' : 'bg-slate-50 border border-slate-200/70' }}">
                            <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'text-amber-300' : 'text-amber-500' }}">{{ $latestMetrics['counts']['B'] }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-80 mt-0.5">Nilai B (50%)</div>
                        </div>

                        <div class="p-3.5 rounded-xl text-center col-span-2 sm:col-span-1 {{ $latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'bg-white/10 border border-white/10' : 'bg-slate-50 border border-slate-200/70' }}">
                            <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $latestMetrics['counts']['C'] > 0 ? 'text-rose-400' : ($latestSubmission->isApproved() || $latestSubmission->isRejected() ? 'text-slate-300' : 'text-slate-600') }}">{{ $latestMetrics['counts']['C'] }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-80 mt-0.5">Nilai C (0%)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Columns: Syarat Kategori Akreditasi & Progress 5 Bagian A-E -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Left: Card Syarat Ambang Batas Kategori Akreditasi -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🎯</span>
                            <h3 class="text-sm font-bold text-slate-900">Kriteria Kategori Akreditasi</h3>
                        </div>
                    </div>

                    <div class="rounded-xl p-4 border {{ $latestMetrics['prediction']['badge_class'] }} space-y-2">
                        <div class="text-xs font-bold uppercase tracking-wider opacity-75">Status / Prediksi KEPK</div>
                        <div class="text-xl font-black font-display">{{ $latestMetrics['prediction']['type'] }}</div>
                        <p class="text-xs leading-relaxed opacity-90">
                            {{ $latestMetrics['prediction']['description'] }}
                        </p>
                    </div>

                    <div class="space-y-2 text-xs text-slate-600">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span class="font-medium">Ambang Batas Tipe A:</span>
                            <span class="font-bold font-mono text-emerald-700">Skor ≥80% & C = 0</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span class="font-medium">Ambang Batas Tipe B:</span>
                            <span class="font-bold font-mono text-blue-700">Skor ≥65% & C ≤ 5</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Ambang Batas Tipe C:</span>
                            <span class="font-bold font-mono text-amber-700">Skor ≥50%</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Progress 5 Bagian Standar A-E -->
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📊</span>
                            <h3 class="text-sm font-bold text-slate-900">Hasil Capaian per Komponen (Bagian A – E)</h3>
                        </div>
                        <a href="{{ route('pengajuan.show', $latestSubmission) }}" class="text-xs text-primary-700 font-bold hover:underline" wire:navigate>
                            Buka Detail Lengkap &rarr;
                        </a>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        @php
                            $defaultSections = [
                                'A' => ['nama' => 'Regulasi, Kelembagaan, dan Tata Kelola', 'total' => 29],
                                'B' => ['nama' => 'Keanggotaan dan Kompetensi', 'total' => 35],
                                'C' => ['nama' => 'Operasional dan Prosedur', 'total' => 74],
                                'D' => ['nama' => 'Fasilitas dan Sumber Daya', 'total' => 12],
                                'E' => ['nama' => 'Penelitian Khusus', 'total' => 14],
                            ];
                        @endphp

                        @foreach ($defaultSections as $secCode => $secMeta)
                            @php
                                $sData = $latestMetrics['sections'][$secCode] ?? [
                                    'nama' => $secMeta['nama'],
                                    'answered_items' => 0,
                                    'total_items' => $secMeta['total'],
                                    'compliance_percentage' => 0,
                                ];
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-slate-800">Bagian {{ $secCode }}: {{ $secMeta['nama'] }}</span>
                                    <span class="font-bold font-mono text-primary-700">{{ $sData['compliance_percentage'] }}% <span class="text-slate-400 font-normal">({{ $sData['answered_items'] }}/{{ $sData['total_items'] }})</span></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-primary-700 h-2 rounded-full transition-all duration-300" style="{{ 'width: ' . $sData['compliance_percentage'] . '%' }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ulasan Rekomendasi Asesor Penilai (Jika Ada) -->
            @if ($latestSubmission->penilaianEtik->isNotEmpty())
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs p-5 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📝</span>
                            <h3 class="text-sm font-bold text-slate-900">Ulasan & Catatan Rekomendasi Tim Asesor Penilai</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-mono">{{ $latestSubmission->penilaianEtik->count() }} Ulasan Masuk</span>
                    </div>

                    <div class="space-y-3">
                        @foreach ($latestSubmission->penilaianEtik as $penilaian)
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($penilaian->penilai->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $penilaian->penilai->name ?? 'Asesor' }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $penilaian->tanggal_keputusan ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold border {{ $penilaian->badge_rekomendasi }}">
                                            {{ $penilaian->label_rekomendasi }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-slate-700 leading-relaxed italic">
                                    "{{ $penilaian->catatan ?? 'Tidak ada catatan kesimpulan tambahan.' }}"
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Panel Tim Asesor & Menu Pintas Administrator -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card 1: Tim Asesor Terdaftar -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs p-5 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-700 text-[20px]">badge</span>
                        <h3 class="text-sm font-bold text-slate-900">Tim Asesor Penilai</h3>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-[11px] text-primary-700 font-bold hover:underline" wire:navigate>
                        Kelola &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse ($asesorList as $asesor)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($asesor->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $asesor->name }}</div>
                                    <div class="text-[11px] text-slate-400 truncate">{{ $asesor->email }}</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200 shrink-0">
                                Asesor
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-4">Belum ada akun asesor terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- Card 2: Akses Cepat Pengelolaan Sistem -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs p-5 space-y-3">
                <div class="text-xs font-bold text-slate-700 uppercase tracking-wider pb-1 border-b border-slate-100">
                    Menu Pintas Administrator
                </div>
                <div class="space-y-2 text-xs">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition font-semibold text-slate-800" wire:navigate>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary-700">group_add</span>
                            <span>Tambah / Kelola Akun</span>
                        </div>
                        <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
                    </a>
                    <a href="{{ route('admin.kriteria.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition font-semibold text-slate-800" wire:navigate>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary-700">tune</span>
                            <span>Kriteria & Acuan</span>
                        </div>
                        <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
                    </a>
                    <a href="{{ route('pengajuan.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition font-semibold text-slate-800" wire:navigate>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary-700">folder_shared</span>
                            <span>Daftar Seluruh Pengajuan KEPK</span>
                        </div>
                        <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

    {{-- ========================================================================= --}}
    {{-- 3. DASHBOARD KHUSUS KETUA & ANGGOTA KEPK                                  --}}
    {{-- ========================================================================= --}}
    @else
        <!-- 1. Top Notice / Info Banner -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
            <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>
            <div class="p-6 sm:p-7 relative z-10 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="bg-primary-50 text-primary-700 font-display text-[11px] px-3 py-1 rounded-lg font-bold border border-primary-200/70 shadow-2xs flex items-center gap-1.5">
                            <span class="text-sm">🌸</span>
                            <span>Sekar-Mu: Sistem Akreditasi KEPK</span>
                        </span>
                        @if ($suratPengajuan)
                            <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                        @endif
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
                    <div class="space-y-1 max-w-3xl">
                        <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight">
                            Hasil Akreditasi & Evaluasi Diri Standar WHO-CIOMS
                        </h1>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                            Selamat datang, <strong>{{ $user->name }}</strong>. Borang evaluasi diri dan pengunggahan berkas bukti dapat diisi bersama Ketua & Anggota KEPK secara <em>real-time</em>.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto shrink-0">
                        @if ($suratPengajuan)
                            <a
                                href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                                wire:navigate>
                                <span class="material-symbols-outlined text-[18px]">checklist</span>
                                <span>Evaluasi Diri</span>
                            </a>
                            <a
                                href="{{ route('pengajuan.dokumen', $suratPengajuan) }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition cursor-pointer"
                                wire:navigate>
                                <span class="material-symbols-outlined text-[18px]">folder_open</span>
                                <span>Berkas Dokumen</span>
                            </a>
                        @else
                            <a
                                href="{{ route('pengajuan.index', ['create' => 1]) }}"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                                wire:navigate>
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                <span>Buat Pengajuan Baru</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. CARD HASIL AKREDITASI RESMI & CAPAIAN NILAI -->
        @if ($suratPengajuan)
            <div class="rounded-2xl border overflow-hidden shadow-xs {{ $suratPengajuan->isApproved() ? 'bg-gradient-to-r from-emerald-900 via-teal-900 to-[#174668] text-white border-emerald-700' : ($suratPengajuan->isRejected() ? 'bg-gradient-to-r from-rose-900 to-slate-900 text-white border-rose-800' : 'bg-white border-slate-200/90 text-slate-900') }}">
                <div class="p-6 sm:p-7 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="space-y-2.5 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $suratPengajuan->isApproved() ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30' : ($suratPengajuan->isRejected() ? 'bg-rose-500/20 text-rose-200 border border-rose-400/30' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                                <span class="material-symbols-outlined text-[16px]">{{ $suratPengajuan->status_icon }}</span>
                                <span>{{ $suratPengajuan->isApproved() ? 'HASIL KEPUTUSAN AKREDITASI RESMI (ACC)' : ($suratPengajuan->isRejected() ? 'HASIL KEPUTUSAN: TIDAK LOLOS' : 'HASIL CAPAIAN AKREDITASI (REAL-TIME)') }}</span>
                            </span>
                            <span class="font-mono text-xs {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-slate-300' : 'text-slate-500' }}">
                                No. Berkas: {{ $suratPengajuan->formatted_id }}
                            </span>
                        </div>

                        <div class="flex items-baseline gap-3 flex-wrap pt-1">
                            <h2 class="text-2xl sm:text-3xl font-black font-display {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-white' : 'text-slate-900' }}">
                                {{ $metrics['prediction']['type'] }}
                            </h2>
                            <span class="text-xs px-3 py-1 rounded-lg font-extrabold font-mono {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/20 text-white' : 'bg-primary-50 text-primary-700 border border-primary-200/80' }}">
                                Skor Kepatuhan: {{ $metrics['overall_compliance'] }}%
                            </span>
                        </div>

                        <p class="text-xs sm:text-sm leading-relaxed {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-slate-200' : 'text-slate-600' }} max-w-3xl">
                            {{ $metrics['prediction']['description'] }}
                        </p>
                    </div>

                    <!-- Mini Stat Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 shrink-0">
                        <div class="p-3.5 rounded-xl text-center {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 border border-white/10' : 'bg-slate-50 border border-slate-200/70' }}">
                            <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-emerald-300' : 'text-emerald-600' }}">{{ $metrics['counts']['A'] }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-80 mt-0.5">Nilai A (100%)</div>
                        </div>

                        <div class="p-3.5 rounded-xl text-center {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 border border-white/10' : 'bg-slate-50 border border-slate-200/70' }}">
                            <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-amber-300' : 'text-amber-500' }}">{{ $metrics['counts']['B'] }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-80 mt-0.5">Nilai B (50%)</div>
                        </div>

                        <div class="p-3.5 rounded-xl text-center col-span-2 sm:col-span-1 {{ $suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'bg-white/10 border border-white/10' : 'bg-slate-50 border border-slate-200/70' }}">
                            <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $metrics['counts']['C'] > 0 ? 'text-rose-400' : ($suratPengajuan->isApproved() || $suratPengajuan->isRejected() ? 'text-slate-300' : 'text-slate-600') }}">{{ $metrics['counts']['C'] }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-80 mt-0.5">Nilai C (0%)</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- 3. Summary Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Tingkat Kepatuhan</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-primary-700 mt-1 font-display">{{ $metrics['overall_compliance'] }}%</div>
                <div class="text-[10px] text-slate-500 mt-0.5 font-mono">{{ $metrics['total_answered'] }}/{{ $metrics['total_items'] }} butir terisi</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-emerald-600 font-bold uppercase tracking-wider">Lengkap (Nilai A)</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1 font-display">{{ $metrics['counts']['A'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Memenuhi standar (100%)</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-amber-600 font-bold uppercase tracking-wider">Sebagian (Nilai B)</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-amber-500 mt-1 font-display">{{ $metrics['counts']['B'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Perlu penguatan bukti (50%)</div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <div class="text-[11px] text-slate-600 font-bold uppercase tracking-wider">Berkas Terlampir</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1 font-display">{{ $totalAttachedItems ?? 0 }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Butir memiliki dokumen bukti</div>
            </div>
        </div>

        <!-- 3.5 DASHBOARD ANALYTICS & VISUAL PROGRESS INSIGHTS -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-primary-700 to-teal-600 text-white flex items-center justify-center shadow-md shadow-primary-700/20">
                        <span class="material-symbols-outlined text-[22px]">analytics</span>
                    </div>
                    <div>
                        <h2 class="font-display text-base sm:text-lg font-extrabold text-slate-900 leading-tight">
                            Analitis Visual & Kesiapan Akreditasi Real-Time
                        </h2>
                        <p class="text-slate-500 text-xs mt-0.5">
                            Visualisasi sebaran skor 164 butir standar WHO-CIOMS & proyeksi kelulusan KEPK.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ $metrics['total_answered'] }} / {{ $metrics['total_items'] }} Butir Evaluasi</span>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <!-- Donut Progress Gauge (4 cols) -->
                <div class="lg:col-span-4 p-5 rounded-2xl bg-slate-50/80 border border-slate-100 text-center flex flex-col items-center justify-center space-y-3">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-200 stroke-current" stroke-width="3.5" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-primary-700 stroke-current transition-all duration-700" stroke-width="3.5" stroke-dasharray="{{ $metrics['overall_compliance'] }}, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-3xl font-black font-display text-slate-900 tracking-tight">{{ $metrics['overall_compliance'] }}%</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kepatuhan</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="font-bold text-slate-800 text-xs">Prediksi Status: <span class="text-primary-700 font-extrabold">{{ $metrics['prediction']['type'] }}</span></div>
                        <p class="text-[11px] text-slate-500 leading-snug">
                            {{ $metrics['overall_compliance'] >= 80 ? 'Target akreditasi KEPK terpenuhi dengan baik!' : 'Lengkapi sisa butir standar untuk meningkatkan kategori akreditasi.' }}
                        </p>
                    </div>
                </div>

                <!-- Distribution Stacked Bar & Breakdowns (8 cols) -->
                <div class="lg:col-span-8 space-y-5">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                            <span>Komposisi Sebaran Evaluasi</span>
                            <span class="font-mono text-slate-500">{{ $metrics['total_answered'] }} Terisi ({{ round(($metrics['total_answered']/max($metrics['total_items'], 1))*100) }}%)</span>
                        </div>

                        @php
                            $totalItems = max($metrics['total_items'], 1);
                            $pctA = round(($metrics['counts']['A'] / $totalItems) * 100, 1);
                            $pctB = round(($metrics['counts']['B'] / $totalItems) * 100, 1);
                            $pctC = round(($metrics['counts']['C'] / $totalItems) * 100, 1);
                            $unanswered = max(0, $totalItems - $metrics['total_answered']);
                            $pctUnanswered = round(($unanswered / $totalItems) * 100, 1);
                        @endphp

                        <div class="w-full h-4 rounded-xl bg-slate-100 overflow-hidden flex shadow-inner">
                            <div style="width: {{ $pctA }}%" class="bg-emerald-500 h-full transition-all duration-500" title="Nilai A (100%): {{ $metrics['counts']['A'] }} Butir"></div>
                            <div style="width: {{ $pctB }}%" class="bg-amber-500 h-full transition-all duration-500" title="Nilai B (50%): {{ $metrics['counts']['B'] }} Butir"></div>
                            <div style="width: {{ $pctC }}%" class="bg-rose-500 h-full transition-all duration-500" title="Nilai C (0%): {{ $metrics['counts']['C'] }} Butir"></div>
                            <div style="width: {{ $pctUnanswered }}%" class="bg-slate-200 h-full transition-all duration-500" title="Belum Diisi: {{ $unanswered }} Butir"></div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                            <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-200/70 text-emerald-800 text-[11px] flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                                <div>
                                    <div class="font-extrabold font-mono text-xs">{{ $metrics['counts']['A'] }} <span class="font-normal text-[10px]">({{ $pctA }}%)</span></div>
                                    <div class="text-[10px] opacity-80">Nilai A (Penuh)</div>
                                </div>
                            </div>

                            <div class="p-2 rounded-xl bg-amber-50 border border-amber-200/70 text-amber-800 text-[11px] flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0"></span>
                                <div>
                                    <div class="font-extrabold font-mono text-xs">{{ $metrics['counts']['B'] }} <span class="font-normal text-[10px]">({{ $pctB }}%)</span></div>
                                    <div class="text-[10px] opacity-80">Nilai B (Sebagian)</div>
                                </div>
                            </div>

                            <div class="p-2 rounded-xl bg-rose-50 border border-rose-200/70 text-rose-800 text-[11px] flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500 shrink-0"></span>
                                <div>
                                    <div class="font-extrabold font-mono text-xs">{{ $metrics['counts']['C'] }} <span class="font-normal text-[10px]">({{ $pctC }}%)</span></div>
                                    <div class="text-[10px] opacity-80">Nilai C (Kurang)</div>
                                </div>
                            </div>

                            <div class="p-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 text-[11px] flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-slate-300 shrink-0"></span>
                                <div>
                                    <div class="font-extrabold font-mono text-xs">{{ $unanswered }} <span class="font-normal text-[10px]">({{ $pctUnanswered }}%)</span></div>
                                    <div class="text-[10px] opacity-80">Belum Diisi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Ulasan & Rekomendasi Asesor Penilai (Jika Ada) -->
        @if ($suratPengajuan && $suratPengajuan->penilaianEtik->isNotEmpty())
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs p-5 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📝</span>
                        <h3 class="text-sm font-bold text-slate-900">Ulasan & Catatan Rekomendasi Tim Asesor</h3>
                    </div>
                    <span class="text-xs text-slate-400 font-mono">{{ $suratPengajuan->penilaianEtik->count() }} Ulasan Masuk</span>
                </div>

                <div class="space-y-3">
                    @foreach ($suratPengajuan->penilaianEtik as $penilaian)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($penilaian->penilai->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ $penilaian->penilai->name ?? 'Asesor' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $penilaian->tanggal_keputusan ?? '-' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold border {{ $penilaian->badge_rekomendasi }}">
                                        {{ $penilaian->label_rekomendasi }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-slate-700 leading-relaxed italic">
                                "{{ $penilaian->catatan ?? 'Tidak ada catatan kesimpulan tambahan.' }}"
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 5. Two Columns Grid: Syarat Kategori Akreditasi & Capaian 5 Bagian -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Left: Card Syarat Ambang Batas Kategori Akreditasi -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-base">🎯</span>
                        <h3 class="text-sm font-bold text-slate-900">Kriteria Kategori Akreditasi</h3>
                    </div>
                </div>

                <div class="rounded-xl p-4 border {{ $metrics['prediction']['badge_class'] }} space-y-2">
                    <div class="text-xs font-bold uppercase tracking-wider opacity-75">Hasil Prediksi / Status</div>
                    <div class="text-xl font-black font-display">{{ $metrics['prediction']['type'] }}</div>
                    <p class="text-xs leading-relaxed opacity-90">
                        {{ $metrics['prediction']['description'] }}
                    </p>
                </div>

                <div class="space-y-2 text-xs text-slate-600">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <span class="font-medium">Ambang Batas Tipe A:</span>
                        <span class="font-bold font-mono text-emerald-700">Skor ≥80% & C = 0</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <span class="font-medium">Ambang Batas Tipe B:</span>
                        <span class="font-bold font-mono text-blue-700">Skor ≥65% & C ≤ 5</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-medium">Ambang Batas Tipe C:</span>
                        <span class="font-bold font-mono text-amber-700">Skor ≥50%</span>
                    </div>
                </div>
            </div>

            <!-- Right: Progress 5 Bagian Standar A-E -->
            <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📊</span>
                        <h2 class="text-sm font-bold text-slate-900">Hasil Capaian per Komponen (Bagian A – E)</h2>
                    </div>
                    @if ($suratPengajuan)
                        <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline" wire:navigate>
                            Buka Seluruh Butir &rarr;
                        </a>
                    @endif
                </div>

                <div class="space-y-3.5 text-xs">
                    @php
                        $defaultSections = [
                            'A' => ['nama' => 'Regulasi, Kelembagaan, dan Tata Kelola', 'total' => 29],
                            'B' => ['nama' => 'Keanggotaan dan Kompetensi', 'total' => 35],
                            'C' => ['nama' => 'Operasional dan Prosedur', 'total' => 74],
                            'D' => ['nama' => 'Fasilitas dan Sumber Daya', 'total' => 12],
                            'E' => ['nama' => 'Penelitian Khusus', 'total' => 14],
                        ];
                    @endphp

                    @foreach ($defaultSections as $secCode => $secMeta)
                        @php
                            $sData = $metrics['sections'][$secCode] ?? [
                                'nama' => $secMeta['nama'],
                                'answered_items' => 0,
                                'total_items' => $secMeta['total'],
                                'compliance_percentage' => 0,
                            ];
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-800">Bagian {{ $secCode }}: {{ $secMeta['nama'] }}</span>
                                <span class="font-bold font-mono text-primary-700">{{ $sData['compliance_percentage'] }}% <span class="text-slate-400 font-normal">({{ $sData['answered_items'] }}/{{ $sData['total_items'] }})</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-primary-700 h-2 rounded-full transition-all duration-300" style="{{ 'width: ' . $sData['compliance_percentage'] . '%' }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @endif
</div>
