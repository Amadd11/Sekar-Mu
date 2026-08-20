<x-app-layout>
    @php
        $user = auth()->user();
    @endphp

    <div class="space-y-6 max-w-7xl mx-auto">
        {{-- ========================================================================= --}}
        {{-- 1. DASHBOARD KHUSUS REVIEWER / ASESOR AKREDITASI                          --}}
        {{-- ========================================================================= --}}
        @if ($user->isReviewer() && ! $user->isAdmin())
            @php
                $assignedSubmissions = $user->pengajuanDinilai()->with(['kepk.institusi', 'formulirAplikasi', 'penilaianEtik'])->get();
                $totalAssigned = $assignedSubmissions->count();
                $pendingReview = $assignedSubmissions->whereIn('status', ['submitted', 'under_review', 'resubmitted'])->count();
                $revisionRequired = $assignedSubmissions->where('status', 'revision_required')->count();
                $approvedCount = $assignedSubmissions->where('status', 'approved')->count();
            @endphp

            <!-- Banner Reviewer -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-900 text-white rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm border border-primary-800">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center text-xl shrink-0">
                        <span class="material-symbols-outlined text-[24px]">clinical_notes</span>
                    </div>
                    <div>
                        <h2 class="font-display text-base font-bold text-white">Portal Asesor Akreditasi KEPK</h2>
                        <p class="text-xs text-primary-100/90 mt-0.5 leading-relaxed">
                            Selamat datang, <strong>{{ $user->name }}</strong>. Anda ditugaskan untuk menelaah dokumen protokol dan penilaian independen 164 butir standar akreditasi KEPK.
                        </p>
                    </div>
                </div>
                <a href="{{ route('penilaian.index') }}" class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-900 bg-primary-300 hover:bg-primary-200 transition shadow-2xs" wire:navigate>
                    <span>Daftar Tugas Penilaian &rarr;</span>
                </a>
            </div>

            <!-- Reviewer Metric Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-score-card title="Total Tugas Menilai" :count="$totalAssigned" color="slate" />
                <x-score-card title="Perlu Ditelaah" :count="$pendingReview" color="amber" />
                <x-score-card title="Menunggu Revisi" :count="$revisionRequired" color="rose" />
                <x-score-card title="Telah Disetujui" :count="$approvedCount" color="emerald" />
            </div>

            <!-- Daftar Berkas Penugasan Terbaru -->
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📑</span>
                        <h3 class="text-sm font-bold text-slate-900">Berkas Pengajuan Yang Ditugaskan</h3>
                    </div>
                    <a href="{{ route('penilaian.index') }}" class="text-xs text-[#174668] font-bold hover:underline" wire:navigate>
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                            <tr>
                                <th class="px-5 py-3">No. Pengajuan</th>
                                <th class="px-5 py-3">Institusi / KEPK</th>
                                <th class="px-5 py-3">Status Permohonan</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($assignedSubmissions->take(5) as $item)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-3.5 font-mono font-bold text-[#174668]">
                                        #APP-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-slate-900">{{ $item->formulirAplikasi->nama_institusi ?? $item->kepk->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $item->kepk->institusi->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($item->status) }}">
                                            {{ \App\Models\SuratPengajuan::statusLabel($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('penilaian.show', $item) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#174668] hover:bg-[#133e5f] transition shadow-2xs" wire:navigate>
                                            <span>Buka Workspace &rarr;</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                        Belum ada permohonan pengajuan yang ditugaskan kepada Anda saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        {{-- ========================================================================= --}}
        {{-- 2. DASHBOARD KHUSUS ADMINISTRATOR (SUPER ADMIN)                           --}}
        {{-- ========================================================================= --}}
        @elseif ($user->isAdmin())
            @php
                $allSubmissions = \App\Models\SuratPengajuan::with(['kepk.institusi', 'formulirAplikasi', 'penilai', 'user'])->latest()->get();
                $totalAll = $allSubmissions->count();
                $needAssign = $allSubmissions->where('status', 'submitted')->where(fn($s) => $s->penilai->isEmpty())->count();
                $underReviewAll = $allSubmissions->whereIn('status', ['under_review', 'resubmitted'])->count();
                $approvedAll = $allSubmissions->where('status', 'approved')->count();
            @endphp

            <!-- Banner Admin -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-900 text-white rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm border border-primary-800">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center text-xl shrink-0">
                        <span class="material-symbols-outlined text-[24px]">shield</span>
                    </div>
                    <div>
                        <h2 class="font-display text-base font-bold text-white">Panel Administrator Akreditasi Sekar-Mu</h2>
                        <p class="text-xs text-primary-100/90 mt-0.5 leading-relaxed">
                            Pantau seluruh siklus akreditasi KEPK: penerimaan berkas, penugasan tim penilai etik, hingga penetapan status rekomendasi akhir.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('penilaian.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-900 bg-primary-300 hover:bg-primary-200 transition shadow-2xs" wire:navigate>
                        Portal Penilaian
                    </a>
                    <a href="{{ route('pengajuan.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition shadow-2xs" wire:navigate>
                        Daftar Pengajuan
                    </a>
                </div>
            </div>

            <!-- Admin Metric Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-score-card title="Total Semua Pengajuan" :count="$totalAll" color="slate" />
                <x-score-card title="Perlu Penugasan Penilai" :count="$needAssign" color="rose" />
                <x-score-card title="Sedang Dinilai" :count="$underReviewAll" color="amber" />
                <x-score-card title="Pengajuan Disetujui" :count="$approvedAll" color="emerald" />
            </div>

            <!-- Pengajuan Masuk Terbaru -->
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📋</span>
                        <h3 class="text-sm font-bold text-slate-900">Pengajuan Akreditasi Masuk</h3>
                    </div>
                    <a href="{{ route('pengajuan.index') }}" class="text-xs text-[#174668] font-bold hover:underline" wire:navigate>
                        Kelola Semua Pengajuan &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                            <tr>
                                <th class="px-5 py-3">ID & Tanggal</th>
                                <th class="px-5 py-3">Institusi / KEPK</th>
                                <th class="px-5 py-3">Pemohon</th>
                                <th class="px-5 py-3">Tim Penilai</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($allSubmissions->take(5) as $item)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-3.5">
                                        <div class="font-mono font-bold text-[#174668]">#APP-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $item->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-slate-900">{{ $item->formulirAplikasi->nama_institusi ?? $item->kepk->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $item->kepk->institusi->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-700">{{ $item->user->name ?? '-' }}</td>
                                    <td class="px-5 py-3.5">
                                        @if ($item->penilai->isNotEmpty())
                                            <span class="text-emerald-700 font-semibold">{{ $item->penilai->count() }} Penilai Ditugaskan</span>
                                        @else
                                            <span class="text-amber-600 font-medium">Belum ada penilai</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($item->status) }}">
                                            {{ \App\Models\SuratPengajuan::statusLabel($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right space-x-1">
                                        <a href="{{ route('penilaian.tugaskan', $item) }}" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition" wire:navigate>
                                            Tugaskan
                                        </a>
                                        <a href="{{ route('pengajuan.show', $item) }}" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold text-white bg-[#174668] hover:bg-[#133e5f] transition shadow-2xs" wire:navigate>
                                            Buka
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                        Belum ada permohonan pengajuan di dalam sistem.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        {{-- ========================================================================= --}}
        {{-- 3. DASHBOARD KHUSUS KETUA & ANGGOTA KEPK / PEMOHON                        --}}
        {{-- ========================================================================= --}}
        @else
            @php
                $suratPengajuan = \App\Models\SuratPengajuan::with(['kepk.institusi', 'formulirAplikasi', 'profilKepk', 'jawabanEvaluasi.butir.kelompok.bagian'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->first();

                if (!$suratPengajuan) {
                    $suratPengajuan = \App\Models\SuratPengajuan::with(['kepk.institusi', 'formulirAplikasi', 'profilKepk', 'jawabanEvaluasi.butir.kelompok.bagian'])
                        ->latest()
                        ->first();
                }

                $complianceService = app(\App\Services\ComplianceService::class);
                $metrics = $suratPengajuan
                    ? $complianceService->calculateComplianceMetrics($suratPengajuan)
                    : [
                        'overall_compliance' => 0,
                        'overall_completion' => 0,
                        'total_items' => 164,
                        'total_answered' => 0,
                        'counts' => ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'total' => 0],
                        'sections' => [],
                        'prediction' => $complianceService->classifyAccreditation(0, 0, 0),
                    ];

                $gapAnalysis = $suratPengajuan
                    ? $complianceService->calculateGapAnalysis($suratPengajuan)
                    : ['critical_findings_count' => 0, 'critical_findings' => [], 'top_improvements' => []];

                $kepkName = $suratPengajuan?->kepk?->name ?? 'Komisi Etik Penelitian Kesehatan UMY';
                $institutionName = $suratPengajuan?->kepk?->institusi?->name ?? 'Universitas Muhammadiyah Yogyakarta';
                $abbreviation = $suratPengajuan?->formulirAplikasi?->singkatan ?? 'KEPK-UMY';
                $city = $suratPengajuan?->formulirAplikasi?->kota ?? 'Yogyakarta';
            @endphp

            <!-- 1. Top Notice / Info Banner -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-900 text-white border border-primary-800 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <span class="text-3xl shrink-0 select-none">🌸</span>
                    <div>
                        <h2 class="font-display text-base font-bold text-white">Sekar-Mu — Sistem Evaluasi dan Akreditasi KEPK UMY</h2>
                        <p class="text-xs text-primary-100/90 mt-0.5 leading-relaxed">
                            Standar 164 Butir WHO-CIOMS & KEPPKN • Kelola evaluasi diri, unggah bukti dokumen, dan pantau status prediksi akreditasi secara real-time.
                        </p>
                    </div>
                </div>

                @if ($suratPengajuan)
                    <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 bg-primary-300 hover:bg-primary-200 text-slate-900 text-xs font-bold rounded-xl transition shadow-2xs" wire:navigate>
                        <span>Lanjutkan Pengisian &rarr;</span>
                    </a>
                @else
                    <a href="{{ route('pengajuan.index', ['create' => 1]) }}" class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 bg-primary-300 hover:bg-primary-200 text-slate-900 text-xs font-bold rounded-xl transition shadow-2xs" wire:navigate>
                        <span>Buat Pengajuan Baru &rarr;</span>
                    </a>
                @endif
            </div>

            <!-- 2. Summary Metric Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs">
                    <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Tingkat Kepatuhan</div>
                    <div class="text-2xl font-black text-[#174668] mt-1">{{ $metrics['overall_compliance'] }}%</div>
                    <div class="text-[10px] text-slate-500 mt-0.5">{{ $metrics['total_answered'] }}/{{ $metrics['total_items'] }} butir terisi</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs">
                    <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">A – Lengkap (100%)</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">{{ $metrics['counts']['A'] }}</div>
                    <div class="text-[10px] text-slate-500 mt-0.5">Memenuhi standar paripurna</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs">
                    <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">B – Sebagian (50%)</div>
                    <div class="text-2xl font-black text-amber-500 mt-1">{{ $metrics['counts']['B'] }}</div>
                    <div class="text-[10px] text-slate-500 mt-0.5">Perlu peningkatan bukti</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs">
                    <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Temuan Kritis (C)</div>
                    <div class="text-2xl font-black {{ $gapAnalysis['critical_findings_count'] > 0 ? 'text-rose-600' : 'text-slate-700' }} mt-1">
                        {{ $gapAnalysis['critical_findings_count'] }}
                    </div>
                    <div class="text-[10px] text-slate-500 mt-0.5">Total nilai C: {{ $metrics['counts']['C'] }}</div>
                </div>
            </div>

            <!-- 3. Two Columns Grid: Status Prediksi Akreditasi & Progress 5 Bagian -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Left: Card Status Prediksi Akreditasi (Compliance Engine) -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🎯</span>
                            <h3 class="text-sm font-bold text-slate-900">Prediksi Status Akreditasi</h3>
                        </div>
                    </div>

                    <div class="rounded-xl p-4 border {{ $metrics['prediction']['badge_class'] }} space-y-2">
                        <div class="text-xs font-bold uppercase tracking-wider opacity-75">Status Klasifikasi</div>
                        <div class="text-xl font-black">{{ $metrics['prediction']['type'] }}</div>
                        <p class="text-xs leading-relaxed opacity-90">
                            {{ $metrics['prediction']['description'] }}
                        </p>
                    </div>

                    <div class="space-y-2 text-xs text-slate-600">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span>Ambang Batas Tipe A:</span>
                            <span class="font-bold font-mono">Skor ≥80% & C = 0</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <span>Ambang Batas Tipe B:</span>
                            <span class="font-bold font-mono">Skor ≥65% & C ≤ 5</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Ambang Batas Tipe C:</span>
                            <span class="font-bold font-mono">Skor ≥50%</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Progress 5 Bagian Standar A-E -->
                <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📊</span>
                            <h2 class="text-sm font-bold text-slate-900">Kepatuhan per Komponen (Bagian A – E)</h2>
                        </div>
                        @if ($suratPengajuan)
                            <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="text-xs text-[#174668] font-bold hover:underline" wire:navigate>
                                Buka Seluruh 164 Butir &rarr;
                            </a>
                        @endif
                    </div>

                    <div class="space-y-3 text-xs">
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
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-800">Bagian {{ $secCode }} — {{ $secMeta['nama'] }}</span>
                                    <span class="font-bold font-mono text-[#174668]">{{ $sData['compliance_percentage'] }}% ({{ $sData['answered_items'] }}/{{ $sData['total_items'] }} item)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-[#174668] h-2 rounded-full transition-all duration-300" style="width: {{ $sData['compliance_percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 4. Top Improvement Opportunities (Gap Analysis) -->
            @if(count($gapAnalysis['top_improvements']) > 0)
                <div class="bg-white border border-slate-200 rounded-xl shadow-2xs p-5 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🚀</span>
                            <h3 class="text-sm font-bold text-slate-900">Peluang Perbaikan Prioritas (Gap Analysis)</h3>
                        </div>
                        <span class="text-xs text-slate-500 font-medium">Top 5 butir dengan dampak perbaikan tertinggi</span>
                    </div>

                    <div class="space-y-2">
                        @foreach(array_slice($gapAnalysis['top_improvements'], 0, 5) as $opp)
                            <div class="p-3 rounded-lg border border-slate-200/80 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold font-mono text-[#174668]">#{{ $opp['kode_bagian'] }}.{{ $opp['urutan'] }}</span>
                                        @if($opp['is_critical'])
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-100 text-rose-800">⚠️ KRITIS</span>
                                        @endif
                                        <span class="font-semibold text-slate-900">{{ $opp['pertanyaan'] }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500">Nilai saat ini: <strong>{{ $opp['current_score'] }}</strong> • Potensi kenaikan skor: <strong>+{{ $opp['potential_gain'] * 100 }}%</strong></div>
                                </div>
                                <div class="shrink-0">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $opp['priority'] === 'HIGH' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                        Prioritas: {{ $opp['priority'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
