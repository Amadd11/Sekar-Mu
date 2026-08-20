<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    #APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                    {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $metrics['prediction']['badge_class'] }}">
                    {{ $metrics['prediction']['type'] }} ({{ $metrics['overall_compliance'] }}%)
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">
                Workspace Asesor: {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'Borang Akreditasi KEPK' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pemohon: <strong>{{ $suratPengajuan->user->name }}</strong> • KEPK: {{ $suratPengajuan->kepk->name ?? '-' }} ({{ $suratPengajuan->kepk->institusi->name ?? '-' }})
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a
                href="{{ route('pengajuan.pdf.hasil-akreditasi', $suratPengajuan) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-2xs"
            >
                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                <span>Unduh Laporan PDF</span>
            </a>
            <a
                href="{{ route('pengajuan.pdf.matriks-gap', $suratPengajuan) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition shadow-2xs"
            >
                <span class="material-symbols-outlined text-[16px]">bar_chart</span>
                <span>Matriks Gap PDF</span>
            </a>
            <a href="{{ route('penilaian.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition shadow-2xs" wire:navigate>
                &larr; Kembali ke Daftar
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    @if (session('action_status'))
        <div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('action_status') }}</span>
        </div>
    @endif

    <!-- 4 Score Cards (Self Assessment vs Compliance Summary) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-pengajuan.stat-card
            label="Kepatuhan Total"
            :value="$metrics['overall_compliance'] . '%'"
            :subtext="$metrics['total_answered'] . '/' . $metrics['total_items'] . ' butir terisi'"
            icon="📈"
            valueColor="text-[#174668]"
        />

        <x-pengajuan.stat-card
            label="Prediksi Akreditasi"
            :value="$metrics['prediction']['type']"
            :subtext="'Nilai C: ' . $metrics['counts']['C'] . ' (Batas Tipe B: ≤5)'"
            icon="🏆"
            valueColor="text-emerald-700"
        />

        <x-pengajuan.stat-card
            label="Temuan Kritis"
            :value="$gapAnalysis['critical_findings_count']"
            subtext="Butir kritis bernilai C"
            icon="⚠️"
            :valueColor="$gapAnalysis['critical_findings_count'] > 0 ? 'text-rose-600' : 'text-slate-700'"
        />

        <x-pengajuan.stat-card
            label="Kesenjangan (Gap)"
            :value="$comparisonMatrix['total_gaps']"
            subtext="Perbedaan skor KEPK vs Asesor"
            icon="📊"
            valueColor="text-amber-600"
        />
    </div>

    <!-- Main Navigation Tabs -->
    <div class="border-b border-slate-200 flex items-center gap-2 overflow-x-auto text-xs font-semibold">
        <button
            type="button"
            wire:click="switchTab('asesmen_butir')"
            class="py-2.5 px-4 border-b-2 transition flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'asesmen_butir' ? 'border-[#174668] text-[#174668] font-bold bg-white' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
        >
            <span>🔍</span>
            <span>1. Penilaian Kriteria 164 Butir (A / B / C / D)</span>
        </button>

        <button
            type="button"
            wire:click="switchTab('ringkasan')"
            class="py-2.5 px-4 border-b-2 transition flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'ringkasan' ? 'border-[#174668] text-[#174668] font-bold bg-white' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
        >
            <span>📋</span>
            <span>2. Ringkasan Berkas & Rekomendasi</span>
        </button>

        <button
            type="button"
            wire:click="switchTab('matriks_gap')"
            class="py-2.5 px-4 border-b-2 transition flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'matriks_gap' ? 'border-[#174668] text-[#174668] font-bold bg-white' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
        >
            <span>⚖️</span>
            <span>3. Matriks Komparasi Gap</span>
            @if($comparisonMatrix['total_gaps'] > 0)
                <span class="px-1.5 py-0.2 rounded-full bg-amber-100 text-amber-800 text-[10px]">{{ $comparisonMatrix['total_gaps'] }}</span>
            @endif
        </button>

        <button
            type="button"
            wire:click="switchTab('corrective_actions')"
            class="py-2.5 px-4 border-b-2 transition flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'corrective_actions' ? 'border-[#174668] text-[#174668] font-bold bg-white' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
        >
            <span>🛠️</span>
            <span>4. Tindakan Korektif (CAPA)</span>
            @if($correctiveActions->count() > 0)
                <span class="px-1.5 py-0.2 rounded-full bg-slate-100 text-slate-800 text-[10px]">{{ $correctiveActions->count() }}</span>
            @endif
        </button>
    </div>

    <!-- TAB 1: RINGKASAN & REKOMENDASI -->
    @if ($activeTab === 'ringkasan')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Details & Documents & Protocols -->
            <div class="lg:col-span-2 space-y-6">
                <!-- 1. Protocols List -->
                <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900">1. Protokol Penelitian ({{ $suratPengajuan->listProtokol->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                                <tr>
                                    <th class="px-5 py-3">No. Protokol</th>
                                    <th class="px-5 py-3">Judul Penelitian</th>
                                    <th class="px-5 py-3">Jalur Telaah</th>
                                    <th class="px-5 py-3">Peneliti Utama</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($suratPengajuan->listProtokol as $prot)
                                    <tr>
                                        <td class="px-5 py-3 font-mono font-bold text-slate-700">{{ $prot->nomor_protokol }}</td>
                                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $prot->judul }}</td>
                                        <td class="px-5 py-3">
                                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                                {{ str_replace('_', ' ', $prot->review_type ?? 'full_board') }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-slate-600">{{ $prot->peneliti_utama }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-4 text-center text-slate-400">Tidak ada protokol riset terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Documents List -->
                @php
                    $perItemFiles = $suratPengajuan->jawabanEvaluasi->whereNotNull('file_path');
                    $totalAllFiles = $suratPengajuan->dokumen->count() + $perItemFiles->count();
                @endphp
                <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900">2. Berkas Lampiran & Bukti Terunggah ({{ $totalAllFiles }})</h3>
                        <button
                            type="button"
                            wire:click="switchTab('asesmen_butir')"
                            class="text-xs text-[#174668] font-bold hover:underline"
                        >
                            Buka di Lembar 164 Butir &rarr;
                        </button>
                    </div>
                    <div class="divide-y divide-slate-100 text-xs">
                        <!-- Per-Item Uploaded Files -->
                        @foreach ($perItemFiles as $itemFile)
                            <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50 bg-emerald-50/20">
                                <div>
                                    <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-[#174668] text-white">
                                            Butir #{{ $itemFile->butir_evaluasi_id }}
                                        </span>
                                        <span>📄</span>
                                        <span>{{ $itemFile->file_name ?? $itemFile->bukti }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $itemFile->formatUkuran() }} • Bukti: {{ $itemFile->bukti ?? '-' }}
                                    </div>
                                </div>
                                <a
                                    href="{{ Storage::url($itemFile->file_path) }}"
                                    target="_blank"
                                    class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition shadow-2xs flex items-center gap-1"
                                >
                                    <span>⬇</span>
                                    <span>Buka / Unduh</span>
                                </a>
                            </div>
                        @endforeach

                        <!-- General Document Attachments -->
                        @foreach ($suratPengajuan->dokumen as $doc)
                            <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50">
                                <div>
                                    <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-200 text-slate-700">Lampiran Umum</span>
                                        <span>📄</span>
                                        <span>{{ $doc->nama_asli }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                        {{ $doc->formatUkuran() }} • Diunggah: {{ $doc->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                                <a
                                    href="{{ Storage::url($doc->path) }}"
                                    target="_blank"
                                    class="px-3 py-1 bg-teal-50 hover:bg-teal-100 text-teal-800 font-semibold rounded-lg text-xs transition"
                                >
                                    ⬇ Buka / Unduh
                                </a>
                            </div>
                        @endforeach

                        @if ($totalAllFiles === 0)
                            <div class="px-5 py-6 text-center text-slate-400">
                                Belum ada berkas lampiran yang diunggah oleh pemohon.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 3. Comments Thread -->
                <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900">3. Catatan & Permintaan Perbaikan</h3>
                    </div>

                    @if (session('comment_status'))
                        <div class="bg-emerald-50 text-emerald-800 text-xs p-2.5 rounded-lg border border-emerald-200">
                            {{ session('comment_status') }}
                        </div>
                    @endif

                    <div class="space-y-3">
                        @php
                            $allComments = $semuaPenilaian->flatMap->catatanPenilaian;
                        @endphp
                        @forelse ($allComments as $c)
                            <div class="p-3.5 rounded-xl border {{ $c->selesai ? 'bg-slate-50 border-slate-200 text-slate-500' : 'bg-amber-50/50 border-amber-200 text-slate-800' }} text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900">{{ $c->user->name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $c->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="toggleSelesai({{ $c->id }})"
                                        class="text-[11px] font-semibold px-2 py-0.5 rounded border transition {{ $c->selesai ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-100' }}"
                                    >
                                        {{ $c->selesai ? '✓ Selesai Diperbaiki' : 'Tandai Selesai' }}
                                    </button>
                                </div>
                                <p class="leading-relaxed">{{ $c->catatan }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic py-2">Belum ada catatan penilaian.</p>
                        @endforelse
                    </div>

                    <!-- Add Comment Form -->
                    <div class="pt-2 border-t border-slate-100 space-y-2">
                        <label for="catatanBaru" class="block text-xs font-semibold text-slate-700">
                            Tambah Catatan / Permintaan Perbaikan:
                        </label>
                        <textarea
                            wire:model="catatanBaru"
                            id="catatanBaru"
                            rows="2"
                            placeholder="Tuliskan catatan penilaian atau aspek butir yang memerlukan perbaikan pemohon..."
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        ></textarea>
                        @error('catatanBaru')
                            <span class="text-red-500 text-[11px] block">{{ $message }}</span>
                        @enderror
                        <div class="flex justify-end">
                            <button
                                type="button"
                                wire:click="kirimCatatan"
                                class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                            >
                                Kirim Catatan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Recommendation Form -->
            <div class="space-y-6">
                <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900">Form Rekomendasi Penilai</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Berikan keputusan kelayakan etik terhadap permohonan ini.</p>
                    </div>

                    <form wire:submit="simpanPenilaian" class="space-y-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-2">
                                Keputusan Rekomendasi <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $rekomendasi === 'approved' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input
                                        type="radio"
                                        wire:model="rekomendasi"
                                        value="approved"
                                        class="text-emerald-600 focus:ring-emerald-500"
                                    />
                                    <div>
                                        <div class="font-bold">Disetujui (Layak Etik)</div>
                                        <div class="text-[10px] text-emerald-700 font-normal">Memenuhi seluruh standar etik WHO-CIOMS.</div>
                                    </div>
                                </label>

                                <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $rekomendasi === 'revision_required' ? 'bg-amber-50 border-amber-300 text-amber-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input
                                        type="radio"
                                        wire:model="rekomendasi"
                                        value="revision_required"
                                        class="text-amber-600 focus:ring-amber-500"
                                    />
                                    <div>
                                        <div class="font-bold">Perlu Perbaikan / Revisi</div>
                                        <div class="text-[10px] text-amber-700 font-normal">Membutuhkan kelengkapan atau perbaikan berkas.</div>
                                    </div>
                                </label>

                                <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $rekomendasi === 'rejected' ? 'bg-rose-50 border-rose-300 text-rose-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input
                                        type="radio"
                                        wire:model="rekomendasi"
                                        value="rejected"
                                        class="text-rose-600 focus:ring-rose-500"
                                    />
                                    <div>
                                        <div class="font-bold">Ditolak (Tidak Layak Etik)</div>
                                        <div class="text-[10px] text-rose-700 font-normal">Terdapat pelanggaran etik substansial.</div>
                                    </div>
                                </label>
                            </div>
                            @error('rekomendasi')
                                <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan" class="block font-semibold text-slate-700 mb-1">
                                Kesimpulan / Catatan Akhir Penilai:
                            </label>
                            <textarea
                                wire:model="catatan"
                                id="catatan"
                                rows="4"
                                placeholder="Tuliskan ringkasan pertimbangan keputusan kelayakan etik..."
                                class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                            ></textarea>
                            @error('catatan')
                                <span class="text-red-500 text-[11px] block mt-0.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full py-2.5 px-4 bg-[#174668] hover:bg-[#133e5f] active:bg-slate-900 text-white font-semibold rounded-lg shadow-2xs transition"
                            >
                                <span wire:loading.remove wire:target="simpanPenilaian">💾 Simpan Keputusan</span>
                                <span wire:loading wire:target="simpanPenilaian">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 1: PENILAIAN INDEPENDEN 164 BUTIR (PRD SECTION 7 & 8) -->
    @if ($activeTab === 'asesmen_butir')
        <div class="space-y-6">
            <!-- Section selector tabs with progress -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
                @foreach ($bagianList as $b)
                    @php
                        $isActive = $activeSection === $b->kode;
                        $prog = $sectionProgress[$b->kode] ?? ['total' => 0, 'scored' => 0, 'pct' => 0];
                    @endphp
                    <button
                        type="button"
                        wire:click="switchSection('{{ $b->kode }}')"
                        class="p-3 rounded-xl border text-left transition relative {{ $isActive ? 'bg-[#174668] text-white border-[#174668] font-bold shadow-xs ring-2 ring-[#174668]/30' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] uppercase font-bold tracking-wider {{ $isActive ? 'text-teal-200' : 'text-slate-500' }}">Bagian {{ $b->kode }}</span>
                            <span class="text-[10px] font-mono px-1.5 py-0.2 rounded font-bold {{ $isActive ? 'bg-white/20 text-white' : ($prog['pct'] === 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ $prog['scored'] }}/{{ $prog['total'] }}
                            </span>
                        </div>
                        <div class="text-xs font-semibold truncate mt-1">{{ $b->nama }}</div>
                        <div class="w-full bg-slate-200/50 rounded-full h-1 mt-2 overflow-hidden">
                            <div class="h-full {{ $isActive ? 'bg-teal-300' : 'bg-emerald-500' }}" style="width: {{ $prog['pct'] }}%"></div>
                        </div>
                    </button>
                @endforeach
            </div>

            @if ($activeBagian)
                <div class="space-y-6">
                    @foreach ($activeBagian->kelompok as $kIdx => $kelompok)
                        <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                            <div class="bg-[#174668] text-white px-5 py-3 flex items-center justify-between">
                                <div class="font-bold text-xs sm:text-sm">
                                    {{ $activeBagian->kode }}{{ $kIdx + 1 }} – {{ $kelompok->nama }}
                                </div>
                                <div class="text-[11px] font-mono text-teal-200">
                                    {{ $kelompok->butir->count() }} Butir Kriteria
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left border-collapse">
                                    <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold text-[11px] uppercase">
                                        <tr>
                                            <th class="py-3 px-4 w-14 text-center">KODE</th>
                                            <th class="py-3 px-4 min-w-[280px]">KRITERIA & BERKAS DARI KEPK</th>
                                            <th class="py-3 px-4 w-72 text-center">NILAI & BOBOT BUKTI ASESOR</th>
                                            <th class="py-3 px-4 min-w-[240px]">TEMUAN & CATATAN PERBAIKAN</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($kelompok->butir as $bIdx => $butir)
                                            @php
                                                $selfAns = $suratPengajuan->jawabanEvaluasi->firstWhere('butir_evaluasi_id', $butir->id);
                                                $selSkor = $itemSkor[$butir->id] ?? '';
                                                $selStrength = $evidenceStrength[$butir->id] ?? '';
                                                $kodeItem = $activeBagian->kode . ($kIdx + 1) . '.' . ($bIdx + 1);
                                                $hasData = !empty($selfAns?->bukti) || !empty($selfAns?->catatan) || !empty($selfAns?->file_path);
                                            @endphp
                                            <tr class="hover:bg-slate-50/70 transition {{ $selSkor ? 'bg-emerald-50/15' : '' }}">
                                                <!-- Col 1: Kode -->
                                                <td class="py-3.5 px-4 text-center align-top font-bold text-[#174668] text-xs">
                                                    {{ $kodeItem }}
                                                </td>

                                                <!-- Col 2: Kriteria & Berkas KEPK -->
                                                <td class="py-3.5 px-4 align-top space-y-2">
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        @if($butir->is_critical)
                                                            <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                                ⚠️ BUTIR KRITIS
                                                            </span>
                                                        @endif
                                                        <span class="text-[10px] font-mono text-slate-500 font-semibold">{{ $butir->standar ?? 'Standar Akreditasi' }}</span>
                                                    </div>
                                                    <div class="font-medium text-slate-900 text-xs leading-relaxed">
                                                        {{ $butir->pertanyaan }}
                                                    </div>

                                                    <!-- KEPK Evidence & Notes Card -->
                                                    <div class="p-2.5 rounded-lg border text-xs space-y-1.5 {{ $hasData ? 'bg-emerald-50/50 border-emerald-200' : 'bg-slate-50 border-slate-200/80' }}">
                                                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-1">
                                                            <span class="font-bold text-[10px] uppercase text-[#174668]">Data dari KEPK:</span>
                                                            @if($hasData)
                                                                <span class="inline-flex px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">✓ Sudah Dilengkapi</span>
                                                            @else
                                                                <span class="inline-flex px-1.5 py-0.2 rounded text-[9px] font-medium bg-slate-200 text-slate-600">Belum Diisi</span>
                                                            @endif
                                                        </div>

                                                        @if($selfAns?->bukti)
                                                            <div class="text-slate-800 font-medium text-[11px]">
                                                                <span class="text-slate-500 font-normal">📄 Bukti / SK:</span> {{ $selfAns->bukti }}
                                                            </div>
                                                        @endif

                                                        @if($selfAns?->file_path)
                                                            <div class="p-2 bg-white rounded-lg border border-emerald-300 flex items-center justify-between gap-2 shadow-2xs">
                                                                <div class="font-semibold text-emerald-950 text-[11px] truncate flex items-center gap-1.5 overflow-hidden">
                                                                    <span>📎</span>
                                                                    <span class="truncate">{{ $selfAns->file_name ?? 'Berkas Lampiran' }}</span>
                                                                    <span class="text-[10px] text-slate-400 font-mono font-normal">({{ $selfAns->formatUkuran() }})</span>
                                                                </div>
                                                                <a
                                                                    href="{{ Storage::url($selfAns->file_path) }}"
                                                                    target="_blank"
                                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] rounded-md shrink-0 transition shadow-2xs flex items-center gap-1"
                                                                >
                                                                    <span>⬇</span>
                                                                    <span>Buka File</span>
                                                                </a>
                                                            </div>
                                                        @endif

                                                        @if($selfAns?->catatan)
                                                            <div class="text-slate-700 bg-white p-2 rounded border border-slate-200/60 leading-relaxed text-[11px]">
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
                                                <td class="py-3.5 px-4 align-top space-y-2">
                                                    <!-- 1. Skor A / B / C / D -->
                                                    <div>
                                                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1 text-center">Skor Asesor (PRD Sec. 7):</span>
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            @foreach([
                                                                'A' => ['bg' => 'bg-emerald-600', 'title' => 'A: Lengkap / 100%'],
                                                                'B' => ['bg' => 'bg-amber-500', 'title' => 'B: Sebagian / 50%'],
                                                                'C' => ['bg' => 'bg-rose-600', 'title' => 'C: Tidak Ada / 0%'],
                                                                'D' => ['bg' => 'bg-[#174668]', 'title' => 'D: N/a'],
                                                            ] as $opt => $optMeta)
                                                                <button
                                                                    type="button"
                                                                    wire:click="setItemSkor({{ $butir->id }}, '{{ $opt }}')"
                                                                    title="{{ $optMeta['title'] }}"
                                                                    class="flex-1 py-1.5 rounded-lg border text-center font-bold text-xs transition {{ $selSkor === $opt ? "{$optMeta['bg']} text-white border-transparent shadow-xs ring-2 ring-offset-1 ring-slate-400" : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100' }}"
                                                                >
                                                                    {{ $opt }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- 2. Evidence Strength E0..E4 (PRD Sec. 8) -->
                                                    <div class="pt-1 border-t border-slate-100">
                                                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1 text-center">Bobot Bukti (PRD Sec. 8):</span>
                                                        <div class="grid grid-cols-5 gap-1">
                                                            @foreach([
                                                                'E0' => 'E0: Tanpa Bukti',
                                                                'E1' => 'E1: Kurang Lengkap',
                                                                'E2' => 'E2: Dokumen Lengkap',
                                                                'E3' => 'E3: Dok + Implementasi',
                                                                'E4' => 'E4: Dok + Evaluasi'
                                                            ] as $eCode => $eDesc)
                                                                <button
                                                                    type="button"
                                                                    wire:click="setStrength({{ $butir->id }}, '{{ $eCode }}')"
                                                                    title="{{ $eDesc }}"
                                                                    class="py-1 rounded text-center font-mono font-bold text-[10px] transition {{ $selStrength === $eCode ? 'bg-[#174668] text-white border border-[#174668] shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200' }}"
                                                                >
                                                                    {{ $eCode }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Col 4: TEMUAN & CATATAN PERBAIKAN -->
                                                <td class="py-3.5 px-4 align-top space-y-1.5">
                                                    <div>
                                                        <input
                                                            type="text"
                                                            wire:model.blur="itemTemuan.{{ $butir->id }}"
                                                            placeholder="Tuliskan temuan ketidaksesuaian..."
                                                            class="w-full text-xs rounded-lg border-slate-300 px-2.5 py-1.5 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                                                        />
                                                    </div>
                                                    <div>
                                                        <textarea
                                                            wire:model.blur="itemCatatan.{{ $butir->id }}"
                                                            rows="2"
                                                            placeholder="Catatan atau rekomendasi perbaikan asesor..."
                                                            class="w-full text-xs rounded-lg border-slate-300 p-2 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs resize-y"
                                                        ></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                    <!-- Bottom Section Navigation -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <button
                            type="button"
                            wire:click="previousSection"
                            class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition shadow-2xs"
                        >
                            &larr; Bagian Sebelumnya
                        </button>
                        <button
                            type="button"
                            wire:click="nextSection"
                            class="px-4 py-2 bg-[#174668] hover:bg-[#133e5f] text-white font-bold rounded-xl text-xs transition shadow-2xs"
                        >
                            Bagian Selanjutnya &rarr;
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- TAB 3: MATRIKS KOMPARASI GAP (SELF VS ASESOR) -->
    @if ($activeTab === 'matriks_gap')
        <div class="bg-white border border-slate-200 rounded-xl shadow-2xs p-5 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Matriks Komparasi: Self-Assessment KEPK vs Asesmen Asesor</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Membandingkan skor evaluasi diri mandiri dengan skor verifikasi lapangan asesor per butir penilaian.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $comparisonMatrix['total_matches'] }} Sesuai
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $comparisonMatrix['total_gaps'] }} Kesenjangan (Gap)
                    </span>
                </div>
            </div>

            @foreach ($comparisonMatrix['sections'] as $secCode => $secData)
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                    <div class="bg-[#174668] text-white px-4 py-2.5 font-bold text-xs flex items-center justify-between">
                        <span>Bagian {{ $secCode }}: {{ $secData['section_name'] }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead class="bg-slate-100 border-b border-slate-200 text-slate-600 font-bold text-[11px]">
                                <tr>
                                    <th class="py-2.5 px-4 w-16 text-center">KODE</th>
                                    <th class="py-2.5 px-4">KRITERIA BUTIR</th>
                                    <th class="py-2.5 px-4 w-28 text-center">SELF KEPK</th>
                                    <th class="py-2.5 px-4 w-28 text-center">ASESOR</th>
                                    <th class="py-2.5 px-4 w-36 text-center">STATUS GAP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($secData['items'] as $item)
                                    <tr class="hover:bg-slate-50 {{ $item['has_gap'] ? 'bg-amber-50/40' : '' }}">
                                        <td class="py-3 px-4 text-center font-bold text-slate-700">{{ $item['kode_butir'] }}</td>
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-slate-900">{{ $item['pertanyaan'] }}</div>
                                            @if($item['is_critical'])
                                                <span class="inline-flex px-1.5 py-0.2 text-[9px] font-bold bg-rose-100 text-rose-800 rounded mt-0.5">⚠️ Butir Kritis</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex px-2 py-0.5 rounded font-mono font-bold text-xs bg-slate-100 text-slate-800 border border-slate-200">
                                                {{ $item['self_score'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex px-2 py-0.5 rounded font-mono font-bold text-xs {{ $item['assessor_score'] !== '-' ? 'bg-teal-100 text-teal-800 border border-teal-200' : 'bg-slate-100 text-slate-400' }}">
                                                {{ $item['assessor_score'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if ($item['has_gap'])
                                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                    ⚠️ {{ $item['gap_label'] }}
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    ✓ Sesuai (0)
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
    @endif

    <!-- TAB 4: TINDAKAN KOREKTIF (CAPA) -->
    @if ($activeTab === 'corrective_actions')
        <div class="bg-white border border-slate-200 rounded-xl shadow-2xs p-5 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Rencana & Realisasi Tindakan Korektif (CAPA)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pemantauan tindakan perbaikan atas temuan kritis atau ketidaksesuaian borang.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold text-[11px] uppercase">
                        <tr>
                            <th class="py-3 px-4 min-w-[200px]">TEMUAN (FINDING)</th>
                            <th class="py-3 px-4 min-w-[200px]">RENCANA TINDAKAN (ACTION)</th>
                            <th class="py-3 px-4 w-28">PRIORITAS</th>
                            <th class="py-3 px-4 w-28">DEADLINE</th>
                            <th class="py-3 px-4 w-36 text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($correctiveActions as $ca)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-4 align-top">
                                    <div class="font-bold text-slate-900">{{ $ca->finding }}</div>
                                    @if($ca->risk)
                                        <div class="text-[11px] text-rose-600 mt-0.5 font-medium">⚠️ Risiko: {{ $ca->risk }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 align-top">
                                    <div class="text-slate-800">{{ $ca->action }}</div>
                                    <div class="text-[11px] text-slate-500 mt-1">PIC: <strong>{{ $ca->pic_name ?? '-' }}</strong></div>
                                </td>
                                <td class="py-3.5 px-4 align-top">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $ca->priority === 'HIGH' ? 'bg-rose-100 text-rose-800' : ($ca->priority === 'MEDIUM' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $ca->priority }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 align-top font-mono text-slate-600">
                                    {{ $ca->deadline?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 align-top text-center">
                                    <select
                                        wire:change="updateCorrectiveActionStatus({{ $ca->id }}, $event.target.value)"
                                        class="text-xs rounded border-slate-300 font-bold py-1 px-2 focus:ring-1 focus:ring-[#174668]"
                                    >
                                        @foreach(\App\Models\CorrectiveAction::STATUSES as $st)
                                            <option value="{{ $st }}" @selected($ca->status === $st)>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    Belum ada tindakan korektif tercatat untuk pengajuan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
