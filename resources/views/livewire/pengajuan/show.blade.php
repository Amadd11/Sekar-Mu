<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Top Header Banner -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="$suratPengajuan->formulirAplikasi->nama_institusi ?? 'Surat Pengajuan Akreditasi KEPK'"
        :subtitle="'Permohonan asesmen dan evaluasi mandiri standar akreditasi KEPK WHO-CIOMS & KNEPK.'">
        <x-slot:actions>
            <a
                href="{{ route('pengajuan.pdf.hasil-akreditasi', $suratPengajuan) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 transition shadow-xs">
                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                <span>Unduh Laporan PDF</span>
            </a>

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

    <!-- 2. Status Banner & Submission Actions -->
    @if ($suratPengajuan->isDraft())
        <div class="bg-gradient-to-r from-teal-50/80 to-emerald-50/80 border border-teal-200 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">edit_note</span>
                </div>
                <div>
                    <div class="font-display font-bold text-teal-950 text-sm flex items-center gap-2">
                        <span>Status Berkas:</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold border whitespace-nowrap {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                            <span class="material-symbols-outlined text-[13px]">{{ \App\Models\SuratPengajuan::statusIcon($suratPengajuan->status) }}</span>
                            <span>{{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}</span>
                        </span>
                    </div>
                    <p class="text-xs text-teal-800 mt-1 max-w-2xl leading-relaxed">
                        Lengkapi seluruh borang evaluasi diri (164 butir), list protokol riset, dan dokumen lampiran sebelum diajukan ke tim penilai KEPK.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                @can('delete', $suratPengajuan)
                    <button
                        type="button"
                        wire:click="hapusDraft"
                        wire:confirm="Yakin ingin menghapus draft surat pengajuan ini secara permanen?"
                        class="px-4 py-2.5 text-xs font-semibold text-red-600 bg-white hover:bg-red-50 rounded-xl border border-red-200 transition shadow-2xs">
                        Hapus Draft
                    </button>
                @endcan
                @can('submit', $suratPengajuan)
                    <button
                        type="button"
                        wire:click="ajukanBerkas"
                        wire:confirm="Apakah Anda yakin ingin mengajukan berkas pengajuan etik ini untuk dinilai oleh Asesor?"
                        class="px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition flex items-center gap-1.5 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        <span>Submit Pengajuan</span>
                    </button>
                @endcan
            </div>
        </div>
    @elseif ($suratPengajuan->isRevisionRequired())
        <div class="bg-amber-50 border border-amber-300 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">warning</span>
                </div>
                <div>
                    <div class="font-display font-bold text-amber-950 text-sm flex items-center gap-2">
                        <span>Status Berkas:</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold border whitespace-nowrap {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                            <span class="material-symbols-outlined text-[13px]">{{ \App\Models\SuratPengajuan::statusIcon($suratPengajuan->status) }}</span>
                            <span>{{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}</span>
                        </span>
                    </div>
                    <p class="text-xs text-amber-800 mt-1 max-w-2xl leading-relaxed">
                        Asesor telah memberikan catatan perbaikan. Silakan tinjau ulasan temuan di bawah, lakukan revisi, lalu klik tombol ajukan ulang.
                    </p>
                </div>
            </div>
            @can('submit', $suratPengajuan)
                <button
                    type="button"
                    wire:click="ajukanBerkas"
                    wire:confirm="Yakin ingin mengajukan ulang berkas perbaikan ini?"
                    class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md shadow-amber-600/20 transition shrink-0 flex items-center gap-1.5 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    <span>Ajukan Ulang Perbaikan</span>
                </button>
            @endcan
        </div>
    @else
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">{{ \App\Models\SuratPengajuan::statusIcon($suratPengajuan->status) }}</span>
                </div>
                <div>
                    <div class="font-display font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span>Status Berkas:</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold border whitespace-nowrap {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                            <span>{{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Diajukan pada {{ $suratPengajuan->diajukan_pada?->format('d M Y, H:i') ?? '-' }}.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Admin Control Panel (Admins Only) -->
    @if (auth()->user()?->isAdmin())
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

            @if (!in_array($suratPengajuan->status, ['draft'], true))
                <div class="pt-4 border-t border-slate-800 flex flex-wrap items-center gap-2.5">
                    <span class="text-xs text-slate-400 font-semibold mr-1">Putuskan Status Akhir:</span>
                    <button
                        type="button"
                        wire:click="putuskanStatus('approved')"
                        wire:confirm="Yakin ingin MENYETUJUI (Approve) permohonan etik ini?"
                        class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">check</span>
                        <span>Setujui (Approved)</span>
                    </button>
                    <button
                        type="button"
                        wire:click="putuskanStatus('revision_required')"
                        wire:confirm="Yakin ingin MEMINTA PERBAIKAN kepada pemohon?"
                        class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">edit</span>
                        <span>Minta Perbaikan</span>
                    </button>
                    <button
                        type="button"
                        wire:click="putuskanStatus('rejected')"
                        wire:confirm="Yakin ingin MENOLAK (Reject) permohonan etik ini?"
                        class="px-3.5 py-1.5 bg-red-600 hover:bg-red-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">close</span>
                        <span>Tolak (Rejected)</span>
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- 4. Three Main Borang Module Cards (Quick Navigation) -->
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

        <!-- Module 3: Dokumen Lampiran -->
        <a href="{{ route('pengajuan.dokumen', $suratPengajuan) }}" class="bg-white border border-slate-200/90 hover:border-emerald-400 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all group flex flex-col justify-between" wire:navigate>
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">folder</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 group-hover:text-emerald-700 group-hover:translate-x-1 transition-all text-[20px]">arrow_forward</span>
            </div>
            <div>
                <h3 class="font-display font-bold text-sm text-slate-900 group-hover:text-emerald-700 transition">
                    Dokumen Lampiran
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Kelola berkas SK pendirian, SOP & bukti dukung.
                </p>
                <div class="mt-3 flex items-center justify-between text-[11px] font-mono text-slate-600 font-semibold pt-2.5 border-t border-slate-100">
                    <span>Total Berkas</span>
                    <span class="text-emerald-700">{{ $suratPengajuan->dokumen->count() }} File Diunggah</span>
                </div>
            </div>
        </a>
    </div>

    <!-- 5. Two-Columns Detailed Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Main Sections (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Results & Metrik Akreditasi -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-primary-700 text-[22px]">bar_chart</span>
                        <div>
                            <h3 class="font-display text-sm font-bold text-slate-900">Hasil Penilaian & Prediksi Akreditasi</h3>
                            <p class="text-xs text-slate-500">Evaluasi kepatuhan 164 butir standar acuan WHO-CIOMS & KNEPK.</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold self-start sm:self-center {{ $metrics['prediction']['badge'] ?? $metrics['prediction']['badge_class'] ?? 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        {{ $metrics['prediction']['type'] ?? '-' }}
                    </span>
                </div>

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

                <!-- PDF Export Quick Bar -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between flex-wrap gap-3 text-xs">
                    <span class="font-bold text-slate-700 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-slate-500">picture_as_pdf</span>
                        <span>Ekspor Dokumen Resmi (PDF):</span>
                    </span>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a
                            href="{{ route('pengajuan.pdf.hasil-akreditasi', $suratPengajuan) }}"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 transition shadow-2xs">
                            <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                            <span>Laporan Akreditasi</span>
                        </a>
                        <a
                            href="{{ route('pengajuan.pdf.evaluasi-diri', $suratPengajuan) }}"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition shadow-2xs">
                            <span class="material-symbols-outlined text-[15px]">description</span>
                            <span>Borang 164 Butir</span>
                        </a>
                        <a
                            href="{{ route('pengajuan.pdf.matriks-gap', $suratPengajuan) }}"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition shadow-2xs">
                            <span class="material-symbols-outlined text-[15px]">bar_chart</span>
                            <span>Matriks Gap</span>
                        </a>
                    </div>
                </div>

                <!-- Critical Findings Alert -->
                @if ($metrics['critical_non_compliance_count'] > 0)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 text-xs text-red-800">
                        <span class="material-symbols-outlined text-[20px] text-red-600 shrink-0">warning</span>
                        <div>
                            <span class="font-bold block">Ditemukan {{ $metrics['critical_non_compliance_count'] }} Temuan Kritis (Critical Non-Compliance):</span>
                            <p class="text-[11px] text-red-700 mt-0.5">Terdapat butir kritis bernilai C yang memerlukan tindakan korektif (CAPA) sebelum akreditasi dapat disahkan.</p>
                        </div>
                    </div>
                @endif

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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ \App\Models\PenilaianEtik::badgeRekomendasi($t->rekomendasi) }}">
                                        {{ \App\Models\PenilaianEtik::labelRekomendasi($t->rekomendasi) }}
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
            </div>

            <!-- Section 1: Identitas Institusi -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-700 text-[20px]">apartment</span>
                        <h3 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">1. Identitas Institusi Pengusul</h3>
                    </div>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.formulir-aplikasi', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline flex items-center gap-1" wire:navigate>
                            <span class="material-symbols-outlined text-[14px]">edit</span>
                            <span>Edit Formulir</span>
                        </a>
                    @endcan
                </div>
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
            </div>

            <!-- Section 2: Visi & Misi KEPK -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-700 text-[20px]">visibility</span>
                        <h3 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">2. Visi & Misi KEPK</h3>
                    </div>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.profil', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline flex items-center gap-1" wire:navigate>
                            <span class="material-symbols-outlined text-[14px]">edit</span>
                            <span>Edit Profil</span>
                        </a>
                    @endcan
                </div>
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
            </div>

            <!-- Section 3: Struktur Anggota KEPK -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-700 text-[20px]">group</span>
                        <h3 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">3. Anggota KEPK ({{ $suratPengajuan->anggotaKepk->count() }})</h3>
                    </div>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.profil', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline flex items-center gap-1" wire:navigate>
                            <span class="material-symbols-outlined text-[14px]">settings</span>
                            <span>Kelola Anggota</span>
                        </a>
                    @endcan
                </div>
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
            </div>
        </div>

        <!-- Right: Sidebar Meta & Details (1/3 width) -->
        <div class="space-y-6">
            <!-- Card 1: Asesor Ditugaskan -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-700 text-[18px]">clinical_notes</span>
                        <span class="font-display text-xs font-bold text-slate-800 uppercase tracking-wider">Asesor Ditugaskan</span>
                    </div>
                    @if (auth()->user()?->isAdmin())
                        <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="text-[11px] font-bold text-primary-700 hover:underline" wire:navigate>
                            Kelola
                        </a>
                    @endif
                </div>
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
                    @if (auth()->user()?->isAdmin())
                        <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="mt-2 block text-center py-2.5 px-4 bg-primary-700 text-white text-xs font-bold rounded-xl hover:bg-primary-600 transition shadow-2xs" wire:navigate>
                            + Tugaskan Asesor Sekarang
                        </a>
                    @endif
                @endforelse
            </div>

            <!-- Card 2: Tujuan Komite Etik -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="material-symbols-outlined text-primary-700 text-[18px]">health_and_safety</span>
                    <span class="font-display text-xs font-bold text-slate-800 uppercase tracking-wider">Komite Etik (KEPK)</span>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-sm leading-snug">{{ $suratPengajuan->kepk->name ?? '-' }}</div>
                    <div class="text-xs text-slate-500 mt-1">Institusi: {{ $suratPengajuan->kepk->institusi->name ?? '-' }}</div>
                    <div class="text-xs font-mono text-slate-400 mt-0.5">Kode Registrasi: {{ $suratPengajuan->kepk->code ?? '-' }}</div>
                </div>
            </div>

            <!-- Card 3: Informasi Meta Pengajuan -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-3 text-xs">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="material-symbols-outlined text-primary-700 text-[18px]">info</span>
                    <span class="font-display text-xs font-bold text-slate-800 uppercase tracking-wider">Informasi Berkas</span>
                </div>
                <div class="space-y-2.5">
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
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold border whitespace-nowrap shrink-0 {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                            <span class="material-symbols-outlined text-[13px]">{{ \App\Models\SuratPengajuan::statusIcon($suratPengajuan->status) }}</span>
                            <span>{{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>