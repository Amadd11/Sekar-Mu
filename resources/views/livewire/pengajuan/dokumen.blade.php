<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                    {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">Dokumen & Lampiran Berkas Pengajuan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Unggah berkas SK KEPK, pedoman standar operasional (SOP), dan dokumen bukti pendukung.</p>
        </div>

        <div>
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                &larr; Kembali ke Detail Pengajuan
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    <!-- Upload Card -->
    @if ($suratPengajuan->isEditable())
        <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900">Unggah Berkas Baru</h2>
            <form wire:submit="unggah" class="space-y-3 text-xs">
                <div>
                    <input
                        type="file"
                        wire:model="file"
                        id="file"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                    />
                    <div class="text-[11px] text-slate-400 mt-1">Format didukung: PDF, DOC, DOCX, XLS, XLSX, ZIP, JPG, PNG (Maks 10 MB)</div>
                    @error('file') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                    >
                        <span wire:loading.remove wire:target="unggah">⬆ Upload Berkas</span>
                        <span wire:loading wire:target="unggah">Mengunggah...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- List Documents -->
    <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Daftar Dokumen Lampiran ({{ $daftarDokumen->count() }})</h3>
        </div>
        <div class="divide-y divide-slate-100 text-xs">
            @forelse ($daftarDokumen as $doc)
                <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50">
                    <div>
                        <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                            <span>📄</span>
                            <span>{{ $doc->nama_asli }}</span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                            {{ $doc->formatUkuran() }} • Diunggah: {{ $doc->created_at->format('d M Y, H:i') }} ({{ $doc->pengunggah->name ?? '-' }})
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ Storage::url($doc->path) }}"
                            target="_blank"
                            class="px-3 py-1 bg-teal-50 hover:bg-teal-100 text-teal-800 font-semibold rounded-lg text-xs transition"
                        >
                            ⬇ Buka / Unduh
                        </a>
                        @if ($suratPengajuan->isEditable())
                            <button
                                type="button"
                                wire:click="hapus({{ $doc->id }})"
                                wire:confirm="Hapus berkas ini?"
                                class="text-red-600 hover:underline font-semibold"
                            >
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-slate-400">
                    Belum ada dokumen lampiran yang diunggah.
                </div>
            @endforelse
        </div>
    </div>
</div>
