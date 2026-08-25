<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Header Banner -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="'Dokumen & Lampiran Berkas Pengajuan'"
        :subtitle="'Unggah berkas SK pendirian KEPK, pedoman standar operasional (SOP), sertifikat pelatihan, dan dokumen bukti dukung akreditasi.'">
        <x-slot:actions>
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5" wire:navigate>
                <span>&larr;</span>
                <span>Detail Pengajuan</span>
            </a>
        </x-slot:actions>
    </x-pengajuan.header>

    <!-- 2. Flash Status Message -->
    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                <span class="font-semibold">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- 3. Two-Column Workspace Layout (1/3 Left, 2/3 Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Left Column: Upload Form & Guidelines (1/3 width) -->
        <div class="space-y-6">
            <!-- Card 1: Upload Form -->
            @if ($suratPengajuan->isEditable())
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="material-symbols-outlined text-primary-700 text-[20px]">upload_file</span>
                        <h2 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">Unggah Berkas Baru</h2>
                    </div>

                    <form wire:submit="unggah" class="space-y-4 text-xs">
                        <div class="space-y-2">
                            <label for="file" class="block font-semibold text-slate-700">Pilih Berkas Komputer</label>
                            <input
                                type="file"
                                wire:model="file"
                                id="file"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 file:transition file:cursor-pointer border border-slate-200 rounded-xl p-1 bg-slate-50/50" />
                            <div class="text-[11px] text-slate-400 leading-relaxed">
                                Format: <strong>PDF, Word, Excel, ZIP, Gambar</strong> (Maksimal 10 MB per file).
                            </div>
                            @error('file')
                                <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer">
                                <span wire:loading.remove wire:target="file,unggah" class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">upload</span>
                                    <span>Upload Lampiran</span>
                                </span>
                                <span wire:loading wire:target="file,unggah" class="flex items-center gap-1.5">
                                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Mengunggah Berkas...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Card 2: Panduan Berkas Akreditasi -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-3.5 text-xs">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="material-symbols-outlined text-primary-700 text-[18px]">info</span>
                    <h3 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">Panduan Dokumen Wajib</h3>
                </div>
                <ul class="space-y-2.5 text-slate-600">
                    <li class="flex items-start gap-2">
                        <span class="text-primary-700 font-bold mt-0.5">•</span>
                        <span><strong>SK Pendirian KEPK</strong> dari Pimpinan Institusi / Universitas.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-primary-700 font-bold mt-0.5">•</span>
                        <span><strong>Buku Standar Operasional Prosedur (SOP)</strong> KEPK yang masih berlaku.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-primary-700 font-bold mt-0.5">•</span>
                        <span><strong>Sertifikat GCP / Etik Dasar</strong> bagi seluruh anggota KEPK.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-primary-700 font-bold mt-0.5">•</span>
                        <span><strong>Struktur Organisasi</strong> dan Uraian Tugas Pengurus KEPK.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Column: Document List Table / Grid (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/60">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">folder</span>
                        </div>
                        <div>
                            <h3 class="font-display text-sm font-bold text-slate-900 leading-tight">Daftar Dokumen Lampiran</h3>
                            <p class="text-[11px] text-slate-500">Berkas pendukung yang telah diunggah ke server.</p>
                        </div>
                    </div>
                    <span class="bg-slate-100 text-slate-700 font-mono text-xs px-3 py-1 rounded-lg font-bold border border-slate-200 self-start sm:self-center shadow-2xs">
                        {{ $daftarDokumen->count() }} Dokumen Terunggah
                    </span>
                </div>

                <!-- Document Items List -->
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse ($daftarDokumen as $index => $doc)
                        <div class="p-5 sm:px-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/60 transition group">
                            <div class="flex items-start gap-3.5 overflow-hidden">
                                <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                                    <span class="material-symbols-outlined text-[22px]">description</span>
                                </div>
                                <div class="overflow-hidden space-y-1">
                                    <div class="font-bold text-slate-900 text-xs leading-snug group-hover:text-primary-700 transition truncate" title="{{ $doc->nama_asli }}">
                                        {{ $doc->nama_asli }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-mono flex flex-wrap items-center gap-2">
                                        <span class="font-semibold text-slate-600">{{ $doc->formatUkuran() }}</span>
                                        <span>•</span>
                                        <span>Diunggah: {{ $doc->created_at->format('d M Y, H:i') }}</span>
                                        @if ($doc->pengunggah)
                                            <span>•</span>
                                            <span class="text-slate-500">Oleh: {{ $doc->pengunggah->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                <a
                                    href="{{ Storage::url($doc->path) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition shadow-2xs">
                                    <span class="material-symbols-outlined text-[15px]">download</span>
                                    <span>Buka / Unduh</span>
                                </a>

                                @if ($suratPengajuan->isEditable())
                                    <button
                                        type="button"
                                        wire:click="hapus({{ $doc->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus berkas lampiran '{{ $doc->nama_asli }}' ini?"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition"
                                        title="Hapus Dokumen">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center text-slate-400 space-y-3">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                <span class="material-symbols-outlined text-[32px]">folder_off</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Belum ada dokumen lampiran yang diunggah.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Gunakan panel di sebelah kiri untuk mengunggah SK pendirian, SOP, dan berkas bukti dukung.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>