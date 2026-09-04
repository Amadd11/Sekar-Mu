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
