<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Header Card (app.css primary theme) -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xs border border-slate-200/80 relative overflow-hidden">
        <!-- Decorative subtle radial glow -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start gap-6">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2.5 mb-3">
                    <span class="bg-slate-100 text-slate-700 font-mono text-xs px-3 py-1 rounded-md font-bold border border-slate-200">
                        #APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold border whitespace-nowrap shrink-0 {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                        <span class="material-symbols-outlined text-[14px]">{{ \App\Models\SuratPengajuan::statusIcon($suratPengajuan->status) }}</span>
                        <span>{{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}</span>
                    </span>
                </div>
                <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                    B01-03: Evaluasi Diri (164 Butir)
                </h1>
                <p class="text-slate-500 text-xs sm:text-sm max-w-2xl mt-1.5 leading-relaxed">
                    Asesmen mandiri berbasis standar WHO-CIOMS dan Komite Nasional Etik Penelitian Kesehatan.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Auto-save Live Indicator -->
                <div wire:loading.remove wire:target="setSkor,simpanCatatan" class="flex items-center gap-2 text-emerald-700 bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-200/80 w-full sm:w-auto justify-center shadow-2xs">
                    <span class="material-symbols-outlined text-[18px]">cloud_done</span>
                    <span class="text-xs font-bold">Tersimpan</span>
                </div>

                <div wire:loading wire:target="setSkor,simpanCatatan" class="flex items-center gap-2 text-primary-700 bg-primary-50 px-4 py-2.5 rounded-xl border border-primary-200/80 w-full sm:w-auto justify-center shadow-2xs">
                    <svg class="animate-spin h-4 w-4 text-primary-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Menyimpan...</span>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a
                        href="{{ route('pengajuan.pdf.evaluasi-diri', $suratPengajuan) }}"
                        target="_blank"
                        class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex-1 sm:flex-none flex items-center justify-center gap-1.5"
                    >
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                        <span>Unduh</span>
                    </a>

                    <button
                        type="button"
                        onclick="window.print()"
                        class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition shadow-xs flex items-center justify-center"
                        title="Cetak Halaman"
                    >
                        <span class="material-symbols-outlined text-[18px]">print</span>
                    </button>

                    <a
                        href="{{ route('pengajuan.show', $suratPengajuan) }}"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition shadow-xs flex items-center justify-center"
                        wire:navigate
                    >
                        &larr; Detail
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Section Progress Cards Grid (Bagian A s/d E) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach ($bagianList as $b)
            @php
                $bProg = $progress[$b->kode] ?? ['terjawab' => 0, 'total' => 0, 'persentase' => 0];
                $isActive = $activeSection === $b->kode;
                $isComplete = $bProg['total'] > 0 && $bProg['terjawab'] === $bProg['total'];
            @endphp

            @if ($isActive)
                <!-- Active Section Card -->
                <div
                    wire:click="switchSection('{{ $b->kode }}')"
                    class="bg-primary-700 text-white rounded-2xl p-5 shadow-lg shadow-primary-700/20 ring-2 ring-primary-700 ring-offset-2 ring-offset-slate-100 relative overflow-hidden group cursor-pointer flex flex-col justify-between"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-white/15 to-transparent pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-2.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-primary-200">Bagian {{ $b->kode }}</span>
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-xs font-mono text-xs font-black">
                                {{ $bProg['persentase'] }}%
                            </div>
                        </div>
                        <h3 class="font-bold text-xs leading-snug mb-4 text-white">
                            {{ $b->nama }}
                        </h3>
                    </div>
                    <div class="relative z-10">
                        <div class="flex justify-between text-[11px] font-medium text-primary-100 mb-1.5">
                            <span>{{ $bProg['terjawab'] }} dari {{ $bProg['total'] }} butir</span>
                            @if ($isComplete)
                                <span class="font-bold text-emerald-300">✓ Lengkap</span>
                            @endif
                        </div>
                        <div class="h-1.5 bg-black/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ $bProg['persentase'] }}%"></div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Inactive Section Card -->
                <div
                    wire:click="switchSection('{{ $b->kode }}')"
                    class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs hover:shadow-md hover:border-primary-400 transition-all cursor-pointer group flex flex-col justify-between"
                >
                    <div>
                        <div class="flex justify-between items-start mb-2.5">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-slate-900 transition">Bagian {{ $b->kode }}</span>
                            <div class="w-8 h-8 rounded-full border-2 border-slate-200 bg-slate-50 flex items-center justify-center text-slate-600 font-mono text-xs font-bold">
                                {{ $bProg['persentase'] }}%
                            </div>
                        </div>
                        <h3 class="font-bold text-xs leading-snug mb-4 text-slate-800 group-hover:text-primary-700 transition">
                            {{ $b->nama }}
                        </h3>
                    </div>
                    <div>
                        <div class="flex justify-between text-[11px] font-medium text-slate-500 mb-1.5">
                            <span>{{ $bProg['terjawab'] }} dari {{ $bProg['total'] }} butir</span>
                            @if ($isComplete)
                                <span class="font-bold text-emerald-600">✓ Selesai</span>
                            @endif
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-700 rounded-full transition-all duration-500" style="width: {{ $bProg['persentase'] }}%"></div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    @if ($activeBagian)
        <!-- 3. Feedback Inputs Section -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Col 1: Catatan Umum Bagian -->
                <div class="space-y-2">
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[16px] text-slate-500">edit_note</span>
                        <span>Catatan Umum Bagian {{ $activeBagian->kode }}</span>
                    </label>
                    <textarea
                        wire:model.lazy="catatanUmum.{{ $activeBagian->kode }}"
                        rows="3"
                        placeholder="Tambahkan catatan evaluasi secara umum di sini..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 text-xs p-3.5 h-24 resize-none transition-all placeholder:text-slate-400"
                    ></textarea>
                </div>

                <!-- Col 2: Rekomendasi / Tindak Lanjut -->
                <div class="space-y-2">
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[16px] text-slate-500">lightbulb</span>
                        <span>Rekomendasi / Tindak Lanjut</span>
                    </label>
                    <textarea
                        wire:model.lazy="rekomendasiUmum.{{ $activeBagian->kode }}"
                        rows="3"
                        placeholder="Tuliskan rekomendasi perbaikan untuk bagian ini..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 text-xs p-3.5 h-24 resize-none transition-all placeholder:text-slate-400"
                    ></textarea>
                </div>

                <!-- Col 3: Dokumen / SK Terkait -->
                <div class="space-y-2">
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[16px] text-slate-500">policy</span>
                        <span>Dokumen / SK Terkait</span>
                    </label>
                    <textarea
                        wire:model.lazy="dokumenStandar.{{ $activeBagian->kode }}"
                        rows="3"
                        placeholder="Cantumkan nomor SK atau referensi dokumen SOP..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 text-xs p-3.5 h-24 resize-none transition-all placeholder:text-slate-400"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- 4. Detailed Kelompok & Table Section -->
        <div class="space-y-6">
            @foreach ($activeBagian->kelompok as $kIdx => $kelompok)
                @php
                    $butirList = $kelompok->butir;
                    $totalButirKelompok = $butirList->count();
                    $terisiKelompok = 0;

                    foreach ($butirList as $b) {
                        $hasB = !empty($bukti[$b->id] ?? null);
                        $hasC = !empty($catatan[$b->id] ?? null);
                        if ($hasB || $hasC) {
                            $terisiKelompok++;
                        }
                    }
                    $persenKelompok = $totalButirKelompok > 0 ? round(($terisiKelompok / $totalButirKelompok) * 100) : 0;
                @endphp

                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden flex flex-col">
                    <!-- Table Header Area -->
                    <div class="bg-slate-50 border-b border-slate-200 p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h3 class="font-display text-base sm:text-lg font-bold text-slate-900 mb-1 leading-snug">
                                    {{ $activeBagian->kode }}{{ $kIdx + 1 }} – {{ $kelompok->nama }}
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Kriteria standar acuan dan kelengkapan bukti dukung KEPK.
                                </p>
                            </div>
                            <div class="bg-white border border-slate-200 px-4 py-2 rounded-xl flex items-center gap-3 shadow-2xs shrink-0">
                                <div class="w-12 bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-primary-700 h-full transition-all duration-300" style="width: {{ $persenKelompok }}%"></div>
                                </div>
                                <span class="font-mono text-xs font-semibold text-slate-700">
                                    {{ $terisiKelompok }}/{{ $totalButirKelompok }} Dilengkapi
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[850px]">
                            <thead>
                                <tr class="bg-white border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-4 px-6 w-[8%] text-center">Kode</th>
                                    <th class="py-4 px-6 w-[34%]">Kriteria & Acuan Standar</th>
                                    <th class="py-4 px-6 w-[26%]">Bukti Dukung</th>
                                    <th class="py-4 px-6 w-[24%]">Uraian / Catatan</th>
                                    <th class="py-4 px-6 w-[8%] text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100 bg-white">
                                @foreach ($butirList as $bIndex => $butir)
                                    @php
                                        $isEditable = $suratPengajuan->isEditable();
                                        $kodeItem = $activeBagian->kode . ($kIdx + 1) . '.' . ($bIndex + 1);
                                        $selfAns = $suratPengajuan->jawabanEvaluasi->firstWhere('butir_evaluasi_id', $butir->id);
                                        $hasFile = !empty($selfAns?->file_path);
                                        $hasBukti = !empty($bukti[$butir->id] ?? null);
                                        $hasCatatan = !empty($catatan[$butir->id] ?? null);
                                        $isFilled = $hasFile || $hasBukti || $hasCatatan;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors align-top group {{ $isFilled ? 'bg-emerald-50/15' : '' }}">
                                        <!-- Column 1: Kode -->
                                        <td class="py-5 px-6 text-center">
                                            <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md border border-slate-200 text-xs">
                                                {{ $kodeItem }}
                                            </span>
                                        </td>

                                        <!-- Column 2: Kriteria & Acuan Standar -->
                                        <td class="py-5 px-6 space-y-2">
                                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                @if($butir->is_critical)
                                                    <span class="bg-red-50 text-red-700 border border-red-200/80 px-2 py-0.5 rounded text-[10px] font-bold flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[12px]">warning</span>
                                                        <span>Kritis</span>
                                                    </span>
                                                @endif
                                                <span class="text-[10px] text-slate-600 font-medium bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                                    {{ $butir->standar ?? 'Standar A' }}
                                                </span>
                                            </div>
                                            <p class="text-slate-800 leading-relaxed text-xs">
                                                {{ $butir->pertanyaan }}
                                            </p>
                                            @if($butir->evidence_required)
                                                <div class="text-[11px] text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 space-y-0.5">
                                                    <span class="font-bold text-primary-700 flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[13px]">bookmark</span>
                                                        <span>Bukti Acuan:</span>
                                                    </span>
                                                    <p class="italic text-slate-500">{{ $butir->evidence_required }}</p>
                                                </div>
                                            @endif

                                            <!-- Assessor Review Card -->
                                            @if (isset($penilaianAsesor[$butir->id]) && ($penilaianAsesor[$butir->id]->skor || $penilaianAsesor[$butir->id]->temuan || $penilaianAsesor[$butir->id]->catatan))
                                                <div class="mt-2 p-2.5 bg-amber-50/90 border border-amber-200/90 rounded-xl space-y-1 text-xs">
                                                    <div class="flex items-center justify-between flex-wrap gap-1.5">
                                                        <span class="font-bold text-amber-950 flex items-center gap-1 text-[11px]">
                                                            <span class="material-symbols-outlined text-[14px] text-amber-700">search_insights</span>
                                                            <span>Ulasan Asesor:</span>
                                                        </span>
                                                        <div class="flex items-center gap-1.5">
                                                            @if ($penilaianAsesor[$butir->id]->skor)
                                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $penilaianAsesor[$butir->id]->skor === 'A' ? 'bg-emerald-100 text-emerald-800' : ($penilaianAsesor[$butir->id]->skor === 'B' ? 'bg-amber-200 text-amber-900' : 'bg-red-100 text-red-800') }}">
                                                                    Nilai: {{ $penilaianAsesor[$butir->id]->skor }}
                                                                </span>
                                                            @endif
                                                            @if ($penilaianAsesor[$butir->id]->evidence_strength)
                                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">
                                                                    Bukti: {{ $penilaianAsesor[$butir->id]->evidence_strength }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if ($penilaianAsesor[$butir->id]->temuan)
                                                        <div class="text-red-800 bg-red-50/90 p-1.5 rounded-lg border border-red-200/70 text-[11px]">
                                                            <span class="font-bold">⚠️ Temuan:</span> {{ $penilaianAsesor[$butir->id]->temuan }}
                                                        </div>
                                                    @endif
                                                    @if ($penilaianAsesor[$butir->id]->catatan)
                                                        <div class="text-slate-700 text-[11px] pt-0.5">
                                                            <span class="font-semibold text-slate-800">Saran:</span> {{ $penilaianAsesor[$butir->id]->catatan }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Column 3: Bukti Dukung -->
                                        <td class="py-5 px-6 space-y-3">
                                            <div>
                                                <input
                                                    type="text"
                                                    wire:model.blur="bukti.{{ $butir->id }}"
                                                    @disabled(!$isEditable)
                                                    placeholder="No. SK / Dokumen SOP..."
                                                    class="w-full bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 text-xs p-2.5 transition shadow-2xs placeholder:text-slate-400"
                                                />
                                            </div>

                                            @if ($hasFile)
                                                <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-2.5 flex items-center justify-between gap-2 text-xs">
                                                    <div class="overflow-hidden">
                                                        <div class="font-semibold text-emerald-950 truncate flex items-center gap-1 text-[11px]">
                                                            <span class="material-symbols-outlined text-[15px] text-emerald-700">description</span>
                                                            <span class="truncate">{{ $selfAns->file_name }}</span>
                                                        </div>
                                                        <div class="text-[10px] text-emerald-700 font-mono mt-0.5">
                                                            {{ $selfAns->formatUkuran() }}
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-1 shrink-0">
                                                        <a
                                                            href="{{ Storage::url($selfAns->file_path) }}"
                                                            target="_blank"
                                                            class="px-2 py-1 bg-white hover:bg-emerald-100 text-emerald-800 font-bold rounded-lg border border-emerald-300 text-[10px] transition shadow-2xs"
                                                            title="Buka Berkas"
                                                        >
                                                            ⬇ Buka
                                                        </a>
                                                        @if ($isEditable)
                                                            <button
                                                                type="button"
                                                                wire:click="hapusBerkas({{ $butir->id }})"
                                                                class="p-1 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                                title="Hapus Berkas"
                                                            >
                                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                @if ($isEditable)
                                                    <div class="space-y-1">
                                                        <div class="flex items-center gap-1.5">
                                                            <input
                                                                type="file"
                                                                wire:model="uploadedFiles.{{ $butir->id }}"
                                                                id="file_{{ $butir->id }}"
                                                                class="text-[10px] text-slate-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer w-full"
                                                            />
                                                            @if (isset($uploadedFiles[$butir->id]))
                                                                <button
                                                                    type="button"
                                                                    wire:click="uploadBerkas({{ $butir->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    class="px-3 py-1 bg-primary-700 hover:bg-primary-600 text-white text-[10px] font-bold rounded-lg shadow-2xs shrink-0 transition"
                                                                >
                                                                    <span wire:loading.remove wire:target="uploadBerkas({{ $butir->id }})">Upload</span>
                                                                    <span wire:loading wire:target="uploadBerkas({{ $butir->id }})">...</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        @error("uploadedFiles.{$butir->id}")
                                                            <span class="text-red-600 text-[10px] block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                            @endif

                                            @if (session("status_{$butir->id}"))
                                                <div class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 p-1.5 rounded-lg border border-emerald-200">
                                                    ✓ {{ session("status_{$butir->id}") }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Column 4: Uraian / Catatan -->
                                        <td class="py-5 px-6">
                                            <textarea
                                                wire:model.blur="catatan.{{ $butir->id }}"
                                                @disabled(!$isEditable)
                                                rows="3"
                                                placeholder="Uraikan implementasi atau justifikasi di sini..."
                                                class="w-full bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 text-xs p-3 min-h-[90px] resize-none transition shadow-2xs placeholder:text-slate-400"
                                            ></textarea>
                                        </td>

                                        <!-- Column 5: Status -->
                                        <td class="py-5 px-6 text-center">
                                            @if ($isFilled)
                                                <span class="inline-flex items-center justify-center bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-xs font-bold border border-emerald-200/80">
                                                    Terisi
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center bg-slate-100 text-slate-500 px-3 py-1.5 rounded-full text-xs font-medium border border-slate-200">
                                                    Belum
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 5. Bottom Navigation Bar -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between gap-4 mt-6">
            <div>
                @if ($activeSection !== 'A')
                    <button
                        type="button"
                        wire:click="previousSection"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition shadow-2xs"
                    >
                        &larr; Bagian Sebelumnya
                    </button>
                @else
                    <span class="text-xs text-slate-400 font-medium">Awal Borang</span>
                @endif
            </div>

            <div class="text-xs text-slate-500 font-semibold hidden sm:block">
                Bagian <strong>{{ $activeBagian->kode }}</strong> dari 5 Bagian Borang KNEPK
            </div>

            <div>
                @if ($activeSection !== 'E')
                    <button
                        type="button"
                        wire:click="nextSection"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-xs"
                    >
                        <span>Bagian Selanjutnya &rarr;</span>
                    </button>
                @else
                    <a
                        href="{{ route('pengajuan.show', $suratPengajuan) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-xs"
                        wire:navigate
                    >
                        <span>Selesai & Lihat Ringkasan &rarr;</span>
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
