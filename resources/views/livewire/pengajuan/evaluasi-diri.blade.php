<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Full Header Card -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
        <!-- Top Gradient Accent Bar -->
        <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>

        <div class="p-6 sm:p-7 relative z-10 space-y-5">
            <!-- Top Meta Strip: App Code, Status Badge, & Auto-save Live Indicator -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="bg-slate-100 text-slate-700 font-mono text-xs px-3 py-1 rounded-lg font-bold border border-slate-200 shadow-2xs">
                        {{ $suratPengajuan->formatted_id }}
                    </span>
                    <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                </div>

                <!-- Auto-save Live Indicator -->
                <div>
                    <div wire:loading.remove wire:target="uploadBerkas,hapusBerkas,updatedBukti,updatedCatatan" class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200/70 text-xs font-semibold shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="material-symbols-outlined text-[15px] text-emerald-600">cloud_done</span>
                        <span>Perubahan Tersimpan</span>
                    </div>

                    <div wire:loading wire:target="uploadBerkas,hapusBerkas,updatedBukti,updatedCatatan" class="inline-flex items-center gap-1.5 text-primary-700 bg-primary-50 px-3 py-1.5 rounded-full border border-primary-200/70 text-xs font-semibold shadow-2xs">
                        <svg class="animate-spin h-3.5 w-3.5 text-primary-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Menyimpan Perubahan...</span>
                    </div>
                </div>
            </div>

            <!-- Main Title & Action Buttons Row -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
                <div class="space-y-1 max-w-3xl">
                    <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        B01-03: Evaluasi Diri
                    </h1>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                        Asesmen mandiri kepatuhan komite etik berbasis standar KNEPK dan WHO-CIOMS.
                    </p>
                </div>

                <!-- Unified Action Buttons -->
                <div class="flex items-center gap-2 w-full lg:w-auto shrink-0">
                    <a
                        href="{{ route('pengajuan.pdf.evaluasi-diri', $suratPengajuan) }}"
                        target="_blank"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 active:scale-[0.98] transition shadow-xs flex-1 sm:flex-none"
                        title="Unduh Berkas Evaluasi Diri (PDF)">
                        <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                        <span>Unduh PDF</span>
                    </a>

                    <button
                        type="button"
                        onclick="window.print()"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200/90 active:scale-[0.96] transition shadow-2xs shrink-0"
                        title="Cetak Halaman">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                    </button>

                    <a
                        href="{{ route('pengajuan.show', $suratPengajuan) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/80 active:scale-[0.98] transition shadow-2xs flex-1 sm:flex-none"
                        wire:navigate
                        title="Kembali ke Detail Pengajuan">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        <span>Detail Pengajuan</span>
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

            <div
                wire:click="switchSection('{{ $b->kode }}')"
                class="rounded-2xl p-5 shadow-2xs transition-all cursor-pointer group flex flex-col justify-between relative overflow-hidden {{ $isActive ? 'bg-primary-700 text-white shadow-lg shadow-primary-700/20 ring-2 ring-primary-700 ring-offset-2 ring-offset-slate-100' : 'bg-white border border-slate-200/90 hover:shadow-md hover:border-primary-400' }}"
            >
                @if ($isActive)
                    <div class="absolute inset-0 bg-gradient-to-br from-white/15 to-transparent pointer-events-none"></div>
                @endif

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2.5">
                        <span class="text-xs font-bold uppercase tracking-wider {{ $isActive ? 'text-primary-200' : 'text-slate-500 group-hover:text-slate-900 transition' }}">
                            Bagian {{ $b->kode }}
                        </span>
                        <div class="w-8 h-8 rounded-full font-mono text-xs font-bold flex items-center justify-center {{ $isActive ? 'bg-white/20 text-white backdrop-blur-xs font-black' : 'border-2 border-slate-200 bg-slate-50 text-slate-600' }}">
                            {{ $bProg['persentase'] }}%
                        </div>
                    </div>
                    <h3 class="font-bold text-xs leading-snug mb-4 {{ $isActive ? 'text-white' : 'text-slate-800 group-hover:text-primary-700 transition' }}">
                        {{ $b->nama }}
                    </h3>
                </div>

                <div class="relative z-10">
                    <div class="flex justify-between text-[11px] font-medium mb-1.5 {{ $isActive ? 'text-primary-100' : 'text-slate-500' }}">
                        <span>{{ $bProg['terjawab'] }} dari {{ $bProg['total'] }} butir</span>
                        @if ($isComplete)
                            <span class="font-bold {{ $isActive ? 'text-emerald-300' : 'text-emerald-600' }}">✓ Selesai</span>
                        @endif
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden {{ $isActive ? 'bg-black/20' : 'bg-slate-100' }}">
                        <div class="h-full rounded-full transition-all duration-500 {{ $isActive ? 'bg-white' : 'bg-primary-700' }}" style="{{ 'width: ' . $bProg['persentase'] . '%' }}"></div>
                    </div>
                </div>
            </div>
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
                    $kProg = $kelompokProgress[$kelompok->id] ?? ['total' => 0, 'filled' => 0, 'percentage' => 0];
                    $totalButirKelompok = $kProg['total'];
                    $terisiKelompok = $kProg['filled'];
                    $persenKelompok = $kProg['percentage'];
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
                                    <div class="bg-primary-700 h-full transition-all duration-300" style="{{ 'width: ' . $persenKelompok . '%' }}"></div>
                                </div>
                                <span class="font-mono text-xs font-semibold text-slate-700">
                                    {{ $terisiKelompok }}/{{ $totalButirKelompok }} Dilengkapi
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse table-fixed min-w-[950px]">
                            <thead>
                                <tr class="bg-white border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-4 px-4 w-16 text-center">Kode</th>
                                    <th class="py-4 px-5 w-auto">Kriteria & Acuan Standar</th>
                                    <th class="py-4 px-5 w-[310px]">Bukti Dukung</th>
                                    <th class="py-4 px-5 w-[240px]">Uraian / Catatan</th>
                                    <th class="py-4 px-4 w-24 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100 bg-white">
                                @foreach ($butirList as $bIndex => $butir)
                                    @php
                                        $kodeItem = $butir->kode ?? ($activeBagian->kode . ($kIdx + 1) . '.' . ($bIndex + 1));
                                        $selfAns = $jawabanMap[$butir->id] ?? null;
                                        $attachments = $selfAns ? $selfAns->getAttachments() : [];
                                        $hasFile = count($attachments) > 0;
                                        $hasBukti = !empty($bukti[$butir->id] ?? null);
                                        $hasCatatan = !empty($catatan[$butir->id] ?? null);
                                        $isFilled = $hasFile || $hasBukti || $hasCatatan;
                                        $asesorReview = $penilaianAsesor[$butir->id] ?? null;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors align-top group {{ $isFilled ? 'bg-emerald-50/15' : '' }}">
                                        <!-- Column 1: Kode -->
                                        <td class="py-5 px-4 text-center">
                                            <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md border border-slate-200 text-xs">
                                                {{ $kodeItem }}
                                            </span>
                                        </td>

                                        <!-- Column 2: Kriteria & Acuan Standar -->
                                        <td class="py-5 px-5 space-y-2">
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
                                            @if ($asesorReview && ($asesorReview->skor || $asesorReview->temuan || $asesorReview->catatan))
                                                <div class="mt-2 p-2.5 bg-amber-50/90 border border-amber-200/90 rounded-xl space-y-1 text-xs">
                                                    <div class="flex items-center justify-between flex-wrap gap-1.5">
                                                        <span class="font-bold text-amber-950 flex items-center gap-1 text-[11px]">
                                                            <span class="material-symbols-outlined text-[14px] text-amber-700">search_insights</span>
                                                            <span>Ulasan Asesor:</span>
                                                        </span>
                                                        <div class="flex items-center gap-1.5">
                                                            @if ($asesorReview->skor)
                                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $asesorReview->skor === 'A' ? 'bg-emerald-100 text-emerald-800' : ($asesorReview->skor === 'B' ? 'bg-amber-200 text-amber-900' : 'bg-red-100 text-red-800') }}">
                                                                    Nilai: {{ $asesorReview->skor }}
                                                                </span>
                                                            @endif
                                                            @if ($asesorReview->evidence_strength)
                                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">
                                                                    Bukti: {{ $asesorReview->evidence_strength }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if ($asesorReview->temuan)
                                                        <div class="text-red-800 bg-red-50/90 p-1.5 rounded-lg border border-red-200/70 text-[11px]">
                                                            <span class="font-bold">⚠️ Temuan:</span> {{ $asesorReview->temuan }}
                                                        </div>
                                                    @endif
                                                    @if ($asesorReview->catatan)
                                                        <div class="text-slate-700 text-[11px] pt-0.5">
                                                            <span class="font-semibold text-slate-800">Saran:</span> {{ $asesorReview->catatan }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Column 3: Bukti Dukung (Multi-File Supported) -->
                                        <td class="py-5 px-5 space-y-2.5">
                                            <div>
                                                <input
                                                    type="text"
                                                    wire:model.blur="bukti.{{ $butir->id }}"
                                                    @disabled(!$isEditable)
                                                    placeholder="No. SK / Dokumen SOP..."
                                                    class="w-full bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 text-xs p-2.5 transition shadow-2xs placeholder:text-slate-400 truncate"
                                                />
                                            </div>

                                            @if ($hasFile)
                                                <div class="space-y-1.5">
                                                    @foreach ($attachments as $fIdx => $att)
                                                        <div class="bg-emerald-50/90 border border-emerald-200 rounded-xl p-2.5 flex items-center justify-between gap-2 text-xs shadow-2xs overflow-hidden">
                                                            <div class="overflow-hidden min-w-0 flex-1">
                                                                <div class="font-semibold text-emerald-950 flex items-center gap-1.5 text-[11px] min-w-0" title="{{ $att['name'] }}">
                                                                    <span class="material-symbols-outlined text-[15px] text-emerald-700 shrink-0">description</span>
                                                                    <span class="truncate block">{{ $att['name'] }}</span>
                                                                </div>
                                                                <div class="text-[10px] text-emerald-700 font-mono mt-0.5">
                                                                    {{ format_bytes((int) ($att['size'] ?? 0)) }}
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-1.5 shrink-0">
                                                                <a
                                                                    href="{{ Storage::url($att['path']) }}"
                                                                    target="_blank"
                                                                    class="px-2 py-1 bg-white hover:bg-emerald-100 text-emerald-800 font-bold rounded-lg border border-emerald-300 text-[10px] transition shadow-2xs shrink-0"
                                                                    title="Buka / Unduh Berkas"
                                                                >
                                                                    Buka
                                                                </a>
                                                                @if ($isEditable)
                                                                    <button
                                                                        type="button"
                                                                        wire:click="hapusBerkas({{ $butir->id }}, {{ $fIdx }})"
                                                                        wire:confirm="Hapus berkas '{{ $att['name'] }}'?"
                                                                        class="w-6 h-6 rounded-full bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-600 flex items-center justify-center transition border border-rose-200 shrink-0 cursor-pointer"
                                                                        title="Hapus berkas ini"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($isEditable)
                                                <div class="space-y-1 pt-0.5">
                                                    <label for="file_{{ $butir->id }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-50 hover:bg-primary-100 active:bg-primary-200 text-primary-700 hover:text-primary-800 text-[11px] font-bold rounded-xl border border-primary-200/80 shadow-2xs transition-all cursor-pointer">
                                                        <span class="material-symbols-outlined text-[15px] text-primary-600">cloud_upload</span>
                                                        <span>Upload File</span>
                                                        <input
                                                            type="file"
                                                            wire:model="uploadedFiles.{{ $butir->id }}"
                                                            id="file_{{ $butir->id }}"
                                                            multiple
                                                            class="sr-only"
                                                        />
                                                    </label>

                                                    <!-- Loading indicator while uploading -->
                                                    <div wire:loading wire:target="uploadedFiles.{{ $butir->id }}" class="text-[10px] text-[#174668] font-bold flex items-center gap-1.5 animate-pulse">
                                                        <span class="material-symbols-outlined text-[14px] animate-spin">progress_activity</span>
                                                        <span>Mengunggah berkas...</span>
                                                    </div>
                                                    <div class="text-[9.5px] text-slate-400">
                                                        Maks 25 MB (PDF/Doc/Xls/Zip/Img).
                                                    </div>
                                                    @error("uploadedFiles.{$butir->id}")
                                                        <span class="text-rose-600 text-[10px] block font-semibold">{{ $message }}</span>
                                                    @enderror
                                                    @error("uploadedFiles.{$butir->id}.*")
                                                        <span class="text-rose-600 text-[10px] block font-semibold">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif

                                            @if (session("status_{$butir->id}"))
                                                <div class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 p-1.5 rounded-lg border border-emerald-200">
                                                    ✓ {{ session("status_{$butir->id}") }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Column 4: Uraian / Catatan -->
                                        <td class="py-5 px-5 align-top">
                                            <textarea
                                                wire:model.blur="catatan.{{ $butir->id }}"
                                                @disabled(!$isEditable)
                                                rows="3"
                                                placeholder="Uraikan implementasi atau justifikasi di sini..."
                                                class="w-full bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 text-xs p-3 min-h-[90px] resize-y transition shadow-2xs placeholder:text-slate-400"
                                            ></textarea>
                                        </td>

                                        <!-- Column 5: Status -->
                                        <td class="py-5 px-4 text-center">
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