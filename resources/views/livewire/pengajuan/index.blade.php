<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Full Header Card (Matching Evaluasi Diri Design) -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
        <!-- Top Gradient Accent Bar -->
        <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>

        <div class="p-6 sm:p-7 relative z-10 space-y-4">
            <!-- Top Meta Strip -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="bg-primary-50 text-primary-700 font-display text-xs px-3 py-1 rounded-lg font-bold border border-primary-200/70 shadow-2xs flex items-center gap-1.5">
                        <span class="text-sm">🌸</span>
                        <span>Borang B01: Akreditasi KEPK</span>
                    </span>
                    <span class="bg-slate-100 text-slate-700 font-mono text-xs px-3 py-1 rounded-lg font-bold border border-slate-200 shadow-2xs">
                        {{ $pengajuanList->total() }} Permohonan Terdaftar
                    </span>
                </div>
            </div>

            <!-- Main Title & Action Buttons Row -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
                <div class="space-y-1 max-w-3xl">
                    <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        Surat Pengajuan & Berkas Akreditasi
                    </h1>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                        Kelola seluruh berkas permohonan akreditasi, instrumen borang mandiri, dan pantau status asesmen KEPK secara terpadu.
                    </p>
                </div>

                <!-- Action Button -->
                <div class="flex items-center gap-2.5 w-full sm:w-auto shrink-0">
                    <button
                        type="button"
                        wire:click="bukaModalCreate"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>Buat Pengajuan Baru</span>
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

    <!-- Filters & Search -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <div class="w-full sm:flex-1 relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nomor berkas, nama institusi, kota..."
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 ps-10 pe-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
            />
            <span class="material-symbols-outlined text-slate-400 absolute left-3 top-2.5 text-[18px]">search</span>
        </div>

        <div class="w-full sm:w-auto">
            <select
                wire:model.live="statusFilter"
                class="w-full sm:w-52 text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium"
            >
                <option value="">Semua Status Berkas</option>
                <option value="in_progress">Proses Evaluasi</option>
                <option value="approved">Terakreditasi</option>
                <option value="rejected">Tidak Lolos</option>
            </select>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-6 py-4 whitespace-nowrap">No. Berkas</th>
                        <th class="px-6 py-4">Institusi & Pemohon</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status Pengajuan</th>
                        <th class="px-6 py-4 whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($pengajuanList as $item)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200 text-xs">
                                    {{ $item->formatted_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-xs leading-snug group-hover:text-primary-700 transition">
                                    {{ $item->formulirAplikasi->nama_institusi ?? 'Draft Permohonan Baru' }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span>{{ $item->kepk->name ?? '-' }}</span>
                                    <span>•</span>
                                    <span class="text-slate-400">{{ $item->formulirAplikasi->kota ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-pengajuan.status-badge :status="$item->status" />
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs whitespace-nowrap">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <a
                                    href="{{ route('pengajuan.show', $item) }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-2xs"
                                    wire:navigate
                                >
                                    <span>Buka Berkas</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-400 space-y-3">
                                <span class="material-symbols-outlined text-slate-300 text-[48px] block">folder_off</span>
                                <p class="text-sm font-semibold text-slate-600">Belum ada berkas surat pengajuan yang dibuat.</p>
                                <button
                                    type="button"
                                    wire:click="bukaModalCreate"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-700 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition"
                                >
                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                    <span>Buat Pengajuan Sekarang</span>
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengajuanList->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $pengajuanList->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Dialog: Buat Pengajuan Baru -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Light Backdrop -->
            <div class="fixed inset-0 bg-slate-900/25 transition-opacity" wire:click="tutupModalCreate"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200/80">
                    <!-- Modal Header -->
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl select-none">🌸</span>
                            <div>
                                <h3 class="font-display font-bold text-base text-slate-900 leading-tight" id="modal-title">
                                    Buat Surat Pengajuan Baru (B01-01)
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Lengkapi identitas institusi untuk memulai asesmen akreditasi KEPK.
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="tutupModalCreate"
                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                        >
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <!-- Modal Body Form -->
                    <form wire:submit="simpanPengajuan">
                        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto custom-scrollbar text-xs">
                            <!-- 1. Tujuan KEPK -->
                            <div class="space-y-2 pb-4 border-b border-slate-100">
                                <label for="modal_kepk_id" class="block font-bold text-slate-700">
                                    Komisi Etik Tujuan Pengajuan <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model="kepk_id"
                                    id="modal_kepk_id"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-800"
                                >
                                    <option value="">-- Pilih KEPK --</option>
                                    @foreach ($daftarKepk as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }} ({{ $k->institusi->name ?? '-' }})</option>
                                    @endforeach
                                </select>
                                @error('kepk_id') <span class="text-red-500 text-[11px] block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- 2. Identitas Institusi -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-primary-700 text-[18px]">apartment</span>
                                    <span class="font-display font-bold text-slate-800 uppercase tracking-wider text-[11px]">Identitas Institusi Pengusul</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="sm:col-span-2">
                                        <label for="modal_nama_institusi" class="block font-semibold text-slate-700 mb-1">
                                            Nama Institusi / Fakultas <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            wire:model="nama_institusi"
                                            id="modal_nama_institusi"
                                            placeholder="Contoh: Fakultas Kedokteran dan Ilmu Kesehatan UMY"
                                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                                        />
                                        @error('nama_institusi') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="modal_singkatan" class="block font-semibold text-slate-700 mb-1">Singkatan / Akronim</label>
                                        <input
                                            type="text"
                                            wire:model="singkatan"
                                            id="modal_singkatan"
                                            placeholder="Contoh: FKIK-UMY"
                                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                                        />
                                    </div>

                                    <div>
                                        <label for="modal_kota" class="block font-semibold text-slate-700 mb-1">Kota / Kabupaten</label>
                                        <input
                                            type="text"
                                            wire:model="kota"
                                            id="modal_kota"
                                            placeholder="Contoh: Yogyakarta"
                                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                                        />
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="modal_alamat" class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                        <textarea
                                            wire:model="alamat"
                                            id="modal_alamat"
                                            rows="2"
                                            placeholder="Alamat kantor sekretariat KEPK..."
                                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                                        ></textarea>
                                    </div>

                                    <div>
                                        <label for="modal_telepon" class="block font-semibold text-slate-700 mb-1">Telepon / WhatsApp</label>
                                        <input
                                            type="text"
                                            wire:model="telepon"
                                            id="modal_telepon"
                                            placeholder="Contoh: 0274-387656"
                                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                                        />
                                    </div>

                                    <div>
                                        <label for="modal_email" class="block font-semibold text-slate-700 mb-1">Email Resmi KEPK</label>
                                        <input
                                            type="email"
                                            wire:model="email"
                                            id="modal_email"
                                            placeholder="Contoh: kepk@umy.ac.id"
                                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer Actions -->
                        <div class="p-4 sm:px-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-2xl">
                            <button
                                type="button"
                                wire:click="tutupModalCreate"
                                class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200/70 transition"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                            >
                                <span wire:loading.remove class="flex items-center gap-1.5">
                                    <span>Simpan & Buka Pengajuan</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
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
</div>
