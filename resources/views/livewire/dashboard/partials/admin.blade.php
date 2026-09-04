<!-- Banner Admin -->
<div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
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
    <!-- Card Hasil Keputusan & Skor Akreditasi KEPK Terkini (Clean Minimalist) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
        <div class="p-6 sm:p-7 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2.5 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $latestSubmission->isApproved() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($latestSubmission->isRejected() ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                        <span class="material-symbols-outlined text-[16px]">{{ $latestSubmission->status_icon }}</span>
                        <span>{{ $latestSubmission->isApproved() ? 'HASIL KEPUTUSAN AKREDITASI RESMI (ACC)' : ($latestSubmission->isRejected() ? 'HASIL KEPUTUSAN: TIDAK LOLOS' : 'HASIL PENILAIAN & PROGRES AKREDITASI') }}</span>
                    </span>
                    <span class="font-mono text-xs text-slate-500">
                        Berkas: {{ $latestSubmission->formatted_id }} • {{ $latestSubmission->formulirAplikasi->nama_institusi ?? $latestSubmission->kepk->name }}
                    </span>
                </div>

                <div class="flex items-baseline gap-3 flex-wrap pt-1">
                    <h2 class="text-2xl sm:text-3xl font-black font-display text-slate-900">
                        {{ $latestMetrics['prediction']['type'] }}
                    </h2>
                    <span class="text-xs px-3 py-1 rounded-lg font-extrabold font-mono bg-primary-50 text-primary-700 border border-primary-200/80">
                        Skor Kepatuhan: {{ $latestMetrics['overall_compliance'] }}%
                    </span>
                    <span class="text-xs font-mono text-slate-500">
                        ({{ $latestMetrics['total_answered'] }}/{{ $latestMetrics['total_items'] }} Butir Dinilai)
                    </span>
                </div>

                <p class="text-xs sm:text-sm leading-relaxed text-slate-600 max-w-3xl">
                    {{ $latestMetrics['prediction']['description'] }}
                </p>
            </div>

            <!-- Mini Stat Badges -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 shrink-0">
                <div class="p-3.5 rounded-xl text-center bg-slate-50 border border-slate-200/70 min-w-[100px]">
                    <div class="text-xl sm:text-2xl font-extrabold font-mono text-emerald-600">{{ $latestMetrics['counts']['A'] }}</div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Nilai A (100%)</div>
                </div>

                <div class="p-3.5 rounded-xl text-center bg-slate-50 border border-slate-200/70 min-w-[100px]">
                    <div class="text-xl sm:text-2xl font-extrabold font-mono text-amber-500">{{ $latestMetrics['counts']['B'] }}</div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Nilai B (50%)</div>
                </div>

                <div class="p-3.5 rounded-xl text-center col-span-2 sm:col-span-1 bg-slate-50 border border-slate-200/70 min-w-[100px]">
                    <div class="text-xl sm:text-2xl font-extrabold font-mono {{ $latestMetrics['counts']['C'] > 0 ? 'text-rose-600' : 'text-slate-600' }}">{{ $latestMetrics['counts']['C'] }}</div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Nilai C (0%)</div>
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
