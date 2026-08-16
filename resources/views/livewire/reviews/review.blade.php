<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\Application::statusBadgeClasses($application->status) }}">
                    {{ \App\Models\Application::statusLabel($application->status) }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">
                Lembar Telaah Etik: {{ $application->information->name ?? 'Permohonan Etik' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pemohon: {{ $application->user->name }} • Institusi: {{ $application->kepk->institution->name ?? '-' }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('reviews.index') }}" class="btn-outline btn-sm text-xs" wire:navigate>
                &larr; Kembali ke Daftar Telaah
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    <!-- 4 Score Cards (Self Assessment Summary) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-slate-800">{{ $scoreSummary['total'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">Item Telah Dievaluasi</div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-emerald-600">{{ $scoreSummary['score_a'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">A – Lengkap/Selalu</div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-amber-500">{{ $scoreSummary['score_b'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">B – Sebagian/Kadang</div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-xl p-4 text-center shadow-2xs">
            <div class="text-2xl font-black text-rose-600">{{ $scoreSummary['score_c'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1">C – Tidak Lengkap</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Details & Documents & Protocols -->
        <div class="lg:col-span-2 space-y-6">
            <!-- 1. Protocols List -->
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">1. Protokol Penelitian ({{ $application->protocols->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                            <tr>
                                <th class="px-5 py-3">No. Protokol</th>
                                <th class="px-5 py-3">Judul Penelitian</th>
                                <th class="px-5 py-3">Peneliti Utama</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($application->protocols as $prot)
                                <tr>
                                    <td class="px-5 py-3 font-mono font-bold text-slate-700">{{ $prot->protocol_number }}</td>
                                    <td class="px-5 py-3 font-semibold text-slate-900">{{ $prot->title }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $prot->principal_investigator }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-4 text-center text-slate-400">Tidak ada protokol riset terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Documents List -->
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">2. Berkas Lampiran & Bukti ({{ $application->documents->count() }})</h3>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse ($application->documents as $doc)
                        <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50">
                            <div>
                                <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>📄</span>
                                    <span>{{ $doc->original_name }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                    {{ $doc->readableSize() }} • Diunggah: {{ $doc->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <a
                                href="{{ Storage::url($doc->path) }}"
                                target="_blank"
                                class="px-3 py-1 bg-teal-50 hover:bg-teal-100 text-teal-800 font-semibold rounded-lg text-xs transition"
                            >
                                ⬇ Buka / Unduh
                            </a>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-slate-400">
                            Belum ada berkas lampiran yang diunggah oleh pemohon.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 3. Review Comments / Catatan Perbaikan Thread -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">3. Catatan & Rekomendasi Perbaikan Butir</h3>
                </div>

                @if (session('comment_status'))
                    <div class="bg-emerald-50 text-emerald-800 text-xs p-2.5 rounded-lg border border-emerald-200">
                        {{ session('comment_status') }}
                    </div>
                @endif

                <!-- Comments List -->
                <div class="space-y-3">
                    @php
                        $allComments = $allReviews->flatMap->comments;
                    @endphp
                    @forelse ($allComments as $comment)
                        <div class="p-3.5 rounded-xl border {{ $comment->is_resolved ? 'bg-slate-50 border-slate-200 text-slate-500' : 'bg-amber-50/50 border-amber-200 text-slate-800' }} text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900">{{ $comment->user->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $comment->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <button
                                    type="button"
                                    wire:click="toggleResolve({{ $comment->id }})"
                                    class="text-[11px] font-semibold px-2 py-0.5 rounded border transition {{ $comment->is_resolved ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-100' }}"
                                >
                                    {{ $comment->is_resolved ? '✓ Selesai Diperbaiki' : 'Tandai Selesai' }}
                                </button>
                            </div>
                            <p class="leading-relaxed">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic py-2">Belum ada catatan telaah.</p>
                    @endforelse
                </div>

                <!-- Add Comment Form -->
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <label for="newComment" class="block text-xs font-semibold text-slate-700">
                        Tambah Catatan / Permintaan Revisi:
                    </label>
                    <textarea
                        wire:model="newComment"
                        id="newComment"
                        rows="2"
                        placeholder="Tuliskan catatan telaah atau aspek butir yang memerlukan perbaikan pemohon..."
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    ></textarea>
                    @error('newComment')
                        <span class="text-red-500 text-[11px] block">{{ $message }}</span>
                    @enderror
                    <div class="flex justify-end">
                        <button
                            type="button"
                            wire:click="postComment"
                            class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                        >
                            Kirim Catatan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Review Recommendation Form -->
        <div class="space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Form Rekomendasi Penelaah</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Berikan keputusan telaah etik terhadap permohonan ini.</p>
                </div>

                <form wire:submit="submitReview" class="space-y-4 text-xs">
                    <!-- Recommendation Options -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Keputusan Rekomendasi <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $recommendation === 'approved' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <input
                                    type="radio"
                                    wire:model="recommendation"
                                    value="approved"
                                    class="text-emerald-600 focus:ring-emerald-500"
                                />
                                <div>
                                    <div class="font-bold">Disetujui (Layak Etik)</div>
                                    <div class="text-[10px] text-emerald-700 font-normal">Memenuhi seluruh standar etik WHO-CIOMS.</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $recommendation === 'revision_required' ? 'bg-amber-50 border-amber-300 text-amber-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <input
                                    type="radio"
                                    wire:model="recommendation"
                                    value="revision_required"
                                    class="text-amber-600 focus:ring-amber-500"
                                />
                                <div>
                                    <div class="font-bold">Perlu Perbaikan / Revisi</div>
                                    <div class="text-[10px] text-amber-700 font-normal">Membutuhkan kelengkapan atau revisi berkas.</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $recommendation === 'rejected' ? 'bg-rose-50 border-rose-300 text-rose-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <input
                                    type="radio"
                                    wire:model="recommendation"
                                    value="rejected"
                                    class="text-rose-600 focus:ring-rose-500"
                                />
                                <div>
                                    <div class="font-bold">Ditolak (Tidak Layak Etik)</div>
                                    <div class="text-[10px] text-rose-700 font-normal">Terdapat pelanggaran etik substansial.</div>
                                </div>
                            </label>
                        </div>
                        @error('recommendation')
                            <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Reviewer Notes -->
                    <div>
                        <label for="notes" class="block font-semibold text-slate-700 mb-1">
                            Kesimpulan / Catatan Akhir Penelaah:
                        </label>
                        <textarea
                            wire:model="notes"
                            id="notes"
                            rows="4"
                            placeholder="Tuliskan ringkasan pertimbangan keputusan etik..."
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        ></textarea>
                        @error('notes')
                            <span class="text-red-500 text-[11px] block mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold rounded-lg shadow-2xs transition"
                        >
                            <span wire:loading.remove wire:target="submitReview">💾 Simpan Keputusan Telaah</span>
                            <span wire:loading wire:target="submitReview">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
