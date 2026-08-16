<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Top Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl select-none">📁</span>
                <h1 class="text-lg font-bold text-slate-900">
                    Manajemen Dokumen Lampiran (Documents)
                </h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Pengajuan: <strong class="text-slate-800">{{ $application->information->name ?? 'Pengajuan Etik' }}</strong> • No: #APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('applications.protocols', $application) }}" class="btn-outline btn-sm gap-1 text-xs" wire:navigate>
                &larr; List Protokol
            </a>
            <a href="{{ route('applications.show', $application) }}" class="btn-primary btn-sm gap-1 text-xs" wire:navigate>
                <span>Ringkasan & Submit &rarr;</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upload Card (Left 1 Col) -->
        <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-900">Unggah Berkas Baru</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Mendukung format PDF, DOCX, XLS, ZIP, Gambar (Maks. 20MB)</p>
            </div>

            <form wire:submit="upload" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">
                        Pilih File Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input
                        wire:model="file"
                        type="file"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer border border-slate-200 rounded-lg p-1"
                    />
                    @error('file')
                        <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                    @enderror

                    <!-- Upload Progress Indicator -->
                    <div wire:loading wire:target="file" class="text-[11px] text-teal-700 mt-2 flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5 text-teal-700" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Mengunggah berkas sementara...</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold rounded-lg shadow-2xs transition"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="upload">📤 Simpan Dokumen</span>
                        <span wire:loading wire:target="upload">Memproses...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Document List Table (Right 2 Cols) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Berkas Lampiran Tersimpan</h3>
                    <p class="text-[11px] text-slate-400">Total: {{ $documents->count() }} berkas terunggah.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                        <tr>
                            <th class="px-5 py-3">Nama Berkas</th>
                            <th class="px-5 py-3">Ukuran & Format</th>
                            <th class="px-5 py-3">Diunggah Oleh</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($documents as $doc)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-3.5">
                                    <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                        <span>📄</span>
                                        <span>{{ $doc->original_name }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        {{ $doc->created_at->format('d M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-[11px]">
                                    <span class="font-mono">{{ $doc->readableSize() }}</span>
                                    <span class="block text-[10px] text-slate-400 uppercase truncate max-w-[120px]">{{ $doc->mime_type }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-[11px]">
                                    {{ $doc->uploader->name ?? 'User' }}
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-1">
                                    <button
                                        type="button"
                                        wire:click="download({{ $doc->id }})"
                                        class="px-2.5 py-1 text-teal-700 bg-teal-50 hover:bg-teal-100 rounded text-[11px] font-medium transition"
                                    >
                                        ⬇ Unduh
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $doc->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus berkas ini?"
                                        class="px-2.5 py-1 text-red-700 bg-red-50 hover:bg-red-100 rounded text-[11px] font-medium transition"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada berkas dokumen yang diunggah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
