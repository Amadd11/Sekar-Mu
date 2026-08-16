<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                    {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">Borang Evaluasi Diri KEPK (B01-03)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Penilaian mandiri berdasarkan instrumen standar WHO-CIOMS (155 Butir).</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                &larr; Kembali ke Detail Pengajuan
            </a>
        </div>
    </div>

    <!-- 4 Score Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-score-card title="Total Item Dinilai" :count="$rekapSkor['total']" color="slate" />
        <x-score-card title="A – Lengkap/Selalu" :count="$rekapSkor['skor_a']" color="emerald" />
        <x-score-card title="B – Sebagian/Kadang" :count="$rekapSkor['skor_b']" color="amber" />
        <x-score-card title="C – Tidak Lengkap" :count="$rekapSkor['skor_c']" color="rose" />
    </div>

    <!-- Section Tabs (A s/d E) -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-2 shadow-2xs">
        <div class="flex flex-wrap gap-2">
            @foreach ($bagianList as $b)
                @php
                    $bProg = $progress[$b->kode] ?? ['terjawab' => 0, 'total' => 0, 'persentase' => 0];
                    $isActive = $activeSection === $b->kode;
                @endphp
                <button
                    type="button"
                    wire:click="switchSection('{{ $b->kode }}')"
                    class="flex-1 min-w-[140px] px-4 py-3 rounded-lg text-left transition flex flex-col justify-between border {{ $isActive ? 'bg-[#174668] text-white border-[#174668] shadow-2xs font-bold' : 'bg-slate-50/70 hover:bg-slate-100 text-slate-700 border-slate-200' }}"
                >
                    <div class="text-xs font-bold leading-snug">
                        Bagian {{ $b->kode }}
                    </div>
                    <div class="text-[11px] opacity-80 truncate mt-0.5">
                        {{ $b->nama }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-[10px] font-mono">
                        <span>{{ $bProg['terjawab'] }}/{{ $bProg['total'] }}</span>
                        <span>{{ $bProg['persentase'] }}%</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Questionnaire Items List -->
    @if ($activeBagian)
        <div class="space-y-6">
            <div class="bg-[#174668] text-white p-4 rounded-xl shadow-2xs flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold">Bagian {{ $activeBagian->kode }}: {{ $activeBagian->nama }}</h2>
                    <p class="text-xs text-teal-100/70 mt-0.5">Pilih skor penilaian dan sertakan bukti dokumen pendukung.</p>
                </div>
            </div>

            @foreach ($activeBagian->kelompok as $kIdx => $kelompok)
                <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            {{ $activeBagian->kode }}.{{ $kIdx + 1 }} {{ $kelompok->nama }}
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @foreach ($kelompok->butir as $butir)
                            @php
                                $selectedSkor = $skor[$butir->id] ?? null;
                            @endphp
                            <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/50 space-y-3">
                                <div class="text-xs font-semibold text-slate-900 leading-relaxed">
                                    {{ $butir->pertanyaan }}
                                </div>

                                <!-- Score Selection Buttons -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs text-slate-500 font-medium mr-2">Pilihan Skor:</span>

                                    <button
                                        type="button"
                                        wire:click="setSkor({{ $butir->id }}, 'A')"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ $selectedSkor === 'A' ? 'bg-emerald-600 text-white border-emerald-600 shadow-2xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-emerald-50' }}"
                                    >
                                        A (Lengkap / Selalu)
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="setSkor({{ $butir->id }}, 'B')"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ $selectedSkor === 'B' ? 'bg-amber-500 text-white border-amber-500 shadow-2xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-amber-50' }}"
                                    >
                                        B (Sebagian / Kadang)
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="setSkor({{ $butir->id }}, 'C')"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ $selectedSkor === 'C' ? 'bg-rose-600 text-white border-rose-600 shadow-2xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-rose-50' }}"
                                    >
                                        C (Tidak Lengkap / Tidak Ada)
                                    </button>
                                </div>

                                <!-- Evidence and Notes -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                    <div>
                                        <input
                                            type="text"
                                            wire:model.lazy="bukti.{{ $butir->id }}"
                                            wire:change="simpanCatatan({{ $butir->id }})"
                                            placeholder="Bukti dukung (misal: SOP-01, SK Rektor, dll)..."
                                            class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600 bg-white"
                                        />
                                    </div>
                                    <div>
                                        <input
                                            type="text"
                                            wire:model.lazy="catatan.{{ $butir->id }}"
                                            wire:change="simpanCatatan({{ $butir->id }})"
                                            placeholder="Catatan penjelas mandiri..."
                                            class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600 bg-white"
                                        />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
