<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                    {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">
                Lembar Penilaian Etik: {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'Permohonan Etik' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pemohon: {{ $suratPengajuan->user->name }} • Institusi: {{ $suratPengajuan->kepk->institusi->name ?? '-' }}
            </p>
        </div>

        <div>
            <a href="{{ route('penilaian.index') }}" class="btn-outline btn-sm text-xs" wire:navigate>
                &larr; Kembali ke Daftar Penilaian
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
        <x-score-card title="Item Telah Dievaluasi" :count="$rekapSkor['total']" color="slate" />
        <x-score-card title="A – Lengkap/Selalu" :count="$rekapSkor['skor_a']" color="emerald" />
        <x-score-card title="B – Sebagian/Kadang" :count="$rekapSkor['skor_b']" color="amber" />
        <x-score-card title="C – Tidak Lengkap" :count="$rekapSkor['skor_c']" color="rose" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Details & Documents & Protocols -->
        <div class="lg:col-span-2 space-y-6">
            <!-- 1. Protocols List -->
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">1. Protokol Penelitian ({{ $suratPengajuan->listProtokol->count() }})</h3>
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
                            @forelse ($suratPengajuan->listProtokol as $prot)
                                <tr>
                                    <td class="px-5 py-3 font-mono font-bold text-slate-700">{{ $prot->nomor_protokol }}</td>
                                    <td class="px-5 py-3 font-semibold text-slate-900">{{ $prot->judul }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $prot->peneliti_utama }}</td>
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
                    <h3 class="text-sm font-bold text-slate-900">2. Berkas Lampiran & Bukti ({{ $suratPengajuan->dokumen->count() }})</h3>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse ($suratPengajuan->dokumen as $doc)
                        <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50">
                            <div>
                                <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>📄</span>
                                    <span>{{ $doc->nama_asli }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                    {{ $doc->formatUkuran() }} • Diunggah: {{ $doc->created_at->format('d M Y, H:i') }}
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

            <!-- 3. Comments Thread -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">3. Catatan & Rekomendasi Perbaikan Butir</h3>
                </div>

                @if (session('comment_status'))
                    <div class="bg-emerald-50 text-emerald-800 text-xs p-2.5 rounded-lg border border-emerald-200">
                        {{ session('comment_status') }}
                    </div>
                @endif

                <div class="space-y-3">
                    @php
                        $allComments = $semuaPenilaian->flatMap->catatanPenilaian;
                    @endphp
                    @forelse ($allComments as $c)
                        <div class="p-3.5 rounded-xl border {{ $c->selesai ? 'bg-slate-50 border-slate-200 text-slate-500' : 'bg-amber-50/50 border-amber-200 text-slate-800' }} text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900">{{ $c->user->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $c->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <button
                                    type="button"
                                    wire:click="toggleSelesai({{ $c->id }})"
                                    class="text-[11px] font-semibold px-2 py-0.5 rounded border transition {{ $c->selesai ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-100' }}"
                                >
                                    {{ $c->selesai ? '✓ Selesai Diperbaiki' : 'Tandai Selesai' }}
                                </button>
                            </div>
                            <p class="leading-relaxed">{{ $c->catatan }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic py-2">Belum ada catatan penilaian.</p>
                    @endforelse
                </div>

                <!-- Add Comment Form -->
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <label for="catatanBaru" class="block text-xs font-semibold text-slate-700">
                        Tambah Catatan / Permintaan Perbaikan:
                    </label>
                    <textarea
                        wire:model="catatanBaru"
                        id="catatanBaru"
                        rows="2"
                        placeholder="Tuliskan catatan penilaian atau aspek butir yang memerlukan perbaikan pemohon..."
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    ></textarea>
                    @error('catatanBaru')
                        <span class="text-red-500 text-[11px] block">{{ $message }}</span>
                    @enderror
                    <div class="flex justify-end">
                        <button
                            type="button"
                            wire:click="kirimCatatan"
                            class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                        >
                            Kirim Catatan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Recommendation Form -->
        <div class="space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Form Rekomendasi Penilai</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Berikan keputusan kelayakan etik terhadap permohonan ini.</p>
                </div>

                <form wire:submit="simpanPenilaian" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Keputusan Rekomendasi <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $rekomendasi === 'approved' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <input
                                    type="radio"
                                    wire:model="rekomendasi"
                                    value="approved"
                                    class="text-emerald-600 focus:ring-emerald-500"
                                />
                                <div>
                                    <div class="font-bold">Disetujui (Layak Etik)</div>
                                    <div class="text-[10px] text-emerald-700 font-normal">Memenuhi seluruh standar etik WHO-CIOMS.</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $rekomendasi === 'revision_required' ? 'bg-amber-50 border-amber-300 text-amber-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <input
                                    type="radio"
                                    wire:model="rekomendasi"
                                    value="revision_required"
                                    class="text-amber-600 focus:ring-amber-500"
                                />
                                <div>
                                    <div class="font-bold">Perlu Perbaikan / Revisi</div>
                                    <div class="text-[10px] text-amber-700 font-normal">Membutuhkan kelengkapan atau perbaikan berkas.</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ $rekomendasi === 'rejected' ? 'bg-rose-50 border-rose-300 text-rose-900 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <input
                                    type="radio"
                                    wire:model="rekomendasi"
                                    value="rejected"
                                    class="text-rose-600 focus:ring-rose-500"
                                />
                                <div>
                                    <div class="font-bold">Ditolak (Tidak Layak Etik)</div>
                                    <div class="text-[10px] text-rose-700 font-normal">Terdapat pelanggaran etik substansial.</div>
                                </div>
                            </label>
                        </div>
                        @error('rekomendasi')
                            <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan" class="block font-semibold text-slate-700 mb-1">
                            Kesimpulan / Catatan Akhir Penilai:
                        </label>
                        <textarea
                            wire:model="catatan"
                            id="catatan"
                            rows="4"
                            placeholder="Tuliskan ringkasan pertimbangan keputusan kelayakan etik..."
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        ></textarea>
                        @error('catatan')
                            <span class="text-red-500 text-[11px] block mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold rounded-lg shadow-2xs transition"
                        >
                            <span wire:loading.remove wire:target="simpanPenilaian">💾 Simpan Hasil Penilaian</span>
                            <span wire:loading wire:target="simpanPenilaian">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
