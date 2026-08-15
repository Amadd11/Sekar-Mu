<div>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title text-xl">Daftar Pengajuan Etik</h1>
                <p class="page-subtitle">Kelola dan pantau seluruh permohonan akreditasi dan telaah protokol etik.</p>
            </div>
            @can('create', App\Models\Application::class)
                <a href="{{ route('applications.create') }}" class="btn-primary gap-2" wire:navigate>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Pengajuan Baru
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter & Search Bar -->
        <div class="card p-4">
            <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="w-full sm:w-80 relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama institusi, KEPK..."
                        class="form-input ps-9 text-sm"
                    />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="w-full sm:w-auto flex items-center gap-2">
                    <label class="text-xs text-slate-500 font-medium whitespace-nowrap">Filter Status:</label>
                    <select wire:model.live="statusFilter" class="form-select text-xs w-full sm:w-48">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $st)
                            <option value="{{ $st }}">{{ App\Models\Application::statusLabel($st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Table List -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Pengajuan</th>
                            <th>Nama Institusi / Pemohon</th>
                            <th>Komite Etik (KEPK)</th>
                            <th>Status</th>
                            <th>Tanggal Diajukan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $app)
                            <tr>
                                <td class="font-mono text-xs font-semibold text-slate-600">
                                    #APP-{{ str_pad($app->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    <div class="font-semibold text-slate-900">
                                        {{ $app->information->name ?? 'Belum Diisi' }}
                                    </div>
                                    @if ($app->information?->city)
                                        <div class="text-xs text-slate-400">{{ $app->information->city }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-slate-800 font-medium">
                                        {{ $app->kepk->name ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono">
                                        Kode: {{ $app->kepk->code ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ App\Models\Application::statusBadgeClasses($app->status) }}">
                                        {{ App\Models\Application::statusLabel($app->status) }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $app->submitted_at ? $app->submitted_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('applications.show', $app) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                                            Detail
                                        </a>
                                        @can('update', $app)
                                            <a href="{{ route('applications.information', $app) }}" class="btn-primary btn-sm text-xs" wire:navigate>
                                                Edit
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">Belum ada pengajuan etik.</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik tombol "Buat Pengajuan Baru" untuk memulai formulir pendaftaran.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($applications->hasPages())
                <div class="card-footer">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
