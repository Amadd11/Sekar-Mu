<x-app-layout>
    @php
        $user = auth()->user();
        $application = \App\Models\Application::with(['kepk.institution', 'information', 'profile', 'answers'])->where('user_id', $user->id)->latest()->first();
        if (!$application && ($user->isAdmin() || $user->isReviewer())) {
            $application = \App\Models\Application::with(['kepk.institution', 'information', 'profile', 'answers'])->latest()->first();
        }

        $selfAssessmentService = app(\App\Services\SelfAssessmentService::class);
        $progress = $application ? $selfAssessmentService->calculateProgress($application) : [];
        $scoreSummary = $application ? $selfAssessmentService->calculateScoreSummary($application) : ['total' => 0, 'score_a' => 0, 'score_b' => 0, 'score_c' => 0];

        $kepkName = $application?->kepk?->name ?? 'Komisi Etik Penelitian Kesehatan UMY';
        $institutionName = $application?->kepk?->institution?->name ?? 'Universitas Muhammadiyah Yogyakarta';
        $abbreviation = $application?->information?->abbreviation ?? 'KEPK-UMY';
        $city = $application?->information?->city ?? 'Yogyakarta';
    @endphp

    <div class="space-y-5 max-w-7xl mx-auto">
        <!-- 1. Top Notice / Info Banner -->
        <div class="bg-[#e0f2fe] border border-[#bae6fd] text-[#0369a1] rounded-xl p-4 flex items-start gap-3 shadow-2xs">
            <span class="text-xl shrink-0 select-none">🌸</span>
            <div class="text-xs leading-relaxed text-[#0c4a6e]">
                <strong class="font-bold">Sekar-Mu – Sistem Evaluasi dan Akreditasi Komite Etik Penelitian Kesehatan Universitas Muhammadiyah Yogyakarta.</strong> Maskot: <em>Bunga Sekar 🌸</em>. Isi seluruh formulir dan evaluasi diri, lalu lihat <a href="{{ route('applications.index') }}" class="font-bold underline hover:text-[#0369a1]" wire:navigate>Laporan</a> untuk melihat status akreditasi.
            </div>
        </div>

        <!-- 2. Summary Metric Cards (4 Reusable Components) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-score-card title="Total Item Dinilai" :count="$scoreSummary['total']" color="slate" />
            <x-score-card title="A – Lengkap/Selalu" :count="$scoreSummary['score_a']" color="emerald" />
            <x-score-card title="B – Sebagian/Kadang" :count="$scoreSummary['score_b']" color="amber" />
            <x-score-card title="C – Tidak Lengkap" :count="$scoreSummary['score_c']" color="rose" />
        </div>

        <!-- 3. Progress Pengisian Evaluasi Diri -->
        <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-base">📝</span>
                    <h2 class="text-sm font-bold text-slate-900">Progress Pengisian Evaluasi Diri</h2>
                </div>
                @if ($application)
                    <a href="{{ route('applications.self-assessment', $application) }}" class="text-xs text-teal-700 font-semibold hover:underline" wire:navigate>
                        Buka Borang Evaluasi Diri &rarr;
                    </a>
                @endif
            </div>

            <div class="space-y-3.5 text-xs">
                @php
                    $sectionsMeta = [
                        'A' => ['name' => 'Bagian A — Struktur dan Komposisi KEP', 'default_total' => 20],
                        'B' => ['name' => 'Bagian B — Kepatuhan terhadap Kebijakan Khusus', 'default_total' => 35],
                        'C' => ['name' => 'Bagian C — Kelengkapan Proses Telaah', 'default_total' => 74],
                        'D' => ['name' => 'Bagian D — Setelah Proses Peninjauan', 'default_total' => 12],
                        'E' => ['name' => 'Bagian E — Dokumentasi dan Pengarsipan', 'default_total' => 14],
                    ];
                @endphp

                @foreach ($sectionsMeta as $code => $meta)
                    @php
                        $secData = $progress[$code] ?? ['answered' => 0, 'total' => $meta['default_total'], 'percentage' => 0];
                    @endphp
                    <x-section-progress-row
                        :name="$meta['name']"
                        :answered="$secData['answered']"
                        :total="$secData['total']"
                        :percentage="$secData['percentage']"
                    />
                @endforeach
            </div>
        </div>

        <!-- 4. Two Columns Grid: Identitas KEPK & Status Akreditasi Prediksi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Left Card: Identitas KEPK -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="text-base">ℹ️</span>
                    <h3 class="text-sm font-bold text-slate-900">Identitas KEPK</h3>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-500 font-medium">Nama KEPK</span>
                        <span class="col-span-2 text-slate-900 font-semibold">: {{ $kepkName }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-500 font-medium">Institusi</span>
                        <span class="col-span-2 text-slate-800">: {{ $institutionName }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-500 font-medium">Singkatan</span>
                        <span class="col-span-2 text-slate-800">: {{ $abbreviation }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-500 font-medium">Kota</span>
                        <span class="col-span-2 text-slate-800">: {{ $city }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-500 font-medium">Kategori Diajukan</span>
                        <span class="col-span-2 text-slate-800">: Tipe -</span>
                    </div>
                </div>
            </div>

            <!-- Right Card: Status Akreditasi Prediksi -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="text-base">🎯</span>
                    <h3 class="text-sm font-bold text-slate-900">Status Akreditasi Prediksi</h3>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center py-6 text-center text-slate-400">
                    @if ($scoreSummary['total'] > 0)
                        <div class="text-2xl font-black text-teal-700 mb-1">
                            {{ $scoreSummary['score_a'] >= 100 ? 'Akreditasi Utama (A)' : ($scoreSummary['score_a'] >= 50 ? 'Akreditasi Madya (B)' : 'Dalam Proses Evaluasi') }}
                        </div>
                        <p class="text-xs text-slate-500">
                            {{ $scoreSummary['total'] }} dari 155 butir telah dievaluasi.
                        </p>
                    @else
                        <div class="text-4xl mb-2 select-none">⏳</div>
                        <p class="text-xs font-medium text-slate-500">
                            Isi evaluasi diri untuk melihat prediksi
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- 5. Bottom Card: Panduan Pengisian -->
        <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <span class="text-base">📜</span>
                <span class="text-sm font-bold text-slate-900">Panduan Pengisian</span>
            </div>

            <div>
                @if ($application)
                    <a href="{{ route('applications.self-assessment', $application) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 transition shadow-2xs" wire:navigate>
                        <span>💾</span>
                        <span>Mulai Pengisian Evaluasi Diri</span>
                    </a>
                @else
                    <a href="{{ route('applications.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 transition shadow-2xs" wire:navigate>
                        <span>💾</span>
                        <span>Buat Pengajuan Baru</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
