<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Full Header Card (Matching Evaluasi Diri & Borang Design) -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
        <!-- Top Gradient Accent Bar -->
        <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>

        <div class="p-6 sm:p-7 relative z-10 space-y-4">
            <!-- Top Meta Strip -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="bg-primary-50 text-primary-700 font-display text-xs px-3 py-1 rounded-lg font-bold border border-primary-200/70 shadow-2xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px]">tune</span>
                        <span>Master Instrumen Evaluasi</span>
                    </span>
                </div>
            </div>

            <!-- Main Title & Action Buttons Row -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
                <div class="space-y-1 max-w-3xl">
                    <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        Kelola Kriteria & Acuan Standar Akreditasi
                    </h1>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                        Kelola parameter instrumen penilaian, bobot temuan kritis, dan acuan bukti dukung akreditasi KEPK.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap w-full sm:w-auto shrink-0">
                    <button
                        type="button"
                        wire:click="bukaModalKelompok"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer shadow-2xs">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                        <span>Kelola Kelompok</span>
                    </button>

                    <button
                        type="button"
                        wire:click="bukaModalCreate"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>+ Tambah Butir</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('status'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
            <span class="font-semibold">{{ session('status') }}</span>
        </div>
    </div>
    @endif

    <!-- 2. KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Butir</span>
                <span class="w-8 h-8 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">fact_check</span>
                </span>
            </div>
            <div class="text-2xl font-black text-slate-900 font-display mt-2">{{ $totalButir }}</div>
            <p class="text-[11px] text-slate-400 mt-0.5">Instrumen aktif dalam sistem</p>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bagian Standar</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">folder_copy</span>
                </span>
            </div>
            <div class="text-2xl font-black text-blue-800 font-display mt-2">{{ $totalBagianCount }} Bagian</div>
            <p class="text-[11px] text-slate-400 mt-0.5">Bagian A s/d E (Standar WHO)</p>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kelompok Acuan</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">account_tree</span>
                </span>
            </div>
            <div class="text-2xl font-black text-emerald-800 font-display mt-2">{{ $totalKelompokCount }} Kelompok</div>
            <p class="text-[11px] text-slate-400 mt-0.5">Kategori sub-standar asesmen</p>
        </div>
    </div>

    <!-- 3. Filters & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex flex-col lg:flex-row items-center gap-3">
        <!-- Search Input -->
        <div class="w-full lg:flex-1 relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nomor butir, bunyi kriteria, standar acuan, atau parameter..."
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 ps-10 pe-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
            <span class="material-symbols-outlined text-slate-400 absolute left-3 top-2.5 text-[18px]">search</span>
        </div>

        <!-- Filter Bagian -->
        <div class="w-full lg:w-48">
            <select
                wire:model.live="selectedBagian"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium">
                <option value="">Semua Bagian (A-E)</option>
                @foreach ($daftarBagian as $b)
                <option value="{{ $b->id }}">Bagian {{ $b->kode }}: {{ $b->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Kelompok -->
        <div class="w-full lg:w-56">
            <select
                wire:model.live="selectedKelompok"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium">
                <option value="">Semua Kelompok</option>
                @foreach ($daftarKelompok as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Per Page -->
        <div class="w-full lg:w-28">
            <select
                wire:model.live="perPage"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium">
                <option value="10">10 / hal</option>
                <option value="15">15 / hal</option>
                <option value="25">25 / hal</option>
                <option value="50">50 / hal</option>
            </select>
        </div>
    </div>

    <!-- 4. Kriteria & Acuan Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-6 py-4 w-24 text-center whitespace-nowrap">Kode Butir</th>
                        <th class="px-6 py-4 w-52">Bagian & Kelompok</th>
                        <th class="px-6 py-4 min-w-[320px]">Kriteria & Pertanyaan Evaluasi</th>
                        <th class="px-6 py-4 w-60">Standar Acuan & Parameter</th>
                        <th class="px-6 py-4 w-52">Bukti Disyaratkan</th>
                        <th class="px-6 py-4 text-right w-28 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($butirList as $b)
                    <tr class="hover:bg-slate-50/60 transition group">
                        <!-- Kode Butir -->
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <span class="font-mono font-bold text-primary-800 bg-primary-50 px-3 py-1 rounded-lg border border-primary-200/80 text-xs shadow-2xs">
                                {{ $b->kode }}
                            </span>
                        </td>

                        <!-- Bagian & Kelompok -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-700 font-mono text-[11px] flex items-center justify-center font-bold">
                                    {{ $b->kelompok?->bagian?->kode ?? '-' }}
                                </span>
                                <span>Bagian {{ $b->kelompok?->bagian?->kode ?? '-' }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 mt-1 leading-snug">
                                {{ $b->kelompok?->nama ?? '-' }}
                            </div>
                        </td>

                        <!-- Pertanyaan / Kriteria -->
                        <td class="px-6 py-4 space-y-2">
                            <div class="font-semibold text-slate-900 leading-relaxed text-xs">
                                {{ $b->pertanyaan }}
                            </div>
                        </td>

                        <!-- Standar & Parameter -->
                        <td class="px-6 py-4 space-y-1.5">
                            <div class="font-bold text-slate-800 text-[11px] flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] text-primary-700">rule</span>
                                <span>{{ $b->standar ?? 'Standar WHO-CIOMS' }}</span>
                            </div>
                            @if ($b->parameter)
                            <p class="text-slate-500 text-[11px] leading-relaxed line-clamp-2" title="{{ $b->parameter }}">
                                {{ $b->parameter }}
                            </p>
                            @else
                            <span class="text-slate-400 italic text-[10px]">Parameter umum standar</span>
                            @endif
                        </td>

                        <!-- Dokumen Bukti Disyaratkan -->
                        <td class="px-6 py-4">
                            @if ($b->evidence_required)
                            <div class="text-slate-600 text-[11px] leading-snug flex items-start gap-1.5 p-2 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="material-symbols-outlined text-primary-700 text-[15px] shrink-0 mt-0.5">attach_file</span>
                                <span class="line-clamp-2" title="{{ $b->evidence_required }}">{{ $b->evidence_required }}</span>
                            </div>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500">
                                Tidak wajib upload
                            </span>
                            @endif
                        </td>

                        <!-- Aksi (Edit & Hapus) -->
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <button
                                    type="button"
                                    wire:click="bukaModalEdit({{ $b->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition shadow-2xs"
                                    title="Edit & Revisi Kriteria">
                                    <span class="material-symbols-outlined text-[15px]">edit</span>
                                    <span>Edit</span>
                                </button>

                                <button
                                    type="button"
                                    wire:click="konfirmasiHapusKriteria({{ $b->id }})"
                                    class="p-1.5 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer"
                                    title="Hapus Kriteria">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400 space-y-3">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                <span class="material-symbols-outlined text-[32px]">search_off</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Tidak ada kriteria butir yang sesuai dengan filter pencarian.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Coba ubah kata kunci atau filter bagian/kelompok.</p>
                            </div>
                            <button
                                type="button"
                                wire:click="bukaModalCreate"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-700 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                <span>Tambah Kriteria Baru</span>
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($butirList->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $butirList->links() }}
        </div>
        @endif
    </div>

    <!-- Modals (Create/Edit Kriteria, Kelola Kelompok, Confirm Delete) -->
    @include('livewire.admin.partials.modal-kriteria')
</div>