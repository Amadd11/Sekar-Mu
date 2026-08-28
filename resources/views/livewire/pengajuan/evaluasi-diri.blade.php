<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Minimalist Header -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Left: Breadcrumb & Title -->
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="hover:text-primary-700 font-medium inline-flex items-center gap-1 transition" wire:navigate>
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Detail Pengajuan</span>
                    </a>
                    <span>/</span>
                    <span class="font-mono text-slate-600 font-semibold">{{ $suratPengajuan->formatted_id }}</span>
                    <span>•</span>
                    <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                </div>
                <h1 class="font-display text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Evaluasi Diri (164 Butir Standar KEPK)
                </h1>
                <p class="text-xs text-slate-500">
                    Asesmen mandiri kepatuhan komite etik berbasis standar KNEPK & WHO-CIOMS.
                </p>
            </div>

            <!-- Right: Auto-save status & Actions -->
            <div class="flex items-center gap-2.5 shrink-0">
                <!-- Auto-save Indicator -->
                <div class="text-xs">
                    <div wire:loading.remove wire:target="uploadBerkas,hapusBerkas,updatedBukti,updatedCatatan,simpanCatatan" class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200/70 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[11px]">Tersimpan</span>
                    </div>

                    <div wire:loading wire:target="uploadBerkas,hapusBerkas,updatedBukti,updatedCatatan,simpanCatatan" class="inline-flex items-center gap-1.5 text-primary-700 bg-primary-50 px-2.5 py-1.5 rounded-xl border border-primary-200/70 font-medium">
                        <svg class="animate-spin h-3 w-3 text-primary-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-[11px]">Menyimpan...</span>
                    </div>
                </div>

                <!-- Action: PDF Download -->
                <a
                    href="{{ route('pengajuan.pdf.evaluasi-diri', $suratPengajuan) }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition shadow-2xs"
                    title="Unduh PDF"
                >
                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                    <span class="hidden sm:inline">Unduh PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Minimalist Segmented Tabs (Bagian A - E) -->
    <div class="bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200/80 grid grid-cols-2 sm:grid-cols-5 gap-1.5">
        @foreach ($bagianList as $b)
            @php
                $bProg = $progress[$b->kode] ?? ['terjawab' => 0, 'total' => 0, 'persentase' => 0, 'belum_lengkap' => 0];
                $isActive = $activeSection === $b->kode;
                $isComplete = $bProg['total'] > 0 && $bProg['terjawab'] === $bProg['total'];
            @endphp

            <button
                type="button"
                wire:click="switchSection('{{ $b->kode }}')"
                class="rounded-xl px-3 py-2.5 text-left transition-all flex flex-col justify-between gap-1.5 cursor-pointer {{ $isActive ? 'bg-white text-slate-900 shadow-xs border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}"
            >
                <div class="flex items-center justify-between w-full">
                    <span class="text-xs font-bold {{ $isActive ? 'text-primary-700' : 'text-slate-700' }}">
                        Bagian {{ $b->kode }}
                    </span>
                    <span class="font-mono text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ $isComplete ? 'bg-emerald-100 text-emerald-800' : ($isActive ? 'bg-primary-100 text-primary-800' : 'bg-slate-200/70 text-slate-600') }}">
                        {{ $bProg['persentase'] }}%
                    </span>
                </div>
                <div class="text-[11px] font-medium truncate w-full" title="{{ $b->nama }}">
                    {{ $b->nama }}
                </div>
                <!-- Mini Progress Bar -->
                <div class="w-full bg-slate-200/60 h-1 rounded-full overflow-hidden mt-0.5">
                    <div class="h-full rounded-full transition-all duration-300 {{ $isComplete ? 'bg-emerald-500' : ($isActive ? 'bg-primary-600' : 'bg-slate-400') }}" style="{{ 'width: ' . $bProg['persentase'] . '%' }}"></div>
                </div>
            </button>
        @endforeach
    </div>

    @if ($activeBagian)
        <!-- 3. Minimalist Collapsible Section Notes (Optional) -->
        <details class="group bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-2xs transition">
            <summary class="p-4 sm:px-6 font-semibold text-xs text-slate-700 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/70 transition">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">edit_note</span>
                    <span>Catatan Umum & Rekomendasi Bagian {{ $activeBagian->kode }} (Opsional)</span>
                </div>
                <span class="material-symbols-outlined text-[18px] text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
            </summary>

            <div class="p-5 sm:p-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50/40">
                <!-- Catatan Umum -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Catatan Umum</label>
                    <textarea
                        wire:model.lazy="catatanUmum.{{ $activeBagian->kode }}"
                        rows="2"
                        placeholder="Catatan evaluasi umum..."
                        class="w-full bg-white border border-slate-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-xs p-3 resize-y transition placeholder:text-slate-400"
                    ></textarea>
                </div>

                <!-- Rekomendasi -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Rekomendasi / Tindak Lanjut</label>
                    <textarea
                        wire:model.lazy="rekomendasiUmum.{{ $activeBagian->kode }}"
                        rows="2"
                        placeholder="Rekomendasi perbaikan..."
                        class="w-full bg-white border border-slate-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-xs p-3 resize-y transition placeholder:text-slate-400"
                    ></textarea>
                </div>

                <!-- Dokumen Standar -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Dokumen / SK Terkait</label>
                    <textarea
                        wire:model.lazy="dokumenStandar.{{ $activeBagian->kode }}"
                        rows="2"
                        placeholder="Nomor SK atau referensi SOP..."
                        class="w-full bg-white border border-slate-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-xs p-3 resize-y transition placeholder:text-slate-400"
                    ></textarea>
                </div>
            </div>
        </details>

        <!-- 4. Kelompok & Table Items -->
        <div class="space-y-6">
            @foreach ($activeBagian->kelompok as $kIdx => $kelompok)
                @php
                    $butirList = $kelompok->butir;
                    $kProg = $kelompokProgress[$kelompok->id] ?? ['total' => 0, 'filled' => 0, 'percentage' => 0, 'belum_lengkap' => 0];
                    $totalButirKelompok = $kProg['total'];
                    $terisiKelompok = $kProg['filled'];
                    $persenKelompok = $kProg['percentage'];
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                    <!-- Kelompok Subheader -->
                    <div class="px-5 py-4 bg-slate-50/80 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="font-display text-sm font-bold text-slate-900 leading-snug">
                                {{ $activeBagian->kode }}{{ $kIdx + 1 }} – {{ $kelompok->nama }}
                            </h3>
                        </div>
                        <div class="flex items-center gap-2.5 shrink-0">
                            <div class="w-16 bg-slate-200/80 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-primary-600 h-full rounded-full transition-all duration-300" style="{{ 'width: ' . $persenKelompok . '%' }}"></div>
                            </div>
                            <span class="font-mono text-xs text-slate-600 font-semibold">
                                {{ $terisiKelompok }}/{{ $totalButirKelompok }} Lengkap
                                @if(($kProg['belum_lengkap'] ?? 0) > 0)
                                    <span class="text-amber-600 font-normal">({{ $kProg['belum_lengkap'] }} Belum Lengkap)</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-500 uppercase tracking-wider bg-white">
                                    <th class="py-3 px-4 w-16 text-center">Kode</th>
                                    <th class="py-3 px-4 w-auto">Kriteria & Acuan Standar</th>
                                    <th class="py-3 px-4 w-[300px]">Bukti Dukung (Minimal 2 Berkas)</th>
                                    <th class="py-3 px-4 w-[240px]">Catatan / Uraian</th>
                                    <th class="py-3 px-4 w-28 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100 bg-white">
                                @foreach ($butirList as $bIndex => $butir)
                                    @php
                                        $kodeItem = $butir->kode ?? ($activeBagian->kode . ($kIdx + 1) . '.' . ($bIndex + 1));
                                        $selfAns = $jawabanMap[$butir->id] ?? null;
                                        $attachments = $selfAns ? $selfAns->getAttachments() : [];
                                        $fileCount = count($attachments);
                                        $asesorReview = $penilaianAsesor[$butir->id] ?? null;

                                        $rowBg = match(true) {
                                            $fileCount >= 2 => 'bg-emerald-50/15',
                                            $fileCount === 1 => 'bg-amber-50/15',
                                            default => '',
                                        };
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors align-top {{ $rowBg }}">
                                        <!-- Column 1: Kode -->
                                        <td class="py-4 px-4 text-center">
                                            <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded text-xs border border-slate-200/70">
                                                {{ $kodeItem }}
                                            </span>
                                        </td>

                                        <!-- Column 2: Kriteria & Pertanyaan -->
                                        <td class="py-4 px-4 space-y-2">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                @if($butir->is_critical)
                                                    <span class="bg-rose-50 text-rose-700 border border-rose-200 px-1.5 py-0.5 rounded text-[10px] font-bold">
                                                        Kritis
                                                    </span>
                                                @endif
                                                <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded font-mono">
                                                    {{ $butir->standar ?? 'Standar' }}
                                                </span>
                                            </div>

                                            <p class="text-slate-800 leading-relaxed text-xs">
                                                {{ $butir->pertanyaan }}
                                            </p>

                                            @if($butir->evidence_required)
                                                <p class="text-[11px] text-slate-500 italic bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                    <span class="font-semibold text-slate-600 not-italic">Acuan:</span> {{ $butir->evidence_required }}
                                                </p>
                                            @endif

                                            <!-- Assessor Feedback if present -->
                                            @if ($asesorReview && ($asesorReview->skor || $asesorReview->temuan || $asesorReview->catatan))
                                                <div class="p-2.5 bg-amber-50/80 border border-amber-200 rounded-xl space-y-1 text-xs">
                                                    <div class="flex items-center justify-between text-[11px]">
                                                        <span class="font-bold text-amber-900">Ulasan Asesor:</span>
                                                        <div class="flex items-center gap-1">
                                                            @if ($asesorReview->skor)
                                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $asesorReview->skor === 'A' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                                    Skor: {{ $asesorReview->skor }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if ($asesorReview->temuan)
                                                        <div class="text-rose-700 text-[11px]">
                                                            <strong>Temuan:</strong> {{ $asesorReview->temuan }}
                                                        </div>
                                                    @endif
                                                    @if ($asesorReview->catatan)
                                                        <div class="text-slate-600 text-[11px]">
                                                            <strong>Saran:</strong> {{ $asesorReview->catatan }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Column 3: Bukti Dukung (Multi-File) -->
                                        <td class="py-4 px-4 space-y-2">
                                            <!-- Text Ref (SK / Dokumen) -->
                                            <div>
                                                <input
                                                    type="text"
                                                    wire:model.blur="bukti.{{ $butir->id }}"
                                                    @disabled(!$isEditable)
                                                    placeholder="No. SK / Dokumen SOP..."
                                                    class="w-full bg-white border border-slate-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-xs p-2 transition shadow-2xs placeholder:text-slate-400"
                                                />
                                            </div>

                                            <!-- Uploaded File Tags -->
                                            @if ($fileCount > 0)
                                                <div class="space-y-1">
                                                    @foreach ($attachments as $fIdx => $att)
                                                        <div class="bg-slate-50 border border-slate-200/80 rounded-lg p-2 flex items-center justify-between gap-2 text-xs">
                                                            <div class="overflow-hidden min-w-0 flex-1">
                                                                <div class="font-medium text-slate-800 text-[11px] truncate flex items-center gap-1" title="{{ $att['name'] }}">
                                                                    <span class="material-symbols-outlined text-[14px] text-slate-500 shrink-0">description</span>
                                                                    <span class="truncate">{{ $att['name'] }}</span>
                                                                </div>
                                                                <div class="text-[10px] text-slate-400 font-mono">
                                                                    {{ format_bytes((int) ($att['size'] ?? 0)) }}
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-1 shrink-0">
                                                                <a
                                                                    href="{{ Storage::url($att['path']) }}"
                                                                    target="_blank"
                                                                    class="px-2 py-0.5 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded border border-slate-200 text-[10px] transition"
                                                                >
                                                                    Buka
                                                                </a>
                                                                @if ($isEditable)
                                                                    <button
                                                                        type="button"
                                                                        wire:click="hapusBerkas({{ $butir->id }}, {{ $fIdx }})"
                                                                        wire:confirm="Hapus berkas '{{ $att['name'] }}'?"
                                                                        class="w-5 h-5 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition cursor-pointer"
                                                                        title="Hapus berkas"
                                                                    >
                                                                        <span class="material-symbols-outlined text-[13px]">close</span>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Upload Input -->
                                            @if ($isEditable)
                                                <div class="pt-0.5">
                                                    <label for="file_{{ $butir->id }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-slate-700 bg-slate-100 hover:bg-slate-200 text-[11px] font-semibold rounded-lg border border-slate-200 transition cursor-pointer">
                                                        <span class="material-symbols-outlined text-[14px]">upload_file</span>
                                                        <span>+ Upload Berkas</span>
                                                        <input
                                                            type="file"
                                                            wire:model="uploadedFiles.{{ $butir->id }}"
                                                            id="file_{{ $butir->id }}"
                                                            multiple
                                                            class="sr-only"
                                                        />
                                                    </label>

                                                    <div wire:loading wire:target="uploadedFiles.{{ $butir->id }}" class="text-[10px] text-primary-700 font-medium inline-flex items-center gap-1 ml-2 animate-pulse">
                                                        <span class="material-symbols-outlined text-[13px] animate-spin">progress_activity</span>
                                                        <span>Mengunggah...</span>
                                                    </div>

                                                    @error("uploadedFiles.{$butir->id}")
                                                        <span class="text-rose-600 text-[10px] block font-medium mt-1">{{ $message }}</span>
                                                    @enderror
                                                    @error("uploadedFiles.{$butir->id}.*")
                                                        <span class="text-rose-600 text-[10px] block font-medium mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif

                                            @if (session("status_{$butir->id}"))
                                                <div class="text-[10px] text-emerald-700 font-medium bg-emerald-50 p-1.5 rounded-lg border border-emerald-200">
                                                    ✓ {{ session("status_{$butir->id}") }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Column 4: Catatan / Uraian -->
                                        <td class="py-4 px-4 align-top">
                                            <textarea
                                                wire:model.blur="catatan.{{ $butir->id }}"
                                                @disabled(!$isEditable)
                                                rows="2"
                                                placeholder="Uraian penjelasan..."
                                                class="w-full bg-white border border-slate-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-xs p-2.5 min-h-[70px] resize-y transition placeholder:text-slate-400"
                                            ></textarea>
                                        </td>

                                        <!-- Column 5: Status Badge -->
                                        <td class="py-4 px-4 text-center align-middle">
                                            @if ($fileCount >= 2)
                                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md text-[11px] font-semibold border border-emerald-200" title="{{ $fileCount }} berkas (Lengkap)">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>Lengkap</span>
                                                </span>
                                            @elseif ($fileCount === 1)
                                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-0.5 rounded-md text-[11px] font-semibold border border-amber-200" title="1 berkas (Belum Lengkap, minimal 2 berkas)">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <span>1/2 Berkas</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-400 px-2 py-0.5 rounded-md text-[11px] font-medium border border-slate-200" title="Belum ada berkas">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                                    <span>Belum</span>
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

        <!-- 5. Minimalist Bottom Navigation -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-2xs flex items-center justify-between gap-4 mt-6">
            <div>
                @if ($activeSection !== 'A')
                    <button
                        type="button"
                        wire:click="previousSection"
                        class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition"
                    >
                        &larr; Bagian Sebelumnya
                    </button>
                @else
                    <span class="text-xs text-slate-400 font-medium">Bagian Awal</span>
                @endif
            </div>

            <div class="text-xs text-slate-500 font-medium hidden sm:block">
                Bagian <strong>{{ $activeBagian->kode }}</strong> ({{ $activeBagian->nama }})
            </div>

            <div>
                @if ($activeSection !== 'E')
                    <button
                        type="button"
                        wire:click="nextSection"
                        class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-2xs"
                    >
                        <span>Bagian Selanjutnya &rarr;</span>
                    </button>
                @else
                    <a
                        href="{{ route('pengajuan.show', $suratPengajuan) }}"
                        class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-2xs"
                        wire:navigate
                    >
                        <span>Selesai & Lihat Ringkasan &rarr;</span>
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>