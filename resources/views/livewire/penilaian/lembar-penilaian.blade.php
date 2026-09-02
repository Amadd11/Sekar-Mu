<div>
    @if (! $isAssigned && ! auth()->user()->isAdmin())
    <div class="max-w-4xl mx-auto py-12 px-4 space-y-6">
        <div class="bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-xs text-center relative overflow-hidden space-y-6">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-amber-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center mx-auto shadow-2xs">
                <span class="material-symbols-outlined text-[42px]">lock_clock</span>
            </div>

            <div class="space-y-2 max-w-xl mx-auto">
                <div class="flex items-center justify-center gap-2">
                    <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200">
                        No. {{ $suratPengajuan->formatted_id }}
                    </span>
                </div>
                <h1 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug">
                    Akses Lembar Kerja Penilaian Belum Ditugaskan
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Anda belum ditugaskan oleh Administrator untuk menelaah berkas permohonan akreditasi
                    <strong class="text-slate-700 font-semibold">{{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'KEPK Pemohon' }}</strong>.
                </p>
            </div>

            <div class="p-5 sm:p-6 rounded-2xl bg-slate-50/80 border border-slate-200/70 max-w-lg mx-auto text-left space-y-3.5 shadow-2xs">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-800 border-b border-slate-200/60 pb-2.5">
                    <span class="material-symbols-outlined text-teal-600 text-[18px]">verified_user</span>
                    <span>Mengapa Halaman Ini Muncul?</span>
                </div>
                <ul class="text-xs text-slate-600 space-y-2.5 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-[16px] shrink-0 mt-0.5">check_circle</span>
                        <span>Berdasarkan standar etika KEPPKN & WHO, asesor hanya dapat menelaah berkas permohonan yang ditugaskan secara resmi oleh Administrator.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-[16px] shrink-0 mt-0.5">check_circle</span>
                        <span>Apabila Administrator telah menugaskan Anda pada berkas ini, tugas akan otomatis muncul di antrean <strong>Dashboard Asesor</strong>.</span>
                    </li>
                </ul>
            </div>

            <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 active:bg-primary-800 transition shadow-md shadow-primary-700/20 cursor-pointer"
                    wire:navigate>
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    <span>Kembali ke Dashboard Asesor</span>
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="space-y-6 max-w-7xl mx-auto pb-12">
        <!-- 1. Top Header Banner (Classic Sekar-Mu Design) -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
            <!-- Top Gradient Accent Bar -->
            <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>

            <div class="p-6 sm:p-7 relative z-10 space-y-4">
                <!-- Top Meta Strip -->
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="bg-primary-50 text-primary-700 font-mono text-xs px-3 py-1 rounded-lg font-bold border border-primary-200/70 shadow-2xs">
                            {{ $suratPengajuan->formatted_id }}
                        </span>
                        <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold border {{ $metrics['prediction']['badge_class'] }}">
                            {{ $metrics['prediction']['type'] }} ({{ $metrics['overall_compliance'] }}%)
                        </span>
                    </div>
                </div>

                <!-- Main Title & Action Buttons Row -->
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
                    <div class="space-y-1 max-w-3xl">
                        <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                            Workspace Asesor: {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'Borang Akreditasi KEPK' }}
                        </h1>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                            Pemohon: <strong class="text-slate-700">{{ $suratPengajuan->user->name }}</strong> • KEPK: {{ $suratPengajuan->kepk->name ?? '-' }} ({{ $suratPengajuan->kepk->institusi->name ?? '-' }})
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap w-full sm:w-auto shrink-0">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition shadow-2xs" wire:navigate>
                            &larr; Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2 font-semibold">
                <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                <span>{{ session('status') }}</span>
            </div>
        </div>
        @endif

        @if (session('action_status'))
        <div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2 font-semibold">
                <span class="material-symbols-outlined text-[18px] text-blue-600">info</span>
                <span>{{ session('action_status') }}</span>
            </div>
        </div>
        @endif

        <!-- 2. Four KPI Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Kepatuhan Total -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
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

            <!-- Card 2: Prediksi Akreditasi -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Prediksi Akreditasi</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl sm:text-2xl font-black text-emerald-700 font-display truncate">
                        {{ $metrics['prediction']['type'] }}
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Nilai C: {{ $metrics['counts']['C'] }} (Batas Tipe B: ≤5)
                    </p>
                </div>
            </div>

            <!-- Card 3: Nilai C (Kurang) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai C (Kurang)</span>
                    <div class="w-8 h-8 rounded-xl {{ $metrics['counts']['C'] > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black font-display {{ $metrics['counts']['C'] > 0 ? 'text-rose-600' : 'text-slate-700' }}">
                        {{ $metrics['counts']['C'] }}
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Butir bernilai 0%
                    </p>
                </div>
            </div>

            <!-- Card 4: Kemajuan Asesmen Asesor -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kemajuan Asesmen</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-black text-blue-700 font-display">
                        {{ $metrics['total_answered'] }}<span class="text-base text-slate-400 font-normal">/{{ $metrics['total_items'] }}</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                        {{ $metrics['completion_percentage'] }}% butir telah dinilai asesor
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Routed via Sidebar Navigation ($activeTab) -->
        @php
        $perItemAnswersWithFiles = $suratPengajuan->jawabanEvaluasi->filter(fn($j) => $j->hasAttachments());
        $totalItemFilesCount = $perItemAnswersWithFiles->sum(fn($j) => count($j->getAttachments()));
        $totalAllFiles = $suratPengajuan->dokumen->count() + $totalItemFilesCount;
        $allComments = $semuaPenilaian->flatMap->catatanPenilaian;
        @endphp

        <!-- 1. TAB PENILAIAN BUTIR STANDAR -->
        @if ($activeTab === 'penilaian')
        <!-- Section Selector Cards (Bagian A s.d. E) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            @foreach ($bagianList as $b)
            @php
            $isActive = $activeSection === $b->kode;
            $prog = $sectionProgress[$b->kode] ?? ['total' => 0, 'scored' => 0, 'pct' => 0];
            @endphp
            <button
                type="button"
                wire:click="switchSection('{{ $b->kode }}')"
                class="p-3.5 rounded-2xl border text-left transition-all relative cursor-pointer {{ $isActive ? 'bg-[#174668] text-white border-[#174668] font-bold shadow-md ring-2 ring-[#174668]/30' : 'bg-white text-slate-700 border-slate-200/80 hover:border-slate-300 hover:bg-slate-50' }}">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] uppercase font-bold tracking-wider {{ $isActive ? 'text-teal-200' : 'text-slate-500' }}">Bagian {{ $b->kode }}</span>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-md font-bold {{ $isActive ? 'bg-white/20 text-white' : ($prog['pct'] === 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600') }}">
                        {{ $prog['scored'] }}/{{ $prog['total'] }}
                    </span>
                </div>
                <div class="text-xs font-bold truncate mt-1.5 {{ $isActive ? 'text-white' : 'text-slate-900' }}">{{ $b->nama }}</div>
                <div class="w-full bg-slate-200/40 rounded-full h-1.5 mt-2.5 overflow-hidden">
                    <div class="h-full {{ $isActive ? 'bg-teal-300' : 'bg-emerald-500' }}" style="{{ 'width: ' . $prog['pct'] . '%' }}"></div>
                </div>
            </button>
            @endforeach
        </div>

        <!-- Table of Items in Active Section -->
        @if ($activeBagian)
        <div class="space-y-6">
            @foreach ($activeBagian->kelompok as $kIdx => $kelompok)
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                <!-- Group Header Accent -->
                <div class="bg-[#174668] text-white px-5 py-3.5 flex items-center justify-between">
                    <div class="font-bold text-xs sm:text-sm font-display tracking-wide">
                        {{ $activeBagian->kode }}{{ $kIdx + 1 }} – {{ $kelompok->nama }}
                    </div>
                    <div class="text-[11px] font-mono text-teal-200 font-semibold bg-white/10 px-2.5 py-0.5 rounded-lg">
                        {{ $kelompok->butir->count() }} Butir Kriteria
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4 w-16 text-center">Kode</th>
                                <th class="py-3 px-4 min-w-[300px]">Kriteria & Berkas dari KEPK</th>
                                <th class="py-3 px-4 w-72 text-center">Nilai & Bobot Bukti Asesor</th>
                                <th class="py-3 px-4 min-w-[240px]">Temuan & Catatan Perbaikan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($kelompok->butir as $bIdx => $butir)
                            @php
                            $selfAns = $suratPengajuan->jawabanEvaluasi->firstWhere('butir_evaluasi_id', $butir->id);
                            $selSkor = $itemSkor[$butir->id] ?? '';
                            $selStrength = $evidenceStrength[$butir->id] ?? '';
                            $kodeItem = $butir->kode ?? ($activeBagian->kode . ($kIdx + 1) . '.' . ($bIdx + 1));
                            $hasData = !empty($selfAns?->bukti) || !empty($selfAns?->catatan) || !empty($selfAns?->file_path);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition {{ $selSkor ? 'bg-emerald-50/15' : '' }}">
                                <!-- Col 1: Kode -->
                                <td class="py-4 px-4 text-center align-top font-bold text-primary-700 text-xs font-mono">
                                    {{ $kodeItem }}
                                </td>

                                <!-- Col 2: Kriteria & Berkas KEPK -->
                                <td class="py-4 px-4 align-top space-y-2.5">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-[10px] font-mono text-slate-500 font-semibold bg-slate-100 px-2 py-0.5 rounded">{{ $butir->standar ?? 'Standar Akreditasi' }}</span>
                                    </div>
                                    <div class="font-semibold text-slate-900 text-xs leading-relaxed">
                                        {{ $butir->pertanyaan }}
                                    </div>

                                    @php
                                    $itemAttachments = $selfAns ? $selfAns->getAttachments() : [];
                                    $attCount = count($itemAttachments);
                                    @endphp
                                    <!-- KEPK Evidence & Notes Card -->
                                    <div class="p-3 rounded-xl border text-xs space-y-2 {{ $attCount >= 2 ? 'bg-emerald-50/40 border-emerald-200' : ($attCount === 1 ? 'bg-amber-50/40 border-amber-200' : 'bg-slate-50 border-slate-200/80') }}">
                                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-1.5">
                                            <span class="font-bold text-[10px] uppercase text-[#174668] tracking-wider">Bukti Dukung KEPK:</span>
                                            @if($attCount >= 2)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <span class="material-symbols-outlined text-[12px]">check_circle</span> Lengkap ({{ $attCount }} Berkas)
                                            </span>
                                            @elseif($attCount === 1)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                <span class="material-symbols-outlined text-[12px]">hourglass_top</span> Belum Lengkap (1 Berkas)
                                            </span>
                                            @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-200 text-slate-600">
                                                Belum Ada Berkas
                                            </span>
                                            @endif
                                        </div>

                                        @if($selfAns?->bukti)
                                        <div class="text-slate-800 font-medium text-[11px]">
                                            <span class="text-slate-500 font-normal">📄 Bukti / SK:</span> {{ $selfAns->bukti }}
                                        </div>
                                        @endif

                                        @if(count($itemAttachments) > 0)
                                        <div class="space-y-1.5">
                                            @foreach($itemAttachments as $att)
                                            <div class="p-2 bg-white rounded-lg border border-emerald-200 flex items-center justify-between gap-2 shadow-2xs">
                                                <div class="font-semibold text-emerald-950 text-[11px] truncate flex items-center gap-1.5 overflow-hidden" title="{{ $att['name'] }}">
                                                    <span class="material-symbols-outlined text-emerald-600 text-[16px]">attachment</span>
                                                    <span class="truncate">{{ $att['name'] }}</span>
                                                    <span class="text-[10px] text-slate-400 font-mono font-normal">({{ format_bytes((int) ($att['size'] ?? 0)) }})</span>
                                                </div>
                                                <a
                                                    href="{{ Storage::url($att['path']) }}"
                                                    target="_blank"
                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] rounded-lg shrink-0 transition shadow-2xs flex items-center gap-1">
                                                    <span>Unduh</span>
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif

                                        @if($selfAns?->catatan)
                                        <div class="text-slate-700 bg-white p-2.5 rounded-lg border border-slate-200/60 leading-relaxed text-[11px]">
                                            <span class="text-slate-500 font-semibold block mb-0.5">💬 Uraian Pemohon:</span>
                                            {{ $selfAns->catatan }}
                                        </div>
                                        @endif

                                        @if(!$hasData)
                                        <div class="text-slate-400 italic text-[11px]">Pemohon KEPK belum mengisi nama bukti/uraian pada butir ini.</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Col 3: NILAI ASESOR (A/B/C/D) & BOBOT BUKTI (E0..E4) -->
                                <td class="py-4 px-4 align-top space-y-3">
                                    <!-- 1. Skor A / B / C / D -->
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5 text-center tracking-wider">Skor Asesor:</span>
                                        <div class="flex items-center justify-center gap-1.5">
                                            @foreach([
                                            'A' => ['bg' => 'bg-emerald-600', 'title' => 'A: Lengkap / 100%'],
                                            'B' => ['bg' => 'bg-amber-500', 'title' => 'B: Sebagian / 50%'],
                                            'C' => ['bg' => 'bg-rose-600', 'title' => 'C: Tidak Ada / 0%'],
                                            'D' => ['bg' => 'bg-[#174668]', 'title' => 'D: Tidak Dapat Dinilai'],
                                            ] as $opt => $optMeta)
                                            <button
                                                type="button"
                                                wire:click="setItemSkor({{ $butir->id }}, '{{ $opt }}')"
                                                title="{{ $optMeta['title'] }}"
                                                class="flex-1 py-2 rounded-xl border text-center font-bold text-xs transition-all cursor-pointer {{ $selSkor === $opt ? "{$optMeta['bg']} text-white border-transparent shadow-xs ring-2 ring-offset-1 ring-slate-400" : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100' }}">
                                                {{ $opt }}
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- 2. Evidence Strength E0..E4 -->
                                    <div class="pt-2 border-t border-slate-100">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5 text-center tracking-wider">Bobot Bukti:</span>
                                        <div class="grid grid-cols-5 gap-1">
                                            @foreach([
                                            'E0' => 'E0: Tanpa Bukti',
                                            'E1' => 'E1: Kurang Lengkap',
                                            'E2' => 'E2: Dokumen Lengkap',
                                            'E3' => 'E3: Dok + Implementasi',
                                            'E4' => 'E4: Dok + Evaluasi',
                                            ] as $eCode => $eDesc)
                                            <button
                                                type="button"
                                                wire:click="setStrength({{ $butir->id }}, '{{ $eCode }}')"
                                                title="{{ $eDesc }}"
                                                class="py-1 rounded-lg text-center font-mono font-bold text-[10px] transition-all cursor-pointer {{ $selStrength === $eCode ? 'bg-[#174668] text-white border border-[#174668] shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200' }}">
                                                {{ $eCode }}
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>

                                <!-- Col 4: TEMUAN & CATATAN PERBAIKAN -->
                                <td class="py-4 px-4 align-top space-y-2">
                                    <div>
                                        <input
                                            type="text"
                                            wire:model.blur="itemTemuan.{{ $butir->id }}"
                                            placeholder="Temuan ketidaksesuaian..."
                                            class="w-full text-xs rounded-xl border-slate-300 px-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-2 focus:ring-[#174668]/15 shadow-2xs" />
                                    </div>
                                    <div>
                                        <textarea
                                            wire:model.blur="itemCatatan.{{ $butir->id }}"
                                            rows="2"
                                            placeholder="Catatan / rekomendasi perbaikan asesor..."
                                            class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-2 focus:ring-[#174668]/15 shadow-2xs resize-y"></textarea>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

            <!-- Bottom Section Navigation Buttons -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                <button
                    type="button"
                    wire:click="previousSection"
                    class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition shadow-2xs cursor-pointer">
                    &larr; Bagian Sebelumnya
                </button>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        wire:click="switchTab('rekomendasi')"
                        class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-200 font-bold rounded-xl text-xs transition shadow-2xs cursor-pointer flex items-center gap-1.5">
                        <span>Ke Form Rekomendasi</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                    <button
                        type="button"
                        wire:click="nextSection"
                        class="px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold rounded-xl text-xs transition shadow-md shadow-primary-700/20 cursor-pointer">
                        Bagian Selanjutnya &rarr;
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- 2. TAB DOKUMEN (BERKAS BUKTI & PROTOKOL PENELITIAN) -->
        @elseif ($activeTab === 'dokumen')
        <div class="space-y-6">
            <!-- 1. Daftar Protokol Riset -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-display">1. Daftar Protokol Penelitian ({{ $suratPengajuan->listProtokol->count() }})</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar usulan protokol riset yang diajukan dan ditelaah oleh KEPK.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-6 py-4">No. Protokol</th>
                                <th class="px-6 py-4">Judul Penelitian</th>
                                <th class="px-6 py-4">Jalur Telaah</th>
                                <th class="px-6 py-4">Peneliti Utama</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($suratPengajuan->listProtokol as $prot)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 font-mono font-bold text-primary-700">{{ $prot->nomor_protokol }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900 leading-relaxed">{{ $prot->judul }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ str_replace('_', ' ', $prot->review_type ?? 'full_board') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-700 font-medium">{{ $prot->peneliti_utama }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                    <span class="text-3xl block mb-2">📑</span>
                                    Tidak ada protokol riset terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Bukti Terunggah per Butir Evaluasi (Dikelompokkan per Bagian) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-1">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-display">Berkas Bukti Dukung Evaluasi Diri ({{ $totalItemFilesCount }} Berkas)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Lampiran dokumen bukti pemenuhan instrumen yang diunggah pemohon KEPK dikelompokkan per bagian.</p>
                    </div>
                </div>

                @foreach($bagianList as $bg)
                @php
                $bgItemsWithFiles = $perItemAnswersWithFiles->filter(function($ans) use ($bg) {
                return $ans->butir && $ans->butir->bagian_evaluasi_id === $bg->id;
                });
                $bgFilesCount = $bgItemsWithFiles->sum(function($ans) {
                return count($ans->getAttachments());
                });
                @endphp

                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-[#174668] text-white font-mono font-bold text-[11px] flex items-center justify-center">
                                {{ $bg->kode }}
                            </span>
                            <h4 class="text-xs font-bold text-slate-800">
                                Bagian {{ $bg->kode }}: {{ $bg->nama }}
                            </h4>
                        </div>
                        <span class="font-mono text-[11px] px-2.5 py-0.5 rounded-md font-semibold border {{ $bgFilesCount > 0 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                            {{ $bgFilesCount }} Berkas
                        </span>
                    </div>

                    @if($bgFilesCount > 0)
                    <div class="divide-y divide-slate-100 text-xs">
                        @foreach($bgItemsWithFiles as $itemAnswer)
                        @foreach($itemAnswer->getAttachments() as $att)
                        @php
                        $kodeButir = $itemAnswer->butir?->kode ?? ('#' . $itemAnswer->butir_evaluasi_id);
                        @endphp
                        <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/70 transition">
                            <div class="space-y-1">
                                <div class="font-semibold text-slate-900 flex items-center gap-2 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                        Butir {{ $kodeButir }}
                                    </span>
                                    <span class="font-bold text-slate-800">{{ $att['name'] }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                    <span class="font-mono">{{ format_bytes((int) ($att['size'] ?? 0)) }}</span>
                                    @if($itemAnswer->bukti)
                                    <span>•</span>
                                    <span>No. Bukti / SK: <strong class="text-slate-700">{{ $itemAnswer->bukti }}</strong></span>
                                    @endif
                                </div>
                                @if($itemAnswer->catatan)
                                <div class="text-[11px] text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-200/60 mt-1">
                                    <span class="text-slate-400 font-medium">Uraian KEPK:</span> {{ $itemAnswer->catatan }}
                                </div>
                                @endif
                            </div>
                            <a
                                href="{{ Storage::url($att['path']) }}"
                                target="_blank"
                                class="px-3.5 py-1.5 bg-[#174668] hover:bg-[#133e5f] text-white font-bold rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5 shrink-0 self-start sm:self-center cursor-pointer">
                                <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                                <span>Buka Berkas</span>
                            </a>
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                    @else
                    <div class="px-5 py-6 text-center text-slate-400 text-xs">
                        Belum ada berkas bukti dukung untuk Bagian {{ $bg->kode }}.
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- 3. TAB FORM REKOMENDASI (KEPUTUSAN, CATATAN, & RINGKASAN) -->
        @elseif ($activeTab === 'rekomendasi')
        <div class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left 1 Col: Summary Metrics & Status Overview -->
                <div class="space-y-5">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 font-display pb-2 border-b border-slate-100">
                            Ringkasan Hasil Evaluasi
                        </h3>
                        <div class="space-y-3 text-xs">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-600 font-medium">Tingkat Kepatuhan:</span>
                                <span class="font-bold text-primary-800 text-sm">{{ $metrics['overall_compliance'] }}%</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-600 font-medium">Prediksi Tipe:</span>
                                <span class="font-bold text-emerald-800">{{ $metrics['prediction']['type'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl {{ $metrics['counts']['C'] > 0 ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-100' }}">
                                <span class="{{ $metrics['counts']['C'] > 0 ? 'text-rose-900 font-semibold' : 'text-slate-600 font-medium' }}">Nilai C (Kurang):</span>
                                <span class="font-bold {{ $metrics['counts']['C'] > 0 ? 'text-rose-700' : 'text-slate-700' }}">
                                    {{ $metrics['counts']['C'] }} Butir
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 font-mono text-[11px]">
                                <span class="text-slate-600">Distribusi Skor:</span>
                                <span>A:<strong>{{ $metrics['counts']['A'] }}</strong> | B:<strong>{{ $metrics['counts']['B'] }}</strong> | C:<strong>{{ $metrics['counts']['C'] }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button Link to Borang -->
                    <button
                        type="button"
                        wire:click="switchTab('penilaian')"
                        class="w-full py-3 px-4 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-2xl text-xs transition shadow-2xs flex items-center justify-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">edit_note</span>
                        <span>Kembali Cek Borang Penilaian</span>
                    </button>
                </div>

                <!-- Right 2 Cols: Form Rekomendasi Penilai -->
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-base font-bold text-slate-900 font-display">Form Rekomendasi Penilai</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Berikan keputusan kelayakan etik resmi terhadap permohonan akreditasi KEPK ini.</p>
                    </div>

                    <form wire:submit="simpanPenilaian" class="space-y-5 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-2.5">
                                Keputusan Rekomendasi Akhir <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $rekomendasi === 'approved' ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold ring-2 ring-emerald-500/20 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input
                                        type="radio"
                                        wire:model="rekomendasi"
                                        value="approved"
                                        class="mt-1 text-emerald-600 focus:ring-emerald-500 cursor-pointer" />
                                    <div>
                                        <div class="font-bold text-xs sm:text-sm">Disetujui (Layak Etik)</div>
                                        <div class="text-[11px] text-emerald-700 font-normal mt-0.5">Memenuhi seluruh standar baku etik WHO-CIOMS & KNEPK.</div>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $rekomendasi === 'revision_required' ? 'bg-amber-50 border-amber-300 text-amber-950 font-bold ring-2 ring-amber-500/20 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input
                                        type="radio"
                                        wire:model="rekomendasi"
                                        value="revision_required"
                                        class="mt-1 text-amber-600 focus:ring-amber-500 cursor-pointer" />
                                    <div>
                                        <div class="font-bold text-xs sm:text-sm">Perlu Perbaikan / Tindakan Korektif (CAPA)</div>
                                        <div class="text-[11px] text-amber-700 font-normal mt-0.5">Membutuhkan kelengkapan bukti pendukung atau revisi SOP regulasi.</div>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $rekomendasi === 'rejected' ? 'bg-rose-50 border-rose-300 text-rose-950 font-bold ring-2 ring-rose-500/20 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input
                                        type="radio"
                                        wire:model="rekomendasi"
                                        value="rejected"
                                        class="mt-1 text-rose-600 focus:ring-rose-500 cursor-pointer" />
                                    <div>
                                        <div class="font-bold text-xs sm:text-sm">Ditolak (Tidak Layak Etik)</div>
                                        <div class="text-[11px] text-rose-700 font-normal mt-0.5">Terdapat ketidakpatuhan atau pelanggaran etik substansial.</div>
                                    </div>
                                </label>
                            </div>
                            @error('rekomendasi')
                            <span class="text-red-500 text-[11px] block mt-1.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan" class="block font-bold text-slate-700 mb-1.5">
                                Kesimpulan / Catatan Akhir Penilai:
                            </label>
                            <textarea
                                wire:model="catatan"
                                id="catatan"
                                rows="5"
                                placeholder="Tuliskan ringkasan pertimbangan keputusan kelayakan etik dan catatan resmi untuk KEPK..."
                                class="w-full text-xs rounded-xl border border-slate-300 p-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs leading-relaxed"></textarea>
                            @error('catatan')
                            <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full py-3.5 px-5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="simpanPenilaian" class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px]">save</span>
                                    <span>Simpan Keputusan Rekomendasi</span>
                                </span>
                                <span wire:loading wire:target="simpanPenilaian">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Catatan & Permintaan Perbaikan (Review Thread) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900 font-display">Catatan & Permintaan Perbaikan (Review Thread)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Kanal diskusi dan catatan revisi resmi antara Tim Asesor dan Pemohon KEPK.</p>
                </div>

                @if (session('comment_status'))
                <div class="bg-emerald-50 text-emerald-800 text-xs p-3.5 rounded-xl border border-emerald-200 font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                    <span>{{ session('comment_status') }}</span>
                </div>
                @endif

                <div class="space-y-3">
                    @forelse ($allComments as $c)
                    <div class="p-4 rounded-xl border {{ $c->selesai ? 'bg-slate-50 border-slate-200 text-slate-500' : 'bg-amber-50/50 border-amber-200 text-slate-800' }} text-xs space-y-2 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900">{{ $c->user->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $c->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <button
                                type="button"
                                wire:click="toggleSelesai({{ $c->id }})"
                                class="text-[11px] font-bold px-3 py-1 rounded-lg border transition-all cursor-pointer {{ $c->selesai ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-100' }}">
                                {{ $c->selesai ? '✓ Selesai Diperbaiki' : 'Tandai Selesai' }}
                            </button>
                        </div>
                        <p class="leading-relaxed text-slate-700">{{ $c->catatan }}</p>
                    </div>
                    @empty
                    <div class="py-8 text-center text-slate-400 text-xs">
                        <span class="text-3xl block mb-2">💬</span>
                        Belum ada catatan atau permintaan perbaikan yang dikirim.
                    </div>
                    @endforelse
                </div>

                <!-- Add Comment Form -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <label for="catatanBaru" class="block text-xs font-bold text-slate-700">
                        Tulis Catatan / Permintaan Perbaikan Baru:
                    </label>
                    <textarea
                        wire:model="catatanBaru"
                        id="catatanBaru"
                        rows="3"
                        placeholder="Tuliskan catatan telaah atau aspek instrumen yang memerlukan perbaikan pemohon..."
                        class="w-full text-xs rounded-xl border border-slate-300 p-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs"></textarea>
                    @error('catatanBaru')
                    <span class="text-red-500 text-[11px] block">{{ $message }}</span>
                    @enderror
                    <div class="flex justify-end">
                        <button
                            type="button"
                            wire:click="kirimCatatan"
                            class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">send</span>
                            <span>Kirim Catatan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>