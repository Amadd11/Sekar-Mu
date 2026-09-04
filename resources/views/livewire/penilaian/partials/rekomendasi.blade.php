<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 1 Col: Summary Metrics & Status Overview -->
        <div class="space-y-5">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 font-display pb-2 border-b border-slate-100">
                    Ringkasan Hasil Evaluasi
                </h3>
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-600 font-medium">Tingkat Kepatuhan:</span>
                        <span class="font-bold text-primary-800 text-sm">{{ $metrics['overall_compliance'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-600 font-medium">Prediksi Tipe:</span>
                        <span class="font-bold text-emerald-800">{{ $metrics['prediction']['type'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $metrics['counts']['C'] > 0 ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-100' }}">
                        <span class="{{ $metrics['counts']['C'] > 0 ? 'text-rose-900 font-semibold' : 'text-slate-600 font-medium' }}">Nilai C (Kurang):</span>
                        <span class="font-bold {{ $metrics['counts']['C'] > 0 ? 'text-rose-700' : 'text-slate-700' }}">
                            {{ $metrics['counts']['C'] }} Butir
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 font-mono text-[11px]">
                        <span class="text-slate-600">Distribusi Skor:</span>
                        <span>A:<strong>{{ $metrics['counts']['A'] }}</strong> | B:<strong>{{ $metrics['counts']['B'] }}</strong> | C:<strong>{{ $metrics['counts']['C'] }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Action Button Link to Borang -->
            <button
                type="button"
                wire:click="switchTab('penilaian')"
                class="w-full py-3 px-4 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-2xl text-xs transition shadow-2xs flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">edit_note</span>
                <span>Kembali Cek Borang Penilaian</span>
            </button>
        </div>

        <!-- Right 2 Cols: Form Rekomendasi Penilai -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-bold text-slate-900 font-display">Form Rekomendasi Penilai</h3>
                <p class="text-xs text-slate-500 mt-0.5">Berikan keputusan kelayakan etik resmi terhadap permohonan akreditasi KEPK ini.</p>
            </div>

            <form wire:submit="simpanPenilaian" class="space-y-5 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-2.5">
                        Keputusan Rekomendasi Akhir <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $rekomendasi === 'approved' ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold ring-2 ring-emerald-500/20 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                            <input
                                type="radio"
                                wire:model="rekomendasi"
                                value="approved"
                                class="mt-1 text-emerald-600 focus:ring-emerald-500 cursor-pointer" />
                            <div>
                                <div class="font-bold text-xs sm:text-sm">Disetujui (Layak Etik)</div>
                                <div class="text-[11px] text-emerald-700 font-normal mt-0.5">Memenuhi seluruh standar baku etik WHO-CIOMS & KNEPK.</div>
                            </div>
                        </label>

                        <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $rekomendasi === 'revision_required' ? 'bg-amber-50 border-amber-300 text-amber-950 font-bold ring-2 ring-amber-500/20 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                            <input
                                type="radio"
                                wire:model="rekomendasi"
                                value="revision_required"
                                class="mt-1 text-amber-600 focus:ring-amber-500 cursor-pointer" />
                            <div>
                                <div class="font-bold text-xs sm:text-sm">Perlu Perbaikan / Tindakan Korektif (CAPA)</div>
                                <div class="text-[11px] text-amber-700 font-normal mt-0.5">Membutuhkan kelengkapan bukti pendukung atau revisi SOP regulasi.</div>
                            </div>
                        </label>

                        <label class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition-all {{ $rekomendasi === 'rejected' ? 'bg-rose-50 border-rose-300 text-rose-950 font-bold ring-2 ring-rose-500/20 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                            <input
                                type="radio"
                                wire:model="rekomendasi"
                                value="rejected"
                                class="mt-1 text-rose-600 focus:ring-rose-500 cursor-pointer" />
                            <div>
                                <div class="font-bold text-xs sm:text-sm">Ditolak (Tidak Layak Etik)</div>
                                <div class="text-[11px] text-rose-700 font-normal mt-0.5">Terdapat ketidakpatuhan atau pelanggaran etik substansial.</div>
                            </div>
                        </label>
                    </div>
                    @error('rekomendasi')
                    <span class="text-red-500 text-[11px] block mt-1.5">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="catatan" class="block font-bold text-slate-700 mb-1.5">
                        Kesimpulan / Catatan Akhir Penilai:
                    </label>
                    <textarea
                        wire:model="catatan"
                        id="catatan"
                        rows="5"
                        placeholder="Tuliskan ringkasan pertimbangan keputusan kelayakan etik dan catatan resmi untuk KEPK..."
                        class="w-full text-xs rounded-xl border border-slate-300 p-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs leading-relaxed"></textarea>
                    @error('catatan')
                    <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="simpanPenilaian" class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>Simpan Keputusan Rekomendasi</span>
                        </span>
                        <span wire:loading wire:target="simpanPenilaian">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Catatan & Permintaan Perbaikan (Review Thread) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900 font-display">Catatan & Permintaan Perbaikan (Review Thread)</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kanal diskusi dan catatan revisi resmi antara Tim Asesor dan Pemohon KEPK.</p>
        </div>

        @if (session('comment_status'))
        <div class="bg-emerald-50 text-emerald-800 text-xs p-3.5 rounded-xl border border-emerald-200 font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
            <span>{{ session('comment_status') }}</span>
        </div>
        @endif

        <div class="space-y-3">
            @forelse ($allComments as $c)
            <div class="p-4 rounded-xl border {{ $c->selesai ? 'bg-slate-50 border-slate-200 text-slate-500' : 'bg-amber-50/50 border-amber-200 text-slate-800' }} text-xs space-y-2 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900">{{ $c->user->name }}</span>
                        <span class="text-[10px] text-slate-400 font-mono">{{ $c->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <button
                        type="button"
                        wire:click="toggleSelesai({{ $c->id }})"
                        class="text-[11px] font-bold px-3 py-1 rounded-lg border transition-all cursor-pointer {{ $c->selesai ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-100' }}">
                        {{ $c->selesai ? '✓ Selesai Diperbaiki' : 'Tandai Selesai' }}
                    </button>
                </div>
                <p class="leading-relaxed text-slate-700">{{ $c->catatan }}</p>
            </div>
            @empty
            <div class="py-8 text-center text-slate-400 text-xs">
                <span class="text-3xl block mb-2">💬</span>
                Belum ada catatan atau permintaan perbaikan yang dikirim.
            </div>
            @endforelse
        </div>

        <!-- Add Comment Form -->
        <div class="pt-4 border-t border-slate-100 space-y-3">
            <label for="catatanBaru" class="block text-xs font-bold text-slate-700">
                Tulis Catatan / Permintaan Perbaikan Baru:
            </label>
            <textarea
                wire:model="catatanBaru"
                id="catatanBaru"
                rows="3"
                placeholder="Tuliskan catatan telaah atau aspek instrumen yang memerlukan perbaikan pemohon..."
                class="w-full text-xs rounded-xl border border-slate-300 p-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs"></textarea>
            @error('catatanBaru')
            <span class="text-red-500 text-[11px] block">{{ $message }}</span>
            @enderror
            <div class="flex justify-end">
                <button
                    type="button"
                    wire:click="kirimCatatan"
                    class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">send</span>
                    <span>Kirim Catatan</span>
                </button>
            </div>
        </div>
    </div>
</div>
