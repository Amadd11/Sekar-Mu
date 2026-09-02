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

    <!-- 5. Modal Dialog: Tambah / Edit Kriteria & Acuan -->
    @if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-kriteria-title" role="dialog" aria-modal="true">
        <!-- Light Backdrop -->
        <div class="fixed inset-0 bg-slate-900/25 transition-opacity" wire:click="tutupModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200/80">
                <!-- Modal Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl select-none">📝</span>
                        <div>
                            <h3 class="font-display font-bold text-base text-slate-900 leading-tight" id="modal-kriteria-title">
                                {{ $isEditing ? 'Revisi Kriteria & Acuan Standar' : 'Tambah Kriteria Butir Baru' }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $isEditing ? 'Perbarui formulasi pertanyaan, standar acuan, parameter penilaian, atau dokumen bukti.' : 'Definisikan butir asesmen baru ke dalam instrumen evaluasi akreditasi.' }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="tutupModal"
                        class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form wire:submit="simpanKriteria">
                    <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto custom-scrollbar text-xs">
                        <!-- 1. Bagian & Kelompok Evaluasi -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Bagian Standar (A-E) <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model.live="bagian_evaluasi_id"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-800">
                                    @foreach ($daftarBagian as $b)
                                    <option value="{{ $b->id }}">Bagian {{ $b->kode }}: {{ $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Kelompok Kategori Acuan <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model="kelompok_evaluasi_id"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-800">
                                    <option value="">-- Pilih Kelompok --</option>
                                    @foreach ($modalKelompokOptions as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kelompok_evaluasi_id') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- 2. Kode Butir & Kritis Switch -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center pb-4 border-b border-slate-100">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Kode Butir (Contoh: A1.1, B2.3)
                                </label>
                                <input
                                    type="text"
                                    wire:model="kode"
                                    placeholder="Otomatis jika dikosongkan..."
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs font-mono font-bold uppercase" />
                                @error('kode') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- 3. Bunyi Pertanyaan / Kriteria -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Bunyi Kriteria / Pertanyaan Evaluasi <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                wire:model="pertanyaan"
                                rows="3"
                                placeholder="Tuliskan pertanyaan butir evaluasi secara jelas dan spesifik..."
                                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs leading-relaxed"></textarea>
                            @error('pertanyaan') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- 4. Acuan Standar & Parameter Penilaian -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Standar Acuan (WHO-CIOMS / KNEPK)
                                </label>
                                <input
                                    type="text"
                                    wire:model="standar"
                                    placeholder="Contoh: WHO-CIOMS 2016 Pedoman 1"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs" />
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Dokumen Acuan yang Disyaratkan
                                </label>
                                <input
                                    type="text"
                                    wire:model="evidence_required"
                                    placeholder="Contoh: SK KEPK, SOP Sidang, Sertifikat GCP"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs" />
                            </div>
                        </div>

                        <!-- 5. Parameter Penilaian Lengkap -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Panduan Parameter Penilaian (Kriteria Skor A, B, C)
                            </label>
                            <textarea
                                wire:model="parameter"
                                rows="3"
                                placeholder="Panduan bagi pemohon & asesor dalam menentukan kepatuhan..."
                                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs leading-relaxed"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer Actions -->
                    <div class="p-4 sm:px-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-2xl">
                        <button
                            type="button"
                            wire:click="tutupModal"
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200/70 transition">
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer">
                            <span wire:loading.remove class="flex items-center gap-1.5">
                                <span>{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Kriteria' }}</span>
                                <span class="material-symbols-outlined text-[16px]">save</span>
                            </span>
                            <span wire:loading class="flex items-center gap-1.5">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Menyimpan...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- 6. Modal Dialog: Kelola Kelompok Acuan Standar -->
    @if ($showKelompokModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-kelompok-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 transition-opacity" wire:click="tutupModalKelompok"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-700 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[22px]">category</span>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-base text-slate-900 leading-tight" id="modal-kelompok-title">
                                Kelola Kelompok Acuan Standar
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Tambah baru atau hapus kategori pengelompokan butir standar akreditasi.
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="tutupModalKelompok"
                        class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-6 text-xs max-h-[75vh] overflow-y-auto">
                    <!-- Form Tambah Baru -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                        <div class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-primary-700">add_circle</span>
                            <span>Tambah Kelompok Acuan Baru</span>
                        </div>

                        <form wire:submit="simpanKelompok" class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-600 mb-1">
                                        Bagian Standar <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        wire:model="kelompok_bagian_id"
                                        class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-500/20 shadow-2xs text-slate-800 bg-white">
                                        @foreach ($daftarBagian as $b)
                                        <option value="{{ $b->id }}">Bagian {{ $b->kode }}: {{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 mb-1">
                                        Nama Kelompok Standar <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="kelompok_nama"
                                        placeholder="Nama kelompok..."
                                        class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-500/20 shadow-2xs bg-white" />
                                    @error('kelompok_nama') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 mb-1">
                                        Urutan
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="number"
                                            wire:model="kelompok_urutan"
                                            min="1"
                                            class="w-20 text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-1 focus:ring-primary-500/20 shadow-2xs font-mono font-bold bg-white" />
                                        <button
                                            type="submit"
                                            class="flex-1 px-3 py-2 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl transition cursor-pointer shadow-2xs">
                                            + Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Daftar Kelompok & Tombol Hapus -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between pb-1">
                            <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">
                                Daftar Kelompok Acuan ({{ $semuaKelompok->count() }} Kelompok)
                            </span>
                            <span class="text-[11px] text-slate-400">Kelompok dengan 0 butir dapat dihapus langsung</span>
                        </div>

                        <div class="border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-100 max-h-80 overflow-y-auto">
                            @forelse ($semuaKelompok as $kel)
                            <div class="p-3 bg-white hover:bg-slate-50/70 transition flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5 overflow-hidden flex-1">
                                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-mono text-xs flex items-center justify-center font-bold shrink-0">
                                        {{ $kel->bagian?->kode ?? '-' }}
                                    </span>
                                    <div class="overflow-hidden">
                                        <div class="font-bold text-slate-900 truncate">{{ $kel->nama }}</div>
                                        <div class="text-[11px] text-slate-400">Bagian {{ $kel->bagian?->kode }}: {{ $kel->bagian?->nama }} • Urutan #{{ $kel->urutan }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="font-mono text-[11px] px-2 py-0.5 rounded-lg border {{ $kel->butir_count > 0 ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                        {{ $kel->butir_count }} Butir
                                    </span>

                                    <button
                                        type="button"
                                        wire:click="konfirmasiHapusKelompok({{ $kel->id }})"
                                        class="p-1.5 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer flex items-center justify-center"
                                        title="Hapus Kelompok Standar">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center text-slate-400 italic">
                                Belum ada kelompok acuan standar.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:px-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl">
                    <button
                        type="button"
                        wire:click="tutupModalKelompok"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200/70 transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reusable Modal Konfirmasi Hapus Kriteria & Kelompok -->
    <x-confirm-modal
        :show="$showDeleteModal"
        title="Hapus Data Standar?"
        type="danger"
        icon="delete_forever"
        confirmText="Ya, Hapus Data"
        cancelText="Batalkan"
        onConfirm="prosesHapus"
        onCancel="batalHapus"
    >
        <p>
            Apakah Anda yakin ingin menghapus <strong class="text-slate-900 font-semibold">{{ $deletingTitle }}</strong>?
        </p>
        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-[11px] font-semibold text-left flex items-start gap-2 mt-2">
            <span class="material-symbols-outlined text-amber-600 text-[16px] shrink-0 mt-0.5">warning</span>
            <span>Tindakan ini tidak dapat dibatalkan. Data terkait akan dihapus secara permanen.</span>
        </div>
    </x-confirm-modal>
</div>