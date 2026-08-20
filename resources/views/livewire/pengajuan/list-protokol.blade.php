<div class="space-y-6 max-w-7xl mx-auto">
    <!-- 1. Header Banner -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="'List Protokol Penelitian (B01-04)'"
        :subtitle="'Daftar judul dan protokol riset yang telah ditelaah etiknya oleh Komite Etik.'"
    >
        <x-slot:actions>
            @if ($suratPengajuan->isEditable())
                <button
                    type="button"
                    wire:click="tambahProtokol"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-xs"
                >
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Tambah Protokol</span>
                </button>
            @endif
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl text-xs font-semibold transition shadow-xs" wire:navigate>
                &larr; Detail Pengajuan
            </a>
        </x-slot:actions>
    </x-pengajuan.header>

    <!-- Alert Status -->
    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between shadow-2xs">
            <span class="flex items-center gap-1.5 font-semibold">
                <span>✓</span>
                <span>{{ session('status') }}</span>
            </span>
        </div>
    @endif

    <!-- 3. Full-Width Table Layout -->
    <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
        <!-- Table Control Toolbar -->
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nomor protokol, judul, peneliti..."
                        class="w-full text-xs rounded-lg border-slate-300 pl-8 pr-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                    />
                    <span class="absolute left-2.5 top-2.5 text-slate-400 text-xs">🔍</span>
                </div>
            </div>

            <div class="flex items-center gap-3 text-xs text-slate-500 w-full sm:w-auto justify-between sm:justify-end">
                <span class="font-medium">Total Protokol: <strong class="text-slate-900">{{ $protokolList->count() }}</strong></span>
                @if ($suratPengajuan->isEditable())
                    <button
                        type="button"
                        wire:click="tambahProtokol"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#174668] hover:bg-[#133e5f] transition shadow-2xs"
                    >
                        <span>+ Tambah Protokol</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="bg-slate-100/80 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">#</th>
                        <th class="px-4 py-3.5 w-44">No. Protokol & Jenis</th>
                        <th class="px-4 py-3.5">Judul Penelitian</th>
                        <th class="px-4 py-3.5 w-44">Peneliti Utama</th>
                        <th class="px-4 py-3.5 w-32">Tanggal Masuk</th>
                        <th class="px-4 py-3.5 w-28 text-center">Status Etik</th>
                        @if ($suratPengajuan->isEditable())
                            <th class="px-4 py-3.5 text-center w-24">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($protokolList as $index => $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Column 1: Index -->
                            <td class="px-4 py-3.5 text-center font-mono text-slate-400 font-semibold">
                                {{ $index + 1 }}
                            </td>

                            <!-- Column 2: No. Protokol & Jenis Telaah -->
                            <td class="px-4 py-3.5 align-top">
                                <div class="font-mono font-bold text-[#174668] text-xs">
                                    {{ $p->nomor_protokol }}
                                </div>
                                <div class="mt-1">
                                    @if ($p->review_type === 'exempted')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            Exempted
                                        </span>
                                    @elseif ($p->review_type === 'expedited')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                            Expedited
                                        </span>
                                    @elseif ($p->review_type === 'full_board')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Full Board
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">-</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 3: Judul Penelitian & Institusi -->
                            <td class="px-4 py-3.5 align-top space-y-1">
                                <div class="font-semibold text-slate-900 leading-snug">
                                    {{ $p->judul }}
                                </div>
                                @if ($p->institusi_asal)
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1">
                                        <span>🏛️</span>
                                        <span>{{ $p->institusi_asal }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Column 4: Peneliti Utama -->
                            <td class="px-4 py-3.5 align-top">
                                <div class="font-medium text-slate-800 flex items-center gap-1.5">
                                    <span>👤</span>
                                    <span>{{ $p->peneliti_utama }}</span>
                                </div>
                            </td>

                            <!-- Column 5: Tanggal Masuk -->
                            <td class="px-4 py-3.5 align-top text-slate-500 font-mono text-[11px]">
                                {{ $p->tanggal_pengajuan?->format('d M Y') ?? '-' }}
                            </td>

                            <!-- Column 6: Status Etik -->
                            <td class="px-4 py-3.5 align-top text-center">
                                @if ($p->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ✓ Disetujui
                                    </span>
                                @elseif ($p->status === 'in_review')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Dalam Review
                                    </span>
                                @elseif ($p->status === 'revision')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                        Perbaikan
                                    </span>
                                @elseif ($p->status === 'rejected')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Column 7: Aksi -->
                            @if ($suratPengajuan->isEditable())
                                <td class="px-4 py-3.5 align-top text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button
                                            type="button"
                                            wire:click="edit({{ $p->id }})"
                                            class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded text-[11px] transition"
                                            title="Edit Data"
                                        >
                                            ✏️ Edit
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="hapus({{ $p->id }})"
                                            wire:confirm="Yakin ingin menghapus protokol riset ini?"
                                            class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded text-[11px] transition"
                                            title="Hapus Protokol"
                                        >
                                            🗑️ Hapus
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $suratPengajuan->isEditable() ? 7 : 6 }}" class="px-4 py-12 text-center text-slate-400">
                                <div class="text-3xl mb-2">📑</div>
                                <div class="font-bold text-slate-700 text-sm">Belum Ada Protokol Penelitian</div>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                    @if ($suratPengajuan->isEditable())
                                        Klik tombol <strong>"+ Tambah Protokol"</strong> di atas untuk menambahkan daftar protokol riset yang telah ditelaah oleh KEPK.
                                    @else
                                        Belum ada data protokol riset yang terdaftar pada pengajuan ini.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. Interactive Modal Dialog for Adding / Editing Protocol -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-fade-in">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-xl overflow-hidden animate-scale-in">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">
                            {{ $editingId ? 'Edit Protokol Penelitian' : 'Tambah Protokol Penelitian Baru' }}
                        </h3>
                        <p class="text-[11px] text-slate-500">Lengkapi informasi protokol riset yang diajukan ke KEPK.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="tutupModal"
                        class="text-slate-400 hover:text-slate-600 p-1 rounded-lg transition"
                    >
                        ✕
                    </button>
                </div>

                <!-- Modal Body / Form -->
                <form wire:submit="simpan">
                    <div class="p-6 space-y-4 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nomor Protokol -->
                            <div>
                                <label for="nomor_protokol" class="block font-semibold text-slate-700 mb-1">
                                    Nomor Protokol <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="nomor_protokol"
                                    id="nomor_protokol"
                                    placeholder="Contoh: PR-2026-001"
                                    class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                                />
                                @error('nomor_protokol') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <!-- Jenis Telaah (Review Type) -->
                            <div>
                                <label for="review_type" class="block font-semibold text-slate-700 mb-1">
                                    Jenis Telaah Etik <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    wire:model="review_type"
                                    id="review_type"
                                    class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 bg-white text-slate-800 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                                >
                                    <option value="expedited">Expedited (Telaah Cepat)</option>
                                    <option value="full_board">Full Board (Telaah Lengkap Panel)</option>
                                    <option value="exempted">Exempted (Bebas Telaah)</option>
                                </select>
                                @error('review_type') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Judul Penelitian -->
                        <div>
                            <label for="judul" class="block font-semibold text-slate-700 mb-1">
                                Judul Riset / Penelitian <span class="text-rose-500">*</span>
                            </label>
                            <textarea
                                wire:model="judul"
                                id="judul"
                                rows="3"
                                placeholder="Tuliskan judul lengkap protokol penelitian..."
                                class="w-full text-xs rounded-lg border-slate-300 p-2.5 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs resize-y"
                            ></textarea>
                            @error('judul') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Peneliti Utama -->
                            <div>
                                <label for="peneliti_utama" class="block font-semibold text-slate-700 mb-1">
                                    Peneliti Utama / Principal Investigator <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="peneliti_utama"
                                    id="peneliti_utama"
                                    placeholder="Nama & gelar peneliti..."
                                    class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                                />
                                @error('peneliti_utama') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <!-- Institusi Asal -->
                            <div>
                                <label for="institusi_asal" class="block font-semibold text-slate-700 mb-1">
                                    Institusi Asal Peneliti
                                </label>
                                <input
                                    type="text"
                                    wire:model="institusi_asal"
                                    id="institusi_asal"
                                    placeholder="Contoh: FKIK UMY"
                                    class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                                />
                                @error('institusi_asal') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Tanggal Pengajuan -->
                            <div>
                                <label for="tanggal_pengajuan" class="block font-semibold text-slate-700 mb-1">
                                    Tanggal Masuk / Diajukan
                                </label>
                                <input
                                    type="date"
                                    wire:model="tanggal_pengajuan"
                                    id="tanggal_pengajuan"
                                    class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 bg-white text-slate-800 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                                />
                                @error('tanggal_pengajuan') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status Etik -->
                            <div>
                                <label for="status_protokol" class="block font-semibold text-slate-700 mb-1">
                                    Status Keputusan Etik <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    wire:model="status_protokol"
                                    id="status_protokol"
                                    class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 bg-white text-slate-800 focus:border-[#174668] focus:ring-1 focus:ring-[#174668] shadow-2xs"
                                >
                                    <option value="approved">Disetujui (Approved / Ethical Clearance)</option>
                                    <option value="in_review">Dalam Telaah (In Review)</option>
                                    <option value="revision">Perbaikan (Revision Required)</option>
                                    <option value="rejected">Ditolak (Rejected)</option>
                                </select>
                                @error('status_protokol') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer Actions -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button
                            type="button"
                            wire:click="tutupModal"
                            class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-lg text-xs transition shadow-2xs"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-[#174668] hover:bg-[#133e5f] text-white font-bold rounded-lg text-xs transition shadow-2xs flex items-center gap-1.5"
                        >
                            <span wire:loading.remove wire:target="simpan">{{ $editingId ? '✓ Simpan Perubahan' : '➕ Tambah Protokol' }}</span>
                            <span wire:loading wire:target="simpan">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
