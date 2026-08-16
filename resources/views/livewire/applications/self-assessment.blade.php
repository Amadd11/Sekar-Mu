<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Top Card / Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl select-none">📋</span>
                <h1 class="text-lg font-bold text-slate-900">
                    Borang Evaluasi Diri (Self Assessment KEPK)
                </h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Pengajuan: <strong class="text-slate-800">{{ $application->information->name ?? 'Pengajuan Etik' }}</strong> • No: #APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('applications.show', $application) }}" class="btn-outline btn-sm gap-1 text-xs" wire:navigate>
                &larr; Kembali ke Ringkasan
            </a>
            <a href="{{ route('applications.protocols', $application) }}" class="btn-primary btn-sm gap-1 text-xs" wire:navigate>
                <span>Lanjut ke List Protokol &rarr;</span>
            </a>
        </div>
    </div>

    <!-- 4 Score Metric Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-slate-800">{{ $scoreSummary['total'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">Total Item Dinilai</div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-emerald-600">{{ $scoreSummary['score_a'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">A – Lengkap/Selalu</div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-amber-500">{{ $scoreSummary['score_b'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">B – Sebagian/Kadang</div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-rose-600">{{ $scoreSummary['score_c'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">C – Tidak Lengkap</div>
        </div>
    </div>

    <!-- Section Navigation Tabs -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-2 shadow-2xs flex flex-wrap gap-1.5">
        @foreach ($sections as $sec)
            @php
                $secProg = $progress[$sec->code] ?? ['answered' => 0, 'total' => 0, 'percentage' => 0];
                $isActive = $activeSectionCode === $sec->code;
            @endphp
            <button
                type="button"
                wire:click="selectSection('{{ $sec->code }}')"
                class="flex-1 min-w-[180px] p-3 rounded-lg text-left transition border {{ $isActive ? 'bg-[#174668] text-white border-[#174668] shadow-2xs' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/70' }}"
            >
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold {{ $isActive ? 'text-white' : 'text-slate-900' }}">
                        Bagian {{ $sec->code }}
                    </span>
                    <span class="text-[11px] font-mono {{ $isActive ? 'text-teal-200' : 'text-slate-500' }}">
                        {{ $secProg['answered'] }}/{{ $secProg['total'] }} ({{ $secProg['percentage'] }}%)
                    </span>
                </div>
                <div class="text-[11px] truncate mt-0.5 {{ $isActive ? 'text-teal-100' : 'text-slate-500' }}">
                    {{ $sec->name }}
                </div>
                <!-- Mini Progress bar -->
                <div class="w-full bg-slate-200/70 rounded-full h-1.5 mt-2 overflow-hidden">
                    <div class="h-1.5 rounded-full transition-all {{ $isActive ? 'bg-teal-400' : 'bg-teal-600' }}" style="width: {{ $secProg['percentage'] }}%"></div>
                </div>
            </button>
        @endforeach
    </div>

    <!-- Active Section Questionnaire Items -->
    @if ($currentSection)
        <div class="bg-white border border-slate-200/90 rounded-xl p-6 shadow-2xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200 mb-2">
                    Bagian {{ $currentSection->code }}
                </div>
                <h2 class="text-base font-bold text-slate-900">{{ $currentSection->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Pilih opsi penilaian (A: Lengkap/Selalu, B: Sebagian/Kadang, C: Tidak Lengkap), sertakan bukti dokumen atau catatan penjelasan pendukung.
                </p>
            </div>

            <!-- Groups & Items -->
            <div class="space-y-6">
                @foreach ($currentSection->groups as $group)
                    <div class="border border-slate-200/80 rounded-xl overflow-hidden">
                        <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-200/80 font-bold text-xs text-slate-800 flex items-center justify-between">
                            <span>📁 {{ $group->name }}</span>
                            <span class="text-[11px] text-slate-500 font-normal">{{ $group->items->count() }} Butir Standar</span>
                        </div>

                        <div class="divide-y divide-slate-100 p-4 space-y-4">
                            @foreach ($group->items as $item)
                                @php
                                    $score = $answers[$item->id]['score'] ?? null;
                                @endphp
                                <div class="pt-3 first:pt-0 space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="text-xs font-semibold text-slate-900 leading-relaxed">
                                            <span class="text-teal-700 font-mono">#{{ $item->order }}.</span> {{ $item->question }}
                                        </div>
                                        @if (session()->has("saved_{$item->id}"))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0">
                                                ✓ {{ session("saved_{$item->id}") }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Score Radio Options -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer text-xs transition {{ $score === 'A' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                            <input
                                                type="radio"
                                                wire:model="answers.{{ $item->id }}.score"
                                                value="A"
                                                class="text-emerald-600 focus:ring-emerald-500 w-3.5 h-3.5"
                                            />
                                            <span><strong>A</strong> — Lengkap / Selalu</span>
                                        </label>

                                        <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer text-xs transition {{ $score === 'B' ? 'bg-amber-50 border-amber-300 text-amber-900 font-bold shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                            <input
                                                type="radio"
                                                wire:model="answers.{{ $item->id }}.score"
                                                value="B"
                                                class="text-amber-600 focus:ring-amber-500 w-3.5 h-3.5"
                                            />
                                            <span><strong>B</strong> — Sebagian / Kadang</span>
                                        </label>

                                        <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer text-xs transition {{ $score === 'C' ? 'bg-rose-50 border-rose-300 text-rose-900 font-bold shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                            <input
                                                type="radio"
                                                wire:model="answers.{{ $item->id }}.score"
                                                value="C"
                                                class="text-rose-600 focus:ring-rose-500 w-3.5 h-3.5"
                                            />
                                            <span><strong>C</strong> — Tidak Lengkap</span>
                                        </label>
                                    </div>

                                    <!-- Notes & Evidence Fields -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <div>
                                            <input
                                                type="text"
                                                wire:model.defer="answers.{{ $item->id }}.evidence"
                                                placeholder="Bukti/Link Dokumen SOP, SK, dll."
                                                class="w-full text-xs rounded-lg border-slate-300 text-slate-800 placeholder-slate-400 py-1.5 px-2.5 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                                            />
                                        </div>
                                        <div>
                                            <input
                                                type="text"
                                                wire:model.defer="answers.{{ $item->id }}.comment"
                                                placeholder="Catatan / Penjelasan Tambahan"
                                                class="w-full text-xs rounded-lg border-slate-300 text-slate-800 placeholder-slate-400 py-1.5 px-2.5 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                                            />
                                        </div>
                                    </div>

                                    <!-- Save Item Button -->
                                    <div class="flex justify-end pt-1">
                                        <button
                                            type="button"
                                            wire:click="saveItem({{ $item->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-slate-800 hover:bg-slate-900 active:bg-black text-white text-[11px] font-semibold rounded-lg shadow-2xs transition"
                                        >
                                            <span wire:loading.remove wire:target="saveItem({{ $item->id }})">💾 Simpan Butir #{{ $item->order }}</span>
                                            <span wire:loading wire:target="saveItem({{ $item->id }})">Menyimpan...</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
