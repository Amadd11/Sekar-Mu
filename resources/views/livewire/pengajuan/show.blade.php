<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Top Header Banner -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="$suratPengajuan->formulirAplikasi->nama_institusi ?? 'Surat Pengajuan Akreditasi KEPK'"
        :subtitle="'Permohonan asesmen dan evaluasi mandiri standar akreditasi KEPK WHO-CIOMS & KNEPK.'">
        <x-slot:actions>
            <button
                type="button"
                onclick="window.print()"
                class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition shadow-xs flex items-center justify-center"
                title="Cetak Halaman"
            >
                <span class="material-symbols-outlined text-[18px]">print</span>
            </button>

            <a href="{{ route('pengajuan.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition shadow-xs flex items-center gap-1" wire:navigate>
                <span>&larr;</span>
                <span class="hidden sm:inline">Daftar Pengajuan</span>
            </a>
        </x-slot:actions>
    </x-pengajuan.header>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                <span class="font-semibold">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-red-600 text-[18px]">error</span>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- 2. Status Banner -->
    <x-pengajuan.status-banner :surat="$suratPengajuan">
        @if ($suratPengajuan->isInProgress())
            <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                @can('delete', $suratPengajuan)
                    <button
                        type="button"
                        wire:click="hapusDraft"
                        wire:confirm="Yakin ingin menghapus berkas pengajuan ini secara permanen?"
                        class="px-4 py-2.5 text-xs font-semibold text-red-600 bg-white hover:bg-red-50 rounded-xl border border-red-200 transition shadow-2xs cursor-pointer">
                        Hapus Pengajuan
                    </button>
                @endcan
            </div>
        @endif
    </x-pengajuan.status-banner>

    <!-- 3. Admin Control Panel -->
    @if ($isAdmin)
        <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-md space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-teal-400">
                        <span class="material-symbols-outlined text-[20px]">shield</span>
                    </div>
                    <div>
                        <div class="font-display text-sm font-bold text-white tracking-tight">Panel Kontrol Administrator KEPK</div>
                        <div class="text-xs text-slate-400 mt-0.5">Kelola penugasan tim penilai independen dan penetapan status akhir akreditasi.</div>
                    </div>
                </div>
                <a
                    href="{{ route('penilaian.tugaskan', $suratPengajuan) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs rounded-xl transition shadow-2xs shrink-0"
                    wire:navigate>
                    <span class="material-symbols-outlined text-[16px]">person_add</span>
                    <span>Tugaskan Penilai</span>
                </a>
            </div>

            @if ($canDecide)
                <div class="pt-4 border-t border-slate-800 flex flex-wrap items-center gap-2.5">
                    <span class="text-xs text-slate-400 font-semibold mr-1">Keputusan Akhir Akreditasi:</span>
                    <button
                        type="button"
                        wire:click="putuskanStatus('approved')"
                        wire:confirm="Yakin ingin MENYETUJUI (ACC / Terakreditasi) permohonan akreditasi ini?"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span>ACC (Terakreditasi)</span>
                    </button>
                    <button
                        type="button"
                        wire:click="putuskanStatus('rejected')"
                        wire:confirm="Yakin ingin MENOLAK (Tidak Lolos) permohonan akreditasi ini?"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                        <span>Tolak (Tidak Lolos)</span>
                    </button>
                    @if (! $suratPengajuan->isInProgress())
                        <button
                            type="button"
                            wire:click="putuskanStatus('in_progress')"
                            wire:confirm="Buka kembali pengajuan ke status Proses Evaluasi?"
                            class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[15px]">lock_open</span>
                            <span>Buka Kembali (Proses Evaluasi)</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    @endif

    @if (auth()->user()->isAsessor() || auth()->user()->isAdmin())
        <!-- Assessor Workspace Banner -->
        <div class="bg-blue-50 border border-blue-200/90 text-blue-900 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 shadow-2xs">
                    <span class="material-symbols-outlined text-[24px]">clinical_notes</span>
                </div>
                <div>
                    <h3 class="font-display font-bold text-sm text-blue-950 leading-tight">Penelaahan Asesor Penilai (Real-Time)</h3>
                    <p class="text-xs text-blue-700 mt-1 leading-relaxed">
                        Anda dapat menelaah dan menilai evaluasi diri 164 butir, memberi catatan telaah, dan menyusun rekomendasi secara real-time.
                    </p>
                </div>
            </div>
            <a href="{{ route('penilaian.show', $suratPengajuan) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-600 active:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-700/20 transition shrink-0 cursor-pointer" wire:navigate>
                <span>Buka Lembar Penilaian (Real-Time)</span>
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    @endif

    <!-- 4. Main Borang Module Cards -->
    @hasrole('anggota')
        <div class="bg-white border border-primary-300 hover:border-primary-500 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]" style="font-variation-settings: 'FILL' 1;">fact_check</span>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-base text-slate-900">
                            B01-03: Evaluasi Diri (164 Butir)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Instrumen asesmen mandiri kepatuhan dan kelengkapan bukti dukung KEPK.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4 shrink-0 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100 justify-between sm:justify-end">
                    <div class="text-left sm:text-right font-mono text-xs text-slate-600 font-semibold">
                        <div>{{ $metrics['total_answered'] ?? 0 }}/{{ $metrics['total_items'] ?? 164 }} Butir Terisi</div>
                        <div class="text-primary-700 text-[11px]">{{ $metrics['compliance_percentage'] ?? 0 }}% Selesai</div>
                    </div>
                    <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-2xs" wire:navigate>
                        <span>Buka Evaluasi Diri</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    @endhasrole

    @hasanyrole('ketua_kepk|admin')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Module 1: Evaluasi Diri -->
        <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="bg-white border border-slate-200/90 hover:border-primary-400 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all group flex flex-col justify-between" wire:navigate>
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">fact_check</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 group-hover:text-primary-700 group-hover:translate-x-1 transition-all text-[20px]">arrow_forward</span>
            </div>
            <div>
                <h3 class="font-display font-bold text-sm text-slate-900 group-hover:text-primary-700 transition">
                    B01-03: Evaluasi Diri
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Instrumen 164 butir standar WHO-CIOMS & KNEPK.
                </p>
                <div class="mt-3 flex items-center justify-between text-[11px] font-mono text-slate-600 font-semibold pt-2.5 border-t border-slate-100">
                    <span>{{ $metrics['total_answered'] ?? 0 }}/{{ $metrics['total_items'] ?? 164 }} Terisi</span>
                    <span class="text-primary-700">{{ $metrics['compliance_percentage'] ?? 0 }}% Selesai</span>
                </div>
            </div>
        </a>

        <!-- Module 2: List Protokol Riset -->
        <a href="{{ route('pengajuan.list-protokol', $suratPengajuan) }}" class="bg-white border border-slate-200/90 hover:border-blue-400 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all group flex flex-col justify-between" wire:navigate>
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">list_alt</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 group-hover:text-blue-700 group-hover:translate-x-1 transition-all text-[20px]">arrow_forward</span>
            </div>
            <div>
                <h3 class="font-display font-bold text-sm text-slate-900 group-hover:text-blue-700 transition">
                    B01-04: List Protokol Riset
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Daftar judul penelitian yang telah ditelaah etiknya.
                </p>
                <div class="mt-3 flex items-center justify-between text-[11px] font-mono text-slate-600 font-semibold pt-2.5 border-t border-slate-100">
                    <span>Total Protokol</span>
                    <span class="text-blue-700">{{ $suratPengajuan->listProtokol->count() }} Terdaftar</span>
                </div>
            </div>
        </a>

        <!-- Module 3: Dokumen Bukti Evaluasi -->
        @php
            $totalEvaluasiFilesCount = $suratPengajuan->jawabanEvaluasi->sum(function($ans) {
                return count($ans->getAttachments());
            });
        @endphp
        <a href="{{ route('pengajuan.dokumen', $suratPengajuan) }}" class="bg-white border border-slate-200/90 hover:border-emerald-400 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all group flex flex-col justify-between" wire:navigate>
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">folder</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 group-hover:text-emerald-700 group-hover:translate-x-1 transition-all text-[20px]">arrow_forward</span>
            </div>
            <div>
                <h3 class="font-display font-bold text-sm text-slate-900 group-hover:text-emerald-700 transition">
                    Dokumen Bukti Evaluasi
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Arsip berkas bukti dukung per bagian evaluasi diri.
                </p>
                <div class="mt-3 flex items-center justify-between text-[11px] font-mono text-slate-600 font-semibold pt-2.5 border-t border-slate-100">
                    <span>Total Berkas</span>
                    <span class="text-emerald-700">{{ $totalEvaluasiFilesCount }} Berkas Terunggah</span>
                </div>
            </div>
        </a>
    </div>
    @endhasanyrole

    <!-- 5. Two-Columns Detailed Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Main Sections (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Results & Metrik Akreditasi -->
            <x-pengajuan.section-card icon="bar_chart" title="Hasil Penilaian & Prediksi Akreditasi">
                <x-slot:action>
                    <span class="px-3 py-1 rounded-full text-xs font-bold self-start sm:self-center {{ $metrics['prediction']['badge'] ?? $metrics['prediction']['badge_class'] ?? 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        {{ $metrics['prediction']['type'] ?? '-' }}
                    </span>
                </x-slot:action>

                <!-- 4 Metrik Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <x-pengajuan.stat-card
                        label="Kepatuhan"
                        :value="$metrics['compliance_percentage'] . '%'"
                        valueColor="text-primary-700"
                        bgColor="bg-slate-50/70" />
                    <x-pengajuan.stat-card
                        label="Lengkap (A)"
                        :value="$metrics['score_counts']['A']"
                        valueColor="text-emerald-600"
                        bgColor="bg-slate-50/70" />
                    <x-pengajuan.stat-card
                        label="Sebagian (B)"
                        :value="$metrics['score_counts']['B']"
                        valueColor="text-amber-600"
                        bgColor="bg-slate-50/70" />
                    <x-pengajuan.stat-card
                        label="Kurang (C)"
                        :value="$metrics['score_counts']['C']"
                        :valueColor="$metrics['score_counts']['C'] > 0 ? 'text-red-600' : 'text-slate-700'"
                        bgColor="bg-slate-50/70" />
                </div>





                <!-- Reviewer Recommendations -->
                @if ($suratPengajuan->penilaianEtik->isNotEmpty())
                    <div class="space-y-3 pt-2">
                        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Ulasan & Rekomendasi Asesor:</div>
                        @foreach ($suratPengajuan->penilaianEtik as $t)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2 text-xs">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <span class="font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px] text-slate-500">person</span>
                                        <span>{{ $t->penilai->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal">({{ $t->created_at->format('d M Y, H:i') }})</span>
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $t->badge_rekomendasi }}">
                                        {{ $t->label_rekomendasi }}
                                    </span>
                                </div>
                                @if ($t->catatan)
                                    <p class="text-slate-700 leading-relaxed bg-white p-3 rounded-xl border border-slate-200/80">{{ $t->catatan }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Assessor Findings -->
                @php
                    $findings = $suratPengajuan->penilaianButirAsesor->whereNotNull('temuan');
                @endphp
                @if ($findings->isNotEmpty())
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Catatan Temuan Butir dari Asesor ({{ $findings->count() }}):</div>
                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto text-xs bg-slate-50 rounded-2xl border border-slate-200">
                            @foreach ($findings as $f)
                                <div class="p-3.5 space-y-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-primary-700">Butir #{{ $f->butir_evaluasi_id }}: {{ $f->butir?->pertanyaan }}</span>
                                        <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $f->skor === 'A' ? 'bg-emerald-100 text-emerald-800' : ($f->skor === 'B' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                            Nilai: {{ $f->skor }}
                                        </span>
                                    </div>
                                    <p class="text-slate-700 italic">"{{ $f->temuan }}"</p>
                                    @if ($f->catatan)
                                        <p class="text-slate-500 text-[11px]">Saran: {{ $f->catatan }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-pengajuan.section-card>

            <!-- Section 1: Identitas Institusi -->
            <x-pengajuan.section-card icon="apartment" title="1. Identitas Institusi Pengusul">
                <x-slot:action>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.formulir-aplikasi', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline flex items-center gap-1" wire:navigate>
                            <span class="material-symbols-outlined text-[14px]">edit</span>
                            <span>Edit Formulir</span>
                        </a>
                    @endcan
                </x-slot:action>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50/60 p-3 rounded-xl border border-slate-100">
                        <dt class="text-slate-400 font-medium">Nama Institusi</dt>
                        <dd class="text-slate-900 font-bold text-sm mt-0.5">{{ $suratPengajuan->formulirAplikasi->nama_institusi ?? '-' }}</dd>
                    </div>
                    <div class="bg-slate-50/60 p-3 rounded-xl border border-slate-100">
                        <dt class="text-slate-400 font-medium">Singkatan</dt>
                        <dd class="text-slate-800 font-bold text-sm mt-0.5">{{ $suratPengajuan->formulirAplikasi->singkatan ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2 bg-slate-50/60 p-3 rounded-xl border border-slate-100">
                        <dt class="text-slate-400 font-medium">Alamat Lengkap</dt>
                        <dd class="text-slate-700 mt-0.5 leading-relaxed">{{ $suratPengajuan->formulirAplikasi->alamat ?? '-' }}</dd>
                    </div>
                    <div class="bg-slate-50/60 p-3 rounded-xl border border-slate-100">
                        <dt class="text-slate-400 font-medium">Kota / Kabupaten</dt>
                        <dd class="text-slate-800 font-semibold mt-0.5">{{ $suratPengajuan->formulirAplikasi->kota ?? '-' }}</dd>
                    </div>
                    <div class="bg-slate-50/60 p-3 rounded-xl border border-slate-100">
                        <dt class="text-slate-400 font-medium">Kontak & Surel</dt>
                        <dd class="text-slate-700 mt-0.5">{{ $suratPengajuan->formulirAplikasi->telepon ?? '-' }} / {{ $suratPengajuan->formulirAplikasi->email ?? '-' }}</dd>
                    </div>
                </dl>
            </x-pengajuan.section-card>

            <!-- Section 2: Visi & Misi KEPK -->
            <x-pengajuan.section-card icon="visibility" title="2. Visi & Misi KEPK">
                <x-slot:action>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.profil', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline flex items-center gap-1" wire:navigate>
                            <span class="material-symbols-outlined text-[14px]">edit</span>
                            <span>Edit Profil</span>
                        </a>
                    @endcan
                </x-slot:action>

                <div class="space-y-4 text-xs">
                    <div>
                        <div class="text-slate-400 font-medium mb-1">Deskripsi / Gambaran Umum Komite:</div>
                        <p class="text-slate-700 leading-relaxed bg-slate-50/60 p-3 rounded-xl border border-slate-100 whitespace-pre-line">{{ $suratPengajuan->profilKepk->deskripsi ?? 'Belum diisi.' }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-100">
                            <div class="text-primary-700 font-bold mb-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">flag</span>
                                <span>Visi KEPK:</span>
                            </div>
                            <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $suratPengajuan->profilKepk->visi ?? 'Belum diisi.' }}</p>
                        </div>
                        <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-100">
                            <div class="text-primary-700 font-bold mb-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">task_alt</span>
                                <span>Misi KEPK:</span>
                            </div>
                            <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $suratPengajuan->profilKepk->misi ?? 'Belum diisi.' }}</p>
                        </div>
                    </div>
                </div>
            </x-pengajuan.section-card>

            <!-- Section 3: Struktur Anggota KEPK -->
            <x-pengajuan.section-card icon="group" title="3. Anggota KEPK ({{ $suratPengajuan->anggotaKepk->count() }})">
                <x-slot:action>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.profil', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline flex items-center gap-1" wire:navigate>
                            <span class="material-symbols-outlined text-[14px]">settings</span>
                            <span>Kelola Anggota</span>
                        </a>
                    @endcan
                </x-slot:action>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="px-4 py-3 w-12 text-center">No</th>
                                <th class="px-4 py-3">Nama Lengkap</th>
                                <th class="px-4 py-3">Jabatan</th>
                                <th class="px-4 py-3">Kontak / Email</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($suratPengajuan->anggotaKepk as $idx => $m)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 text-slate-400 text-center font-mono">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ strtoupper(substr($m->nama, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-900">{{ $m->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="bg-slate-100 px-2 py-0.5 rounded-md font-medium text-slate-800">{{ $m->jabatan ?? 'Anggota' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 font-mono">{{ $m->email ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">Belum ada anggota yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-pengajuan.section-card>
        </div>

        <!-- Right: Sidebar Meta & Details (1/3 width) -->
        <div class="space-y-6">
            <!-- Card 1: Asesor Ditugaskan -->
            <x-pengajuan.section-card icon="clinical_notes" title="Asesor Ditugaskan">
                <x-slot:action>
                    @if ($isAdmin)
                        <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="text-[11px] font-bold text-primary-700 hover:underline" wire:navigate>
                            Kelola
                        </a>
                    @endif
                </x-slot:action>

                @forelse ($suratPengajuan->penilai as $rev)
                    <div class="flex items-center gap-3 text-xs p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                        <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr($rev->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden flex-1">
                            <div class="font-bold text-slate-900 truncate">{{ $rev->name }}</div>
                            <div class="text-[11px] text-slate-500 truncate">{{ $rev->email }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Belum ada asesor penilai ditugaskan.</p>
                    @if ($isAdmin)
                        <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="mt-2 block text-center py-2.5 px-4 bg-primary-700 text-white text-xs font-bold rounded-xl hover:bg-primary-600 transition shadow-2xs" wire:navigate>
                            + Tugaskan Asesor Sekarang
                        </a>
                    @endif
                @endforelse
            </x-pengajuan.section-card>

            <!-- Card 2: Tujuan Komite Etik -->
            <x-pengajuan.section-card icon="health_and_safety" title="Komite Etik (KEPK)">
                <div>
                    <div class="font-bold text-slate-900 text-sm leading-snug">{{ $suratPengajuan->kepk->name ?? '-' }}</div>
                    <div class="text-xs text-slate-500 mt-1">Institusi: {{ $suratPengajuan->kepk->institusi->name ?? '-' }}</div>
                    <div class="text-xs font-mono text-slate-400 mt-0.5">Kode Registrasi: {{ $suratPengajuan->kepk->code ?? '-' }}</div>
                </div>
            </x-pengajuan.section-card>

            <!-- Card 3: Informasi Meta Pengajuan -->
            <x-pengajuan.section-card icon="info" title="Informasi Berkas">
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Pemohon / Pengaju:</span>
                        <span class="font-semibold text-slate-800">{{ $suratPengajuan->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Tanggal Dibuat:</span>
                        <span class="text-slate-700 font-mono">{{ $suratPengajuan->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if ($suratPengajuan->diajukan_pada)
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Tanggal Diajukan:</span>
                            <span class="text-slate-700 font-mono">{{ $suratPengajuan->diajukan_pada->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-500">Status Saat Ini:</span>
                        <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                    </div>
                </div>
            </x-pengajuan.section-card>
        </div>
    </div>
</div>