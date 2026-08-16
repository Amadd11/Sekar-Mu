<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                    {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">
                Penugasan Penilai Etik (Assign Reviewers)
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pengajuan: {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'Permohonan Etik' }}
            </p>
        </div>

        <div>
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                &larr; Kembali ke Detail Berkas
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-200/90 rounded-xl p-6 shadow-2xs space-y-6">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Pilih Penilai Etik Independen</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Pilih satu atau lebih penilai etik terdaftar untuk memeriksa kelayakan berkas ini. Status permohonan akan otomatis beralih menjadi <strong>Sedang Dinilai (Under Review)</strong>.
            </p>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div class="space-y-2">
                @forelse ($daftarReviewer as $rev)
                    @php
                        $isSelected = in_array($rev->id, $selectedReviewerIds);
                    @endphp
                    <label class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition {{ $isSelected ? 'bg-teal-50 border-teal-300 shadow-2xs' : 'bg-white border-slate-200 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                wire:model="selectedReviewerIds"
                                value="{{ $rev->id }}"
                                class="rounded border-slate-300 text-teal-700 focus:ring-teal-600 w-4 h-4"
                            />
                            <div>
                                <div class="font-bold text-xs text-slate-900">{{ $rev->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $rev->email }}</div>
                            </div>
                        </div>

                        @if ($isSelected)
                            <span class="text-[10px] font-bold text-teal-800 bg-teal-100 px-2 py-0.5 rounded">
                                Ditugaskan
                            </span>
                        @endif
                    </label>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400 border border-dashed rounded-xl">
                        Belum ada pengguna dengan role <strong>Reviewer</strong> terdaftar di sistem.
                    </div>
                @endforelse
            </div>

            @error('selectedReviewerIds')
                <span class="text-red-500 text-xs block">{{ $message }}</span>
            @enderror

            <div class="pt-4 flex justify-end">
                <button
                    type="submit"
                    class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                >
                    <span wire:loading.remove wire:target="save">💾 Simpan Penugasan Penilai</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
