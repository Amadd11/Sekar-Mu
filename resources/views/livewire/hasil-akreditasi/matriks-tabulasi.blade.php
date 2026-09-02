<div class="space-y-6 max-w-7xl mx-auto pb-16 print:p-0 print:max-w-none print:space-y-4">
    <!-- Top Header Card (Hidden buttons during print) -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative print:border-none print:shadow-none">
        <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668] print:hidden"></div>

        <div class="p-6 sm:p-7 relative z-10 space-y-4 print:p-0">
            <!-- Top Nav & Actions Row -->
            <div class="flex flex-wrap items-center justify-between gap-3 print:hidden">
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition" wire:navigate>
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Hasil Akreditasi</span>
                    </a>
                    <span class="bg-slate-100 text-slate-700 font-mono text-xs px-3 py-1.5 rounded-xl font-bold border border-slate-200">
                        No. {{ $suratPengajuan->formatted_id }}
                    </span>
                    <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer shadow-2xs" wire:navigate>
                        <span class="material-symbols-outlined text-[16px]">edit_note</span>
                        <span>Buka Borang</span>
                    </a>
                </div>
            </div>

            <!-- Title & Institution Meta -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
                <div>
                    <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        Matriks Tabulasi Akreditasi
                    </h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">
                        {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? $suratPengajuan->kepk->name }} • {{ $suratPengajuan->kepk->name }}
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-center min-w-[120px]">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kepatuhan</div>
                        <div class="text-2xl font-black font-display text-primary-700">{{ $metrics['overall_compliance'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary Stat Strip (Screen only) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 print:hidden">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-2xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Instrumen</span>
            <div class="text-xl font-black text-slate-900 font-display mt-0.5">{{ $stats['total'] }} Butir</div>
        </div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-2xs">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Nilai A (100%)</span>
            <div class="text-xl font-black text-emerald-600 font-display mt-0.5">{{ $stats['countA'] }}</div>
        </div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-2xs">
            <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Nilai B (50%)</span>
            <div class="text-xl font-black text-amber-600 font-display mt-0.5">{{ $stats['countB'] }}</div>
        </div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-2xs">
            <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider block">Nilai C (0%)</span>
            <div class="text-xl font-black text-rose-600 font-display mt-0.5">{{ $stats['countC'] }}</div>
        </div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-2xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Belum Dinilai (-)</span>
            <div class="text-xl font-black text-slate-500 font-display mt-0.5">{{ $stats['unscored'] }}</div>
        </div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-2xs">
            <span class="text-[10px] font-bold text-teal-600 uppercase tracking-wider block">Bukti Lengkap (≥2)</span>
            <div class="text-xl font-black text-teal-700 font-display mt-0.5">{{ $stats['buktiLengkap'] }}</div>
        </div>
    </div>

    <!-- Filters & Search Bar (Screen only) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-2xs flex flex-col lg:flex-row items-center gap-3 print:hidden">
        <!-- Search Input -->
        <div class="w-full lg:flex-1 relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari kode butir (misal: A1.1), uraian standar, atau catatan..."
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 ps-10 pe-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
            <span class="material-symbols-outlined text-slate-400 absolute left-3 top-2.5 text-[18px]">search</span>
        </div>

        <!-- Filter Bagian -->
        <div class="w-full lg:w-52">
            <select
                wire:model.live="filterBagian"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium bg-white">
                <option value="">Semua Bagian (A-E)</option>
                @foreach ($allSections as $sec)
                <option value="{{ $sec->kode }}">Bagian {{ $sec->kode }}: {{ $sec->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Nilai -->
        <div class="w-full lg:w-44">
            <select
                wire:model.live="filterNilai"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium bg-white">
                <option value="">Semua Nilai</option>
                <option value="A">Nilai A (100%)</option>
                <option value="B">Nilai B (50%)</option>
                <option value="C">Nilai C (0%)</option>
                <option value="unscored">Belum Dinilai (-)</option>
            </select>
        </div>

        <!-- Filter Bukti -->
        <div class="w-full lg:w-48">
            <select
                wire:model.live="filterBukti"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium bg-white">
                <option value="">Semua Status Bukti</option>
                <option value="lengkap">Lengkap (≥ 2 Berkas)</option>
                <option value="belum_lengkap">1 Berkas (Belum Lengkap)</option>
                <option value="kosong">Belum Ada Berkas</option>
            </select>
        </div>

        @if ($filterBagian || $filterNilai || $filterBukti || $search)
        <button
            type="button"
            wire:click="resetFilter"
            class="px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer shrink-0">
            Reset Filter
        </button>
        @endif
    </div>

    <!-- MAIN TABULAR MATRIX (Matches User Prototype Layout) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden print:border-slate-300 print:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse min-w-[950px] print:min-w-full print:text-[10px]">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[11px] print:text-[10px]">
                        <th class="py-3.5 px-4 w-20 text-center">KODE</th>
                        <th class="py-3.5 px-4">URAIAN STANDAR & KRITERIA</th>
                        <th class="py-3.5 px-4 w-28 text-center">NILAI</th>
                        <th class="py-3.5 px-4 w-52">BUKTI</th>
                        <th class="py-3.5 px-4 w-64">CATATAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($filteredSections as $secData)
                        @php
                            $bagian = $secData['bagian'];
                        @endphp

                        <!-- 1. Major Section Header (Deep Navy Bar Matching Prototype) -->
                        <tr class="bg-[#174668] text-white print:bg-[#174668] print:text-white font-bold">
                            <td colspan="5" class="py-3 px-4 text-xs tracking-wider uppercase">
                                {{ $bagian->kode }} – {{ $bagian->nama }}
                            </td>
                        </tr>

                        @foreach ($secData['kelompokList'] as $kData)
                            @php
                                $kelompok = $kData['kelompok'];
                                $items = $kData['items'];
                            @endphp

                            <!-- 2. Sub-group Header (Light Bar Matching Prototype) -->
                            <tr class="bg-slate-100/90 text-slate-800 font-semibold border-y border-slate-200">
                                <td colspan="5" class="py-2.5 px-4 text-[11px]">
                                    {{ $bagian->kode }}{{ $kelompok->urutan }} – {{ $kelompok->nama }}
                                </td>
                            </tr>

                            <!-- 3. Question Items Rows -->
                            @foreach ($items as $row)
                                @php
                                    $butir = $row['butir'];
                                    $ans = $row['jawaban'];
                                    $ass = $row['penilaian'];
                                    $skor = $row['skor'];
                                    $attachments = $row['attachments'];
                                    $fileCount = $row['fileCount'];
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors align-top print:border-b print:border-slate-200">
                                    <!-- KODE -->
                                    <td class="py-3.5 px-4 text-center font-mono font-bold text-primary-800">
                                        {{ $butir->kode }}
                                    </td>

                                    <!-- URAIAN STANDAR & KRITERIA -->
                                    <td class="py-3.5 px-4 space-y-1">
                                        <p class="text-slate-800 leading-relaxed font-normal">
                                            {{ $butir->pertanyaan }}
                                        </p>
                                        @if ($butir->standar)
                                        <span class="inline-block text-[10px] text-slate-400 font-mono">
                                            Acuan: {{ $butir->standar }}
                                        </span>
                                        @endif
                                    </td>

                                    <!-- NILAI (Pill Badge matching prototype) -->
                                    <td class="py-3.5 px-4 text-center">
                                        @if ($skor === 'A')
                                            <span class="inline-flex items-center justify-center min-w-[36px] px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 font-mono">
                                                A
                                            </span>
                                        @elseif ($skor === 'B')
                                            <span class="inline-flex items-center justify-center min-w-[36px] px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 font-mono">
                                                B
                                            </span>
                                        @elseif ($skor === 'C')
                                            <span class="inline-flex items-center justify-center min-w-[36px] px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 font-mono">
                                                C
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center min-w-[36px] px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200 font-mono">
                                                -
                                            </span>
                                        @endif
                                    </td>

                                    <!-- BUKTI -->
                                    <td class="py-3.5 px-4 space-y-1">
                                        @if ($fileCount > 0)
                                            <div class="flex items-center gap-1 flex-wrap">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $fileCount >= 2 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                                    <span class="material-symbols-outlined text-[13px]">attach_file</span>
                                                    <span>{{ $fileCount }} Berkas</span>
                                                </span>
                                            </div>
                                            @if (!empty($ans->bukti))
                                                <div class="text-[11px] text-slate-600 font-mono line-clamp-2" title="{{ $ans->bukti }}">
                                                    {{ $ans->bukti }}
                                                </div>
                                            @endif
                                        @elseif (!empty($ans->bukti))
                                            <div class="text-[11px] text-slate-700 font-mono">
                                                {{ $ans->bukti }}
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-xs font-mono">-</span>
                                        @endif
                                    </td>

                                    <!-- CATATAN -->
                                    <td class="py-3.5 px-4 text-slate-600 leading-relaxed space-y-1">
                                        @if (!empty($ass->catatan) || !empty($ass->temuan))
                                            @if ($ass->temuan)
                                                <div class="text-rose-700 text-[11px]">
                                                    <span class="font-bold">Temuan:</span> {{ $ass->temuan }}
                                                </div>
                                            @endif
                                            @if ($ass->catatan)
                                                <div class="text-slate-600 text-[11px]">
                                                    <span class="font-bold">Asesor:</span> {{ $ass->catatan }}
                                                </div>
                                            @endif
                                        @elseif (!empty($ans->catatan))
                                            <div class="text-slate-600 text-[11px]">
                                                {{ $ans->catatan }}
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-xs font-mono">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400 space-y-2">
                                <span class="material-symbols-outlined text-[36px] text-slate-300">table_rows_narrow</span>
                                <p class="font-semibold text-slate-600">Tidak ada butir yang sesuai dengan filter pencarian.</p>
                                <button type="button" wire:click="resetFilter" class="text-xs text-primary-700 font-bold hover:underline cursor-pointer">
                                    Tampilkan Semua Butir
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
