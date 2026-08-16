<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                    {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">{{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'Surat Pengajuan' }}</h1>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $suratPengajuan)
                <a href="{{ route('pengajuan.formulir-aplikasi', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                    Edit Formulir
                </a>
            @endcan
            <a href="{{ route('pengajuan.index') }}" class="btn-ghost btn-sm text-xs" wire:navigate>
                &larr; Kembali
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✕ {{ session('error') }}</span>
        </div>
    @endif

    <!-- Status Banner & Submit Action -->
    @if ($suratPengajuan->isDraft())
        <div class="bg-gradient-to-r from-teal-50 to-emerald-50 border border-teal-200 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="font-bold text-teal-900 text-sm flex items-center gap-1.5">
                    <span>📄 Status: Masih Draft</span>
                </div>
                <p class="text-xs text-teal-700 mt-1">
                    Lengkapi seluruh borang evaluasi diri (Bagian A s/d E), list protokol, dan dokumen lampiran sebelum submit.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @can('delete', $suratPengajuan)
                    <button
                        type="button"
                        wire:click="hapusDraft"
                        wire:confirm="Yakin ingin menghapus draft surat pengajuan ini secara permanen?"
                        class="px-3 py-2 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition"
                    >
                        Hapus Draft
                    </button>
                @endcan
                @can('submit', $suratPengajuan)
                    <button
                        type="button"
                        wire:click="ajukanBerkas"
                        wire:confirm="Apakah Anda yakin ingin mengajukan berkas pengajuan etik ini untuk dinilai?"
                        class="px-4 py-2 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-bold text-xs rounded-lg shadow-2xs transition"
                    >
                        ✓ Submit Pengajuan
                    </button>
                @endcan
            </div>
        </div>
    @elseif ($suratPengajuan->isRevisionRequired())
        <div class="bg-amber-50 border border-amber-300 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="font-bold text-amber-900 text-sm">
                    ⚠️ Status: Membutuhkan Perbaikan Berkas
                </div>
                <p class="text-xs text-amber-700 mt-1">
                    Penilai etik telah memberikan catatan perbaikan. Perbarui data lalu klik tombol ajukan ulang.
                </p>
            </div>
            @can('submit', $suratPengajuan)
                <button
                    type="button"
                    wire:click="ajukanBerkas"
                    wire:confirm="Yakin ingin mengajukan ulang berkas perbaikan ini?"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow-2xs transition shrink-0"
                >
                    🔄 Ajukan Ulang Perbaikan (Resubmit)
                </button>
            @endcan
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 flex items-center justify-between">
            <div>
                <div class="font-bold text-blue-900 text-sm">Status Berkas: {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}</div>
                <p class="text-xs text-blue-700 mt-0.5">
                    Diajukan pada {{ $suratPengajuan->diajukan_pada?->format('d M Y, H:i') ?? '-' }}.
                </p>
            </div>
        </div>
    @endif

    <!-- Admin Control Panel -->
    @if (auth()->user()->isAdmin())
        <div class="bg-slate-900 text-white rounded-xl p-5 shadow-2xs space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-teal-400">Panel Kontrol Admin KEPK</div>
                    <div class="text-xs text-slate-300 mt-0.5">Kelola penugasan penilai independen dan penetapan status akhir permohonan etik.</div>
                </div>
                <a
                    href="{{ route('penilaian.tugaskan', $suratPengajuan) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-lg transition"
                    wire:navigate
                >
                    <span>👥 Tugaskan Penilai</span>
                </a>
            </div>

            @if (!in_array($suratPengajuan->status, ['draft'], true))
                <div class="pt-3 border-t border-slate-800 flex flex-wrap items-center gap-2">
                    <span class="text-xs text-slate-400 font-semibold mr-1">Putuskan Status Akhir:</span>
                    <button
                        type="button"
                        wire:click="putuskanStatus('approved')"
                        wire:confirm="Yakin ingin MENYETUJUI (Approve) permohonan etik ini?"
                        class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-bold transition"
                    >
                        ✓ Setujui (Approved)
                    </button>
                    <button
                        type="button"
                        wire:click="putuskanStatus('revision_required')"
                        wire:confirm="Yakin ingin MEMINTA PERBAIKAN kepada pemohon?"
                        class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs font-bold transition"
                    >
                        ⚠️ Minta Perbaikan
                    </button>
                    <button
                        type="button"
                        wire:click="putuskanStatus('rejected')"
                        wire:confirm="Yakin ingin MENOLAK (Reject) permohonan etik ini?"
                        class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-xs font-bold transition"
                    >
                        ✕ Tolak (Rejected)
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- 3 Quick Access Navigation Modules -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="bg-white border border-slate-200/90 hover:border-teal-400 rounded-xl p-4 shadow-2xs transition group" wire:navigate>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">Evaluasi Diri (B01-03)</span>
                <span class="text-base group-hover:translate-x-1 transition">📋</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Borang standar Bagian A s/d E</p>
        </a>

        <a href="{{ route('pengajuan.list-protokol', $suratPengajuan) }}" class="bg-white border border-slate-200/90 hover:border-teal-400 rounded-xl p-4 shadow-2xs transition group" wire:navigate>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">List Protokol (B01-04)</span>
                <span class="text-base group-hover:translate-x-1 transition">📑</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Kelola protokol riset yang diajukan</p>
        </a>

        <a href="{{ route('pengajuan.dokumen', $suratPengajuan) }}" class="bg-white border border-slate-200/90 hover:border-teal-400 rounded-xl p-4 shadow-2xs transition group" wire:navigate>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">Dokumen Lampiran</span>
                <span class="text-base group-hover:translate-x-1 transition">📁</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Upload & unduh berkas lampiran</p>
        </a>
    </div>

    <!-- 2 Cols Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left: Details -->
        <div class="md:col-span-2 space-y-6">
            <!-- Results Penilaian (If Any) -->
            @if ($suratPengajuan->penilaianEtik->isNotEmpty())
                <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Hasil Penilaian Penilai Etik</h3>
                    <div class="space-y-3">
                        @foreach ($suratPengajuan->penilaianEtik as $t)
                            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">👤 {{ $t->penilai->name }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ \App\Models\PenilaianEtik::badgeRekomendasi($t->rekomendasi) }}">
                                        {{ \App\Models\PenilaianEtik::labelRekomendasi($t->rekomendasi) }}
                                    </span>
                                </div>
                                @if ($t->catatan)
                                    <p class="text-slate-600 leading-relaxed">{{ $t->catatan }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 1. Formulir Aplikasi (Identitas) -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">1. Identitas Institusi</h3>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.formulir-aplikasi', $suratPengajuan) }}" class="text-xs text-teal-700 font-semibold hover:underline" wire:navigate>Edit</a>
                    @endcan
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <dt class="text-slate-400 font-medium">Nama Institusi</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $suratPengajuan->formulirAplikasi->nama_institusi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Singkatan</dt>
                        <dd class="text-slate-800 font-semibold mt-0.5">{{ $suratPengajuan->formulirAplikasi->singkatan ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400 font-medium">Alamat</dt>
                        <dd class="text-slate-700 mt-0.5">{{ $suratPengajuan->formulirAplikasi->alamat ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Kota / Kabupaten</dt>
                        <dd class="text-slate-800 font-semibold mt-0.5">{{ $suratPengajuan->formulirAplikasi->kota ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Kontak</dt>
                        <dd class="text-slate-700 mt-0.5">{{ $suratPengajuan->formulirAplikasi->telepon ?? '-' }} / {{ $suratPengajuan->formulirAplikasi->email ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- 2. Profil KEPK -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">2. Visi & Misi KEPK</h3>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.profil', $suratPengajuan) }}" class="text-xs text-teal-700 font-semibold hover:underline" wire:navigate>Edit</a>
                    @endcan
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <div class="text-slate-400 font-medium">Deskripsi / Gambaran Umum:</div>
                        <p class="text-slate-700 mt-1 whitespace-pre-line">{{ $suratPengajuan->profilKepk->deskripsi ?? 'Belum diisi.' }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <div class="text-slate-400 font-medium">Visi KEPK:</div>
                            <p class="text-slate-800 mt-1 whitespace-pre-line">{{ $suratPengajuan->profilKepk->visi ?? 'Belum diisi.' }}</p>
                        </div>
                        <div>
                            <div class="text-slate-400 font-medium">Misi KEPK:</div>
                            <p class="text-slate-800 mt-1 whitespace-pre-line">{{ $suratPengajuan->profilKepk->misi ?? 'Belum diisi.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Anggota KEPK -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">3. Anggota KEPK ({{ $suratPengajuan->anggotaKepk->count() }})</h3>
                    @can('update', $suratPengajuan)
                        <a href="{{ route('pengajuan.profil', $suratPengajuan) }}" class="text-xs text-teal-700 font-semibold hover:underline" wire:navigate>Kelola Anggota</a>
                    @endcan
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Nama Lengkap</th>
                                <th class="px-3 py-2">Jabatan</th>
                                <th class="px-3 py-2">Kontak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($suratPengajuan->anggotaKepk as $idx => $m)
                                <tr>
                                    <td class="px-3 py-2 text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="px-3 py-2 font-semibold text-slate-900">{{ $m->nama }}</td>
                                    <td class="px-3 py-2 text-slate-700">{{ $m->jabatan ?? 'Anggota' }}</td>
                                    <td class="px-3 py-2 text-slate-500">{{ $m->email ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-4 text-center text-slate-400">Belum ada anggota yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Meta -->
        <div class="space-y-6">
            <!-- Penilai Ditugaskan -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-3">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Penilai Etik Ditugaskan</div>
                @forelse ($suratPengajuan->penilai as $rev)
                    <div class="flex items-center gap-2 text-xs">
                        <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-[10px]">
                            {{ strtoupper(substr($rev->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">{{ $rev->name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $rev->email }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Belum ada penilai ditugaskan.</p>
                @endforelse
            </div>

            <!-- Tujuan KEPK -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-2">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tujuan Pengajuan</div>
                <div class="font-bold text-slate-900 text-sm">{{ $suratPengajuan->kepk->name ?? '-' }}</div>
                <div class="text-xs text-slate-500">Institusi: {{ $suratPengajuan->kepk->institusi->name ?? '-' }}</div>
                <div class="text-xs font-mono text-slate-400">Kode: {{ $suratPengajuan->kepk->code ?? '-' }}</div>
            </div>

            <!-- Submission Meta -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-3 text-xs">
                <div class="font-bold text-slate-400 uppercase tracking-wider">Informasi Meta</div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Dibuat oleh:</span>
                    <span class="font-semibold text-slate-800">{{ $suratPengajuan->user->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Tanggal Buat:</span>
                    <span class="text-slate-700">{{ $suratPengajuan->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Status Terkini:</span>
                    <span class="font-semibold text-slate-800">{{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
