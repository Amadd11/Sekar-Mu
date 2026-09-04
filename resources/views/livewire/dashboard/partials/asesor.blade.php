<!-- Banner Asesor -->
<div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
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
                                {!! 'style="width: ' . $prog['percentage'] . '%;"' !!}></div>
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
                        <span class="font-bold">Temuan:</span> "{{ Str::limit($finding->temuan, 85) }}"
                    </p>
                    @elseif ($finding->catatan)
                    <p class="text-slate-600 text-[11px] leading-relaxed italic">
                        "{{ Str::limit($finding->catatan, 85) }}"
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
