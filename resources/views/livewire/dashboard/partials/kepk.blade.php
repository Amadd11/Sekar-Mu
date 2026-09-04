<!-- 1. Top Notice / Info Banner -->
<div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
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

<!-- 2. CARD HASIL AKREDITASI RESMI & CAPAIAN NILAI (Clean Minimalist) -->
@if ($suratPengajuan)
<div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
    <div class="p-6 sm:p-7 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-2.5 flex-1">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $suratPengajuan->isApproved() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($suratPengajuan->isRejected() ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $suratPengajuan->status_icon }}</span>
                    <span>{{ $suratPengajuan->isApproved() ? 'HASIL KEPUTUSAN AKREDITASI RESMI (ACC)' : ($suratPengajuan->isRejected() ? 'HASIL KEPUTUSAN: TIDAK LOLOS' : 'HASIL CAPAIAN AKREDITASI (REAL-TIME)') }}</span>
                </span>
                <span class="font-mono text-xs text-slate-500">
                    No. Berkas: {{ $suratPengajuan->formatted_id }}
                </span>
            </div>

            <div class="flex items-baseline gap-3 flex-wrap pt-1">
                <h2 class="text-2xl sm:text-3xl font-black font-display text-slate-900">
                    {{ $metrics['prediction']['type'] }}
                </h2>
                <span class="text-xs px-3 py-1 rounded-lg font-extrabold font-mono bg-primary-50 text-primary-700 border border-primary-200/80">
                    Skor Kepatuhan: {{ $metrics['overall_compliance'] }}%
                </span>
            </div>

            <p class="text-xs sm:text-sm leading-relaxed text-slate-600 max-w-3xl">
                {{ $metrics['prediction']['description'] }}
            </p>
        </div>

        <!-- Mini Stat Badges -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 shrink-0">
            <div class="p-3.5 rounded-xl text-center bg-slate-50 border border-slate-200/70 min-w-[100px]">
                <div class="text-xl sm:text-2xl font-extrabold font-mono text-emerald-600">{{ $metrics['counts']['A'] }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Nilai A (100%)</div>
            </div>

            <div class="p-3.5 rounded-xl text-center bg-slate-50 border border-slate-200/70 min-w-[100px]">
                <div class="text-xl sm:text-2xl font-extrabold font-mono text-amber-500">{{ $metrics['counts']['B'] }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Nilai B (50%)</div>
            </div>

            <div class="p-3.5 rounded-xl text-center col-span-2 sm:col-span-1 bg-slate-50 border border-slate-200/70 min-w-[100px]">
                <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $metrics['counts']['C'] > 0 ? 'text-rose-600' : 'text-slate-600' }}">{{ $metrics['counts']['C'] }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Nilai C (0%)</div>
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