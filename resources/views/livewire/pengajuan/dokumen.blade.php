<div
    x-data="{
        showModal: false,
        selectedItem: null,
        openModal(item) {
            this.selectedItem = item;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedItem = null;
        }
    }"
    @keydown.escape.window="closeModal()"
    class="space-y-5 max-w-7xl mx-auto pb-12"
>
    <!-- 1. Minimalist Header -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="hover:text-primary-700 font-medium inline-flex items-center gap-1 transition" wire:navigate>
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Detail Pengajuan</span>
                    </a>
                    <span>/</span>
                    <span class="font-mono text-slate-600 font-semibold">{{ $suratPengajuan->formatted_id }}</span>
                    <span>•</span>
                    <span class="font-bold text-slate-700 font-mono">{{ $totalFilesAll }} Berkas ({{ $totalButirWithFilesAll }} Butir Berlampiran)</span>
                </div>
                <h1 class="font-display text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                    Arsip Dokumen Bukti Evaluasi Diri
                </h1>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a
                    href="{{ route('pengajuan.evaluasi-diri', [$suratPengajuan, 'section' => $activeSection]) }}"
                    class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 active:bg-primary-800 transition shadow-2xs"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">fact_check</span>
                    <span>Buka Evaluasi Diri</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Search & Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative w-full sm:w-80">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400">search</span>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari nomor butir, nama berkas, kriteria..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition"
            />
            @if ($search !== '')
                <button
                    type="button"
                    wire:click="resetSearch"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs p-0.5 cursor-pointer"
                    title="Hapus pencarian"
                >
                    <span class="material-symbols-outlined text-[15px]">close</span>
                </button>
            @endif
        </div>

        <!-- Status Filter Pills -->
        <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto text-xs">
            <span class="text-[11px] text-slate-400 font-medium mr-1 hidden md:inline">Status:</span>
            <button
                type="button"
                wire:click="$set('filterStatus', 'all')"
                class="px-2.5 py-1 rounded-lg font-medium transition cursor-pointer {{ $filterStatus === 'all' ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
            >
                Semua
            </button>
            <button
                type="button"
                wire:click="$set('filterStatus', 'lengkap')"
                class="px-2.5 py-1 rounded-lg font-medium transition cursor-pointer flex items-center gap-1 {{ $filterStatus === 'lengkap' ? 'bg-emerald-600 text-white font-bold' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200/60' }}"
            >
                <span class="w-1.5 h-1.5 rounded-full {{ $filterStatus === 'lengkap' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                <span>Lengkap (≥2)</span>
            </button>
            <button
                type="button"
                wire:click="$set('filterStatus', 'belum_lengkap')"
                class="px-2.5 py-1 rounded-lg font-medium transition cursor-pointer flex items-center gap-1 {{ $filterStatus === 'belum_lengkap' ? 'bg-amber-500 text-white font-bold' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200/60' }}"
            >
                <span class="w-1.5 h-1.5 rounded-full {{ $filterStatus === 'belum_lengkap' ? 'bg-white' : 'bg-amber-500' }}"></span>
                <span>1 Berkas</span>
            </button>
        </div>
    </div>

    <!-- 3. Section Segmented Tabs (Bagian A - E) -->
    <div class="bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200/80 grid grid-cols-2 sm:grid-cols-5 gap-1.5">
        @foreach ($bagianList as $b)
            @php
                $secFileCount = $fileCountsPerBagian[$b->kode] ?? 0;
                $secButirCount = $butirCountsPerBagian[$b->kode] ?? 0;
                $isActive = $activeSection === $b->kode;
            @endphp

            <button
                type="button"
                wire:click="setSection('{{ $b->kode }}')"
                class="rounded-xl px-3 py-2.5 text-left transition-all flex flex-col justify-between gap-1 cursor-pointer {{ $isActive ? 'bg-white text-slate-900 shadow-xs border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}"
            >
                <div class="flex items-center justify-between w-full">
                    <span class="text-xs font-bold {{ $isActive ? 'text-primary-700' : 'text-slate-700' }}">
                        Bagian {{ $b->kode }}
                    </span>
                    <span class="font-mono text-[10px] px-1.5 py-0.2 rounded-md font-bold {{ $secFileCount > 0 ? ($isActive ? 'bg-emerald-100 text-emerald-800' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') : 'bg-slate-200 text-slate-500' }}">
                        {{ $secFileCount }} Berkas
                    </span>
                </div>
                <div class="text-[11px] font-medium truncate w-full" title="{{ $b->nama }}">
                    {{ $b->nama }}
                </div>
                <div class="text-[10px] text-slate-400 font-mono">
                    {{ $secButirCount }} butir terisi
                </div>
            </button>
        @endforeach
    </div>

    <!-- 4. Active Section High-Density Butir Table (1 Row per Butir) -->
    @php
        $secItems = $activeSectionData['items'] ?? [];
        $secTotalFiles = $activeSectionData['total_files'] ?? 0;
    @endphp

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <!-- Subheader -->
        <div class="px-5 py-3.5 bg-slate-50/80 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-primary-700 text-white font-mono font-bold text-xs flex items-center justify-center shadow-2xs">
                    {{ $activeBagianModel->kode }}
                </span>
                <div>
                    <h2 class="font-display text-sm font-bold text-slate-900 leading-snug">
                        Bagian {{ $activeBagianModel->kode }}: {{ $activeBagianModel->nama }}
                    </h2>
                    <p class="text-[11px] text-slate-500">
                        {{ count($secItems) }} butir dengan total {{ $secTotalFiles }} berkas bukti dukung.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('pengajuan.evaluasi-diri', [$suratPengajuan, 'section' => $activeBagianModel->kode]) }}"
                class="inline-flex items-center gap-1 px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition shadow-2xs self-start sm:self-center"
                wire:navigate
            >
                <span>Kelola di Evaluasi Diri</span>
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>

        @if (count($secItems) > 0)
            <!-- High-Density Table: 1 Row per Butir -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs min-w-[760px]">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400 uppercase tracking-wider bg-white">
                            <th class="py-2.5 px-4 w-20 text-center">Butir</th>
                            <th class="py-2.5 px-4 w-auto">Kriteria & Acuan Standar</th>
                            <th class="py-2.5 px-4 w-36 text-center">Berkas Bukti</th>
                            <th class="py-2.5 px-4 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($secItems as $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <!-- Col 1: Kode Butir -->
                                <td class="py-3 px-4 text-center align-middle">
                                    <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded text-[11px] border border-slate-200/80">
                                        {{ $item['kode'] }}
                                    </span>
                                </td>

                                <!-- Col 2: Kriteria -->
                                <td class="py-3 px-4 align-middle">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px] text-slate-500 font-mono">
                                                {{ $item['standar'] }}
                                            </span>
                                        </div>
                                        <p class="text-slate-800 font-medium text-xs truncate max-w-[420px]" title="{{ $item['pertanyaan'] }}">
                                            {{ $item['pertanyaan'] }}
                                        </p>
                                    </div>
                                </td>

                                <!-- Col 3: Status / Counter Berkas -->
                                <td class="py-3 px-4 text-center align-middle">
                                    @if ($item['count'] >= 2)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                            <span>{{ $item['count'] }} Berkas</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="material-symbols-outlined text-[13px]">hourglass_top</span>
                                            <span>1 Berkas</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Col 4: Tombol Buka Modal -->
                                <td class="py-3 px-4 text-center align-middle">
                                    <button
                                        type="button"
                                        @click="openModal({{ json_encode($item) }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-50 hover:bg-primary-100 text-primary-700 font-bold rounded-xl text-xs border border-primary-200/80 transition cursor-pointer shadow-2xs"
                                        title="Buka pratinjau berkas butir ini"
                                    >
                                        <span class="material-symbols-outlined text-[15px]">visibility</span>
                                        <span>Lihat Berkas</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="p-10 text-center text-slate-400 space-y-2">
                <span class="material-symbols-outlined text-[36px] text-slate-300">folder_off</span>
                <p class="text-xs font-semibold text-slate-600">
                    @if ($search !== '')
                        Tidak ada butir berlampiran yang cocok dengan pencarian "{{ $search }}" pada Bagian {{ $activeBagianModel->kode }}.
                    @else
                        Belum ada berkas bukti dukung yang diunggah pada Bagian {{ $activeBagianModel->kode }}.
                    @endif
                </p>
                <p class="text-[11px] text-slate-400">
                    Unggah berkas melalui halaman <a href="{{ route('pengajuan.evaluasi-diri', [$suratPengajuan, 'section' => $activeBagianModel->kode]) }}" class="text-primary-700 underline font-semibold" wire:navigate>Evaluasi Diri Bagian {{ $activeBagianModel->kode }}</a>.
                </p>
            </div>
        @endif
    </div>

    <!-- 5. Simple Clean File List Modal (No Viewer, No Backdrop Blur) -->
    <template x-teleport="body">
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/50 overflow-y-auto"
            style="display: none;"
        >
            <div
                @click.outside="closeModal()"
                x-show="showModal"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden text-slate-800"
            >
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/80 flex items-start justify-between gap-4">
                    <div class="space-y-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono font-bold text-xs bg-slate-200 text-slate-800 px-2.5 py-0.5 rounded-md" x-text="'Butir ' + (selectedItem ? selectedItem.kode : '')"></span>
                            <span class="text-xs font-semibold text-slate-500" x-text="selectedItem ? selectedItem.kelompok_nama : ''"></span>
                            <template x-if="selectedItem && selectedItem.count >= 2">
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">✓ Lengkap (2+ Berkas)</span>
                            </template>
                        </div>
                        <h3 class="font-display text-sm font-bold text-slate-900 leading-snug" x-text="selectedItem ? selectedItem.pertanyaan : ''"></h3>
                    </div>

                    <button
                        type="button"
                        @click="closeModal()"
                        class="text-slate-400 hover:text-slate-700 p-1.5 rounded-xl hover:bg-slate-200 transition cursor-pointer shrink-0"
                        title="Tutup Modal"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Modal Subheader: Catatan Info -->
                <div class="px-6 py-3 bg-slate-50/50 border-b border-slate-100 text-xs" x-show="selectedItem && selectedItem.catatan">
                    <div class="text-slate-600 bg-white p-2.5 rounded-xl border border-slate-200">
                        <span class="font-bold text-slate-700">Catatan KEPK:</span>
                        <span x-text="selectedItem ? selectedItem.catatan : ''"></span>
                    </div>
                </div>

                <!-- Modal Body: Clean File List -->
                <div class="p-6 flex-1 overflow-y-auto space-y-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                        Daftar Berkas Terlampir (<span x-text="selectedItem ? selectedItem.count : 0"></span>):
                    </span>

                    <div class="divide-y divide-slate-100 border border-slate-200 rounded-2xl overflow-hidden bg-white">
                        <template x-for="(att, idx) in (selectedItem ? selectedItem.attachments : [])" :key="idx">
                            <div class="p-3.5 sm:px-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">description</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 text-xs truncate max-w-[340px]" :title="att.name" x-text="att.name"></div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5" x-text="att.size_formatted"></div>
                                    </div>
                                </div>

                                <a
                                    :href="att.url"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-primary-700 hover:text-white text-slate-700 font-bold rounded-xl text-xs transition shrink-0"
                                    title="Buka atau unduh berkas ini"
                                >
                                    <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                                    <span>Buka / Unduh</span>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3 text-xs">
                    <a
                        :href="'{{ url('/pengajuan/' . $suratPengajuan->id . '/evaluasi-diri') }}?section=' + (selectedItem ? '{{ $activeBagianModel->kode }}' : 'A')"
                        class="inline-flex items-center gap-1 text-primary-700 hover:underline font-bold"
                    >
                        <span>Kelola di Evaluasi Diri</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>

                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>