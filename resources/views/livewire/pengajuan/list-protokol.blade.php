<div class="space-y-6 max-w-7xl mx-auto">
    <!-- 1. Header Banner -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="'List Protokol Penelitian (B01-04)'"
        :subtitle="'Daftar judul dan protokol riset yang telah ditelaah etiknya oleh Komite Etik.'">
        <x-slot:actions>
            @if ($suratPengajuan->isEditable())
            <button
                type="button"
                wire:click="tambahProtokol"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-xs">
                <span class="material-symbols-outlined text-[16px]">add</span>
                <span>Tambah Protokol</span>
            </button>
            @endif
        </x-slot:actions>
    </x-pengajuan.header>

    <!-- Alert Status -->
    @if (session('status'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between shadow-2xs">
        <span class="flex items-center gap-2 font-semibold">
            <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
            <span>{{ session('status') }}</span>
        </span>
    </div>
    @endif

    <!-- 3. Full-Width Table Layout (Clean Minimalist) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col">
        <!-- Table Control Toolbar -->
        <div class="p-4 sm:px-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <div class="relative w-full sm:w-80">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nomor protokol, judul, peneliti..."
                    class="w-full text-xs rounded-xl border border-slate-200 pl-9 pr-8 py-2 bg-white text-slate-800 placeholder-slate-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 shadow-2xs transition" />
                @if ($search !== '')
                <button
                    type="button"
                    wire:click="$set('search', '')"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs p-0.5 cursor-pointer"
                    title="Hapus pencarian">
                    <span class="material-symbols-outlined text-[15px]">close</span>
                </button>
                @endif
            </div>

            <div class="flex items-center gap-3 text-xs text-slate-500 w-full sm:w-auto justify-between sm:justify-end">
                <span class="font-medium">Total: <strong class="text-slate-900 font-mono">{{ $protokolList->count() }}</strong> Protokol</span>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-4 py-4 text-center w-12 whitespace-nowrap">#</th>
                        <th class="px-5 py-4 whitespace-nowrap">No. Protokol</th>
                        <th class="px-5 py-4 whitespace-nowrap">Jenis Telaah</th>
                        <th class="px-5 py-4">Judul Penelitian</th>
                        <th class="px-5 py-4 whitespace-nowrap">Peneliti Utama</th>
                        <th class="px-5 py-4">Institusi Asal</th>
                        <th class="px-5 py-4 whitespace-nowrap">Tanggal Masuk</th>
                        <th class="px-5 py-4 whitespace-nowrap">Dokumen</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Status Etik</th>
                        @if ($suratPengajuan->isEditable())
                        <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($protokolList as $index => $p)
                    <tr wire:key="protokol-{{ $p->id }}" class="hover:bg-slate-50/60 transition group">
                        <!-- Column 1: Index Number -->
                        <td class="px-4 py-4 text-center font-mono text-slate-400 text-xs">
                            {{ $index + 1 }}
                        </td>

                        <!-- Column 2: No. Protokol -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-mono font-bold text-slate-900 text-xs">
                                {{ $p->nomor_protokol }}
                            </span>
                        </td>

                        <!-- Column 3: Jenis Telaah -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($p->review_type === 'exempted')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-mono font-bold bg-blue-50 text-blue-700 border border-blue-200/80">
                                Exempted
                            </span>
                            @elseif ($p->review_type === 'expedited')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-mono font-bold bg-teal-50 text-teal-700 border border-teal-200/80">
                                Expedited
                            </span>
                            @elseif ($p->review_type === 'full_board')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-mono font-bold bg-purple-50 text-purple-700 border border-purple-200/80">
                                Full Board
                            </span>
                            @else
                            <span class="text-[10px] text-slate-400 italic">-</span>
                            @endif
                        </td>

                        <!-- Column 4: Judul Penelitian -->
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900 text-xs leading-snug group-hover:text-primary-700 transition">
                                {{ $p->judul }}
                            </div>
                        </td>

                        <!-- Column 5: Peneliti Utama -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-800 text-xs">
                                {{ $p->peneliti_utama }}
                            </div>
                        </td>

                        <!-- Column 6: Institusi Asal -->
                        <td class="px-5 py-4">
                            <div class="text-xs text-slate-700 font-medium">
                                {{ $p->institusi_asal ?: '-' }}
                            </div>
                        </td>

                        <!-- Column 7: Tanggal Masuk -->
                        <td class="px-5 py-4 text-slate-500 font-mono text-xs whitespace-nowrap">
                            {{ $p->tanggal_pengajuan?->format('d M Y') ?? '-' }}
                        </td>

                        <!-- Column 8: Dokumen Protokol -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($p->dokumen_path)
                            <a
                                href="{{ Storage::url($p->dokumen_path) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-700 border border-slate-200/80 hover:border-primary-200 text-xs font-medium transition group cursor-pointer"
                                title="{{ $p->dokumen_nama ?? 'Buka / Unduh Dokumen' }}">
                                <span class="material-symbols-outlined text-[16px] text-slate-500 group-hover:text-primary-600">description</span>
                                <span class="truncate max-w-[130px] font-semibold text-slate-800 group-hover:text-primary-700">{{ $p->dokumen_nama ?: 'Dokumen Protokol' }}</span>
                                @if ($p->dokumen_ukuran)
                                <span class="text-[10px] text-slate-400 font-mono">({{ $p->formatUkuranDokumen() }})</span>
                                @endif
                                <span class="material-symbols-outlined text-[13px] text-slate-400 group-hover:text-primary-500">open_in_new</span>
                            </a>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] text-slate-400 bg-slate-50 border border-slate-200/60 font-medium">
                                <span class="material-symbols-outlined text-[13px] text-slate-400">attachment_off</span>
                                <span>Belum ada</span>
                            </span>
                            @endif
                        </td>

                        <!-- Column 9: Status Etik -->
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            @if ($p->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Disetujui</span>
                            </span>
                            @elseif ($p->status === 'in_review')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Dalam Review</span>
                            </span>
                            @elseif ($p->status === 'revision')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                <span>Perbaikan</span>
                            </span>
                            @elseif ($p->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                <span>Ditolak</span>
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                {{ ucfirst($p->status) }}
                            </span>
                            @endif
                        </td>

                        <!-- Column 9: Aksi -->
                        @if ($suratPengajuan->isEditable())
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button
                                    type="button"
                                    wire:click="edit({{ $p->id }})"
                                    class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition inline-flex items-center gap-1 cursor-pointer"
                                    title="Edit Data">
                                    <span class="material-symbols-outlined text-[15px]">edit</span>
                                    <span>Edit</span>
                                </button>
                                <button
                                    type="button"
                                    wire:click="konfirmasiHapus({{ $p->id }})"
                                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer"
                                    title="Hapus Protokol">
                                    <span class="material-symbols-outlined text-[17px]">delete</span>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $suratPengajuan->isEditable() ? 10 : 9 }}" class="px-6 py-16 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-[24px]">description</span>
                            </div>
                            <div class="font-bold text-slate-800 text-sm">Belum Ada Protokol Penelitian</div>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                @if ($suratPengajuan->isEditable())
                                Klik tombol <strong class="text-slate-700">+ Tambah Protokol</strong> di atas untuk menambahkan daftar riset KEPK.
                                @else
                                Belum ada data protokol riset yang tercatat pada pengajuan ini.
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
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/25 transition-opacity animate-fade-in">
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
                    class="text-slate-400 hover:text-slate-600 p-1 rounded-lg transition">
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
                                class="w-full text-xs rounded-xl border-slate-300 px-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs" />
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
                                class="w-full text-xs rounded-xl border-slate-300 px-3 py-2 bg-white text-slate-800 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs">
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
                            class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-white text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs resize-y"></textarea>
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
                                class="w-full text-xs rounded-xl border-slate-300 px-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs" />
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
                                class="w-full text-xs rounded-xl border-slate-300 px-3 py-2 bg-white text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs" />
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
                                class="w-full text-xs rounded-xl border-slate-300 px-3 py-2 bg-white text-slate-800 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs" />
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
                                class="w-full text-xs rounded-xl border-slate-300 px-3 py-2 bg-white text-slate-800 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs">
                                <option value="approved">Disetujui (Approved / Ethical Clearance)</option>
                                <option value="in_review">Dalam Telaah (In Review)</option>
                                <option value="revision">Perbaikan (Revision Required)</option>
                                <option value="rejected">Ditolak (Rejected)</option>
                            </select>
                            @error('status_protokol') <span class="text-rose-600 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Upload Dokumen Protokol -->
                    <div class="border-t border-slate-100 pt-4 space-y-2">
                        <label class="block font-semibold text-slate-700">
                            Dokumen / Berkas Protokol Riset
                            <span class="text-[11px] font-normal text-slate-400 block mt-0.5">
                                Lampirkan berkas protokol, proposal riset, atau surat izin telaah etik (PDF, DOCX, ZIP, maks. 20MB).
                            </span>
                        </label>

                        @if ($existingDokumenPath && ! $hapusDokumenLama && ! $dokumen)
                        <!-- Berkas yang tersimpan sebelumnya -->
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">description</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-900 text-xs truncate" title="{{ $existingDokumenNama }}">
                                        {{ $existingDokumenNama }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        @if ($existingDokumenUkuran)
                                            @if ($existingDokumenUkuran >= 1048576)
                                                {{ number_format($existingDokumenUkuran / 1048576, 2) }} MB
                                            @elseif ($existingDokumenUkuran >= 1024)
                                                {{ number_format($existingDokumenUkuran / 1024, 1) }} KB
                                            @else
                                                {{ $existingDokumenUkuran }} B
                                            @endif
                                            • Tersimpan
                                        @else
                                            Berkas tersimpan
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a
                                    href="{{ Storage::url($existingDokumenPath) }}"
                                    target="_blank"
                                    class="px-2.5 py-1 text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 rounded-lg transition inline-flex items-center gap-1 cursor-pointer">
                                    <span class="material-symbols-outlined text-[14px]">visibility</span>
                                    <span>Lihat</span>
                                </a>
                                <button
                                    type="button"
                                    wire:click="hapusDokumenSaatIni"
                                    class="px-2.5 py-1 text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 rounded-lg transition inline-flex items-center gap-1 cursor-pointer"
                                    title="Hapus berkas ini">
                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                        @endif

                        @if ($dokumen)
                        <!-- Berkas baru yang dipilih -->
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-emerald-900 text-xs truncate">
                                        {{ $dokumen->getClientOriginalName() }}
                                    </div>
                                    <div class="text-[10px] text-emerald-600 font-mono mt-0.5">
                                        {{ number_format(($dokumen->getSize() ?: 0) / 1024, 1) }} KB (Siap disimpan)
                                    </div>
                                </div>
                            </div>
                            <button
                                type="button"
                                wire:click="batalUploadDokumen"
                                class="p-1 text-slate-400 hover:text-rose-600 transition cursor-pointer"
                                title="Batal pilih berkas">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                        @endif

                        @if (! $dokumen && (! $existingDokumenPath || $hapusDokumenLama))
                        <!-- Input Upload Dropzone -->
                        <div>
                            <label class="relative flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-300 hover:border-primary-500 rounded-xl bg-slate-50/50 hover:bg-primary-50/20 cursor-pointer transition group">
                                <input
                                    type="file"
                                    wire:model="dokumen"
                                    class="sr-only"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.jpeg,.png" />
                                <div class="flex flex-col items-center text-center">
                                    <span class="material-symbols-outlined text-[26px] text-slate-400 group-hover:text-primary-600 transition mb-1">
                                        cloud_upload
                                    </span>
                                    <span class="text-xs font-semibold text-slate-700 group-hover:text-primary-700">
                                        Pilih dokumen atau seret ke sini
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">
                                        Format: PDF, DOC, DOCX, XLS, XLSX, ZIP, Gambar (Maks. 20MB)
                                    </span>
                                </div>
                            </label>
                        </div>
                        @endif

                        <!-- Upload Loading State Indicator -->
                        <div wire:loading wire:target="dokumen" class="text-xs text-primary-600 flex items-center gap-2 pt-1 font-medium">
                            <span class="inline-block animate-spin material-symbols-outlined text-[16px]">progress_activity</span>
                            <span>Mengunggah dokumen ke server...</span>
                        </div>

                        @error('dokumen')
                        <span class="text-rose-600 text-[10px] block font-medium mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer Actions -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        wire:click="tutupModal"
                        class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-xl text-xs transition shadow-2xs cursor-pointer">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold rounded-xl text-xs transition shadow-2xs flex items-center gap-1.5 cursor-pointer">
                        <span wire:loading.remove wire:target="simpan">{{ $editingId ? 'Simpan Perubahan' : 'Tambah Protokol' }}</span>
                        <span wire:loading wire:target="simpan">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Modal Konfirmasi Hapus Protokol -->
    <x-confirm-modal
        :show="$showDeleteModal"
        title="Hapus Protokol Riset?"
        type="danger"
        icon="delete"
        confirmText="Ya, Hapus Protokol"
        cancelText="Batalkan"
        onConfirm="eksekusiHapus"
        onCancel="batalHapus">
        <p>
            Apakah Anda yakin ingin menghapus data protokol riset <strong class="text-slate-900 font-semibold">{{ $selectedDeleteJudul }}</strong>?
        </p>
        <p class="text-slate-500 text-xs mt-1">
            Data protokol yang dihapus tidak dapat dipulihkan kembali.
        </p>
    </x-confirm-modal>
</div>