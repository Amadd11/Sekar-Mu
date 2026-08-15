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
                        Edit Data
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

        <!-- Status Banner & Submit CTA -->
        @if ($application->isEditable())
            <div class="bg-gradient-to-r from-teal-50 to-emerald-50 border border-teal-200 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="font-bold text-teal-900 text-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pengajuan masih dalam tahap {{ $application->isDraft() ? 'Draft' : 'Perbaikan Revisi' }}
                    </div>
                    <p class="text-xs text-teal-700 mt-1">
                        Periksa kembali seluruh informasi dan anggota sebelum menekan tombol submit pengajuan.
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
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-blue-900 text-sm">Pengajuan Telah Dikirim</div>
                        <p class="text-xs text-blue-700 mt-0.5">
                            Status saat ini: <strong>{{ App\Models\Application::statusLabel($application->status) }}</strong> pada {{ $application->submitted_at?->format('d M Y, H:i') }}.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="md:col-span-2 space-y-6">
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

            <!-- Right Column: Sidebar Meta -->
            <div class="space-y-6">
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
