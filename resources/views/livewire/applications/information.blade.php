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
                <h1 class="page-title text-xl">Kelola Informasi Pengajuan</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('applications.show', $application) }}" class="btn-outline btn-sm" wire:navigate>
                    Lihat Ringkasan
                </a>
                <a href="{{ route('applications.index') }}" class="btn-ghost btn-sm" wire:navigate>
                    Daftar Pengajuan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Navigation Tabs / Stepper -->
        <div class="card p-2 bg-white">
            <div class="flex flex-wrap gap-1">
                <a href="{{ route('applications.information', $application) }}" class="px-4 py-2 text-xs font-semibold rounded-lg bg-primary-700 text-white" wire:navigate>
                    1. Informasi Umum
                </a>
                <a href="{{ route('applications.profile', $application) }}" class="px-4 py-2 text-xs font-medium rounded-lg text-slate-600 hover:bg-slate-100" wire:navigate>
                    2. Profil & Anggota KEPK
                </a>
                <a href="{{ route('applications.show', $application) }}" class="px-4 py-2 text-xs font-medium rounded-lg text-slate-600 hover:bg-slate-100" wire:navigate>
                    3. Ringkasan & Status
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">
            <div class="card">
                <div class="card-header bg-slate-50">
                    <h2 class="text-base font-semibold text-slate-800">Detail Informasi Institusi / Pemohon</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pastikan data kontak dan alamat sesuai dengan identitas resmi lembaga Anda.</p>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 form-group">
                            <label class="form-label" for="name">
                                Nama Institusi / Rumah Sakit / Fakultas <span class="text-red-500">*</span>
                            </label>
                            <input id="name" type="text" wire:model="name" class="form-input" />
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="abbreviation">Singkatan</label>
                            <input id="abbreviation" type="text" wire:model="abbreviation" class="form-input" />
                            @error('abbreviation') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Alamat Lengkap</label>
                        <textarea id="address" wire:model="address" rows="3" class="form-textarea"></textarea>
                        @error('address') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="city">Kota / Kabupaten</label>
                            <input id="city" type="text" wire:model="city" class="form-input" />
                            @error('city') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Nomor Telepon</label>
                            <input id="phone" type="text" wire:model="phone" class="form-input" />
                            @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email Resmi</label>
                            <input id="email" type="email" wire:model="email" class="form-input" />
                            @error('email') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer flex items-center justify-between">
                    <span class="text-xs text-slate-400">Data tersimpan otomatis ke database.</span>
                    <button type="submit" class="btn-primary gap-2">
                        <span>Simpan & Lanjut ke Profil</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
