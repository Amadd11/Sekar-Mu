<div>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="badge {{ App\Models\Application::statusBadgeClasses($application->status) }}">
                        {{ App\Models\Application::statusLabel($application->status) }}
                    </span>
                </div>
                <h1 class="page-title text-xl">{{ $application->information->name ?? 'Pengajuan Etik' }}</h1>
            </div>
            <div class="flex items-center gap-2">
                @can('update', $application)
                    <a href="{{ route('applications.information', $application) }}" class="btn-outline btn-sm" wire:navigate>
                        Edit Identitas
                    </a>
                @endcan
                <a href="{{ route('applications.index') }}" class="btn-ghost btn-sm" wire:navigate>
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        @if (session('status'))
            <div class="alert alert-success">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Status Banner & Submit / Resubmit CTA -->
        @if ($application->isDraft())
            <div class="bg-gradient-to-r from-teal-50 to-emerald-50 border border-teal-200 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="font-bold text-teal-900 text-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pengajuan masih dalam tahap Draft
                    </div>
                    <p class="text-xs text-teal-700 mt-1">
                        Lengkapi borang evaluasi diri, protokol penelitian, dan dokumen lampiran sebelum submit.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @can('delete', $application)
                        <button
                            type="button"
                            wire:click="deleteApplication"
                            wire:confirm="Yakin ingin menghapus draft pengajuan ini secara permanen?"
                            class="btn-outline btn-sm text-red-600 border-red-200 hover:bg-red-50"
                        >
                            Hapus Draft
                        </button>
                    @endcan
                    @can('submit', $application)
                        <button
                            type="button"
                            wire:click="submitApplication"
                            wire:confirm="Apakah Anda yakin ingin mengajukan berkas etik ini untuk ditelaah? Setelah diajukan, data akan terkunci."
                            class="btn-primary btn-sm gap-1.5 font-bold"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit Pengajuan
                        </button>
                    @endcan
                </div>
            </div>
        @elseif ($application->isRevisionRequired())
            <div class="bg-amber-50 border border-amber-300 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="font-bold text-amber-900 text-sm flex items-center gap-1.5">
                        <span>⚠️ Status: Membutuhkan Perbaikan / Revisi Berkas</span>
                    </div>
                    <p class="text-xs text-amber-700 mt-1">
                        Penelaah etik telah memberikan catatan telaah. Silakan perbaiki data/dokumen terkait dan klik tombol ajukan ulang.
                    </p>
                </div>
                @can('submit', $application)
                    <button
                        type="button"
                        wire:click="resubmitApplication"
                        wire:confirm="Apakah seluruh perbaikan revisi sudah selesai dan Anda yakin ingin mengajukan ulang berkas ini?"
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white font-bold text-xs rounded-xl shadow-2xs transition shrink-0"
                    >
                        🔄 Ajukan Ulang Perbaikan (Resubmit)
                    </button>
                @endcan
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-blue-900 text-sm">Status Berkas: {{ App\Models\Application::statusLabel($application->status) }}</div>
                        <p class="text-xs text-blue-700 mt-0.5">
                            Diajukan pada {{ $application->submitted_at?->format('d M Y, H:i') ?? '-' }}.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Admin Management Action Bar -->
        @if (auth()->user()->isAdmin())
            <div class="bg-slate-900 text-white rounded-xl p-5 shadow-2xs space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-teal-400">Panel Kontrol Admin KEPK</div>
                        <div class="text-xs text-slate-300 mt-0.5">Kelola penugasan penelaah independen dan penetapan status akhir permohonan etik.</div>
                    </div>
                    <a
                        href="{{ route('reviews.assign', $application) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-lg transition"
                        wire:navigate
                    >
                        <span>👥 Tugaskan Penelaah</span>
                    </a>
                </div>

                @if (!in_array($application->status, ['draft'], true))
                    <div class="pt-3 border-t border-slate-800 flex flex-wrap items-center gap-2">
                        <span class="text-xs text-slate-400 font-semibold mr-1">Putuskan Status Akhir:</span>
                        <button
                            type="button"
                            wire:click="finalizeDecision('approved')"
                            wire:confirm="Yakin ingin MENYETUJUI (Approve) permohonan etik ini?"
                            class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-bold transition"
                        >
                            ✓ Setujui (Approved)
                        </button>
                        <button
                            type="button"
                            wire:click="finalizeDecision('revision_required')"
                            wire:confirm="Yakin ingin MEMINTA REVISI kepada pemohon?"
                            class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs font-bold transition"
                        >
                            ⚠️ Minta Revisi
                        </button>
                        <button
                            type="button"
                            wire:click="finalizeDecision('rejected')"
                            wire:confirm="Yakin ingin MENOLAK (Reject) permohonan etik ini?"
                            class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-xs font-bold transition"
                        >
                            ✕ Tolak (Rejected)
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Quick 3 Navigation Modules Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('applications.self-assessment', $application) }}" class="bg-white border border-slate-200/90 hover:border-teal-400 rounded-xl p-4 shadow-2xs transition group" wire:navigate>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Evaluasi Diri (B01-03)</span>
                    <span class="text-base group-hover:translate-x-1 transition">📋</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Borang standar Bagian A s/d E</p>
            </a>

            <a href="{{ route('applications.protocols', $application) }}" class="bg-white border border-slate-200/90 hover:border-teal-400 rounded-xl p-4 shadow-2xs transition group" wire:navigate>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">List Protokol (B01-04)</span>
                    <span class="text-base group-hover:translate-x-1 transition">📑</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Kelola protokol riset yang diajukan</p>
            </a>

            <a href="{{ route('applications.documents', $application) }}" class="bg-white border border-slate-200/90 hover:border-teal-400 rounded-xl p-4 shadow-2xs transition group" wire:navigate>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Dokumen Lampiran</span>
                    <span class="text-base group-hover:translate-x-1 transition">📁</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Upload & unduh berkas lampiran</p>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="md:col-span-2 space-y-6">
                <!-- Reviews Summary Card (If Any) -->
                @if ($application->reviews->isNotEmpty())
                    <div class="card">
                        <div class="card-header bg-slate-50 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Hasil Telaah Penelaah Etik ({{ $application->reviews->count() }})</h2>
                        </div>
                        <div class="p-5 space-y-3 text-xs">
                            @foreach ($application->reviews as $rev)
                                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-900">👤 {{ $rev->reviewer->name }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ \App\Models\Review::recommendationBadgeClasses($rev->recommendation) }}">
                                            {{ \App\Models\Review::recommendationLabel($rev->recommendation) }}
                                        </span>
                                    </div>
                                    @if ($rev->notes)
                                        <p class="text-slate-600 leading-relaxed">{{ $rev->notes }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Info Card -->
                <div class="card">
                    <div class="card-header bg-slate-50 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">1. Identitas Institusi</h2>
                        @can('update', $application)
                            <a href="{{ route('applications.information', $application) }}" class="text-xs text-primary-700 hover:underline font-semibold" wire:navigate>Edit</a>
                        @endcan
                    </div>
                    <div class="card-body p-5">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <dt class="text-slate-400 font-medium">Nama Institusi</dt>
                                <dd class="text-slate-900 font-semibold mt-0.5">{{ $application->information->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400 font-medium">Singkatan</dt>
                                <dd class="text-slate-800 font-semibold mt-0.5">{{ $application->information->abbreviation ?? '-' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-slate-400 font-medium">Alamat</dt>
                                <dd class="text-slate-700 mt-0.5">{{ $application->information->address ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400 font-medium">Kota / Kabupaten</dt>
                                <dd class="text-slate-800 font-semibold mt-0.5">{{ $application->information->city ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400 font-medium">Kontak (Telepon / Email)</dt>
                                <dd class="text-slate-700 mt-0.5">{{ $application->information->phone ?? '-' }} / {{ $application->information->email ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="card">
                    <div class="card-header bg-slate-50 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">2. Visi & Misi KEPK</h2>
                        @can('update', $application)
                            <a href="{{ route('applications.profile', $application) }}" class="text-xs text-primary-700 hover:underline font-semibold" wire:navigate>Edit</a>
                        @endcan
                    </div>
                    <div class="card-body p-5 space-y-4 text-xs">
                        <div>
                            <div class="text-slate-400 font-medium">Deskripsi / Gambaran Umum:</div>
                            <p class="text-slate-700 mt-1 whitespace-pre-line">{{ $application->profile->description ?? 'Belum diisi.' }}</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                            <div>
                                <div class="text-slate-400 font-medium">Visi KEPK:</div>
                                <p class="text-slate-800 mt-1 whitespace-pre-line">{{ $application->profile->vision ?? 'Belum diisi.' }}</p>
                            </div>
                            <div>
                                <div class="text-slate-400 font-medium">Misi KEPK:</div>
                                <p class="text-slate-800 mt-1 whitespace-pre-line">{{ $application->profile->mission ?? 'Belum diisi.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Members Card -->
                <div class="card">
                    <div class="card-header bg-slate-50 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">3. Anggota KEPK ({{ $application->members->count() }})</h2>
                        @can('update', $application)
                            <a href="{{ route('applications.profile', $application) }}" class="text-xs text-primary-700 hover:underline font-semibold" wire:navigate>Kelola Anggota</a>
                        @endcan
                    </div>
                    <div class="overflow-x-auto">
                        <table class="data-table text-xs">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jabatan</th>
                                    <th>Kontak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($application->members as $idx => $mbr)
                                    <tr>
                                        <td class="text-slate-400">{{ $idx + 1 }}</td>
                                        <td class="font-semibold text-slate-900">{{ $mbr->name }}</td>
                                        <td><span class="badge bg-slate-100 text-slate-700">{{ $mbr->position ?? 'Anggota' }}</span></td>
                                        <td class="text-slate-500">{{ $mbr->email ?? '-' }} ({{ $mbr->phone ?? '-' }})</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-slate-400">Belum ada anggota yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Meta & Assigned Reviewers -->
            <div class="space-y-6">
                <!-- Assigned Reviewers Card -->
                <div class="card p-5 space-y-3">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Penelaah Etik Ditugaskan</div>
                    @forelse ($application->assignedReviewers as $rev)
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
                        <p class="text-xs text-slate-400 italic">Belum ada penelaah ditugaskan.</p>
                    @endforelse
                </div>

                <!-- KEPK Destination Card -->
                <div class="card p-5 space-y-3">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tujuan Pengajuan</div>
                    <div class="font-bold text-slate-900 text-sm">
                        {{ $application->kepk->name ?? '-' }}
                    </div>
                    <div class="text-xs text-slate-500">
                        Institusi: {{ $application->kepk->institution->name ?? '-' }}
                    </div>
                    <div class="text-xs font-mono text-slate-400">
                        Kode KEPK: {{ $application->kepk->code ?? '-' }}
                    </div>
                </div>

                <!-- Submission Meta -->
                <div class="card p-5 space-y-3 text-xs">
                    <div class="font-bold text-slate-400 uppercase tracking-wider">Informasi Meta</div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Dibuat oleh:</span>
                        <span class="font-semibold text-slate-800">{{ $application->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Tanggal Buat:</span>
                        <span class="text-slate-700">{{ $application->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Status Terkini:</span>
                        <span class="font-semibold text-slate-800">{{ App\Models\Application::statusLabel($application->status) }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Terakhir Update:</span>
                        <span class="text-slate-700">{{ $application->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
