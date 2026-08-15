<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title text-xl">Buat Pengajuan Etik Baru</h1>
                <p class="page-subtitle">Langkah 1: Tentukan Komite Etik (KEPK) tujuan dan identitas dasar institusi.</p>
            </div>
            <a href="{{ route('applications.index') }}" class="btn-outline btn-sm" wire:navigate>
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Flow Stepper -->
        <div class="card p-4 bg-white">
            <div class="flex items-center justify-between">
                <div class="stepper-step active">
                    <span class="w-7 h-7 rounded-full bg-primary-700 text-white flex items-center justify-center font-bold text-xs">1</span>
                    <span class="hidden sm:inline font-semibold">Informasi Dasar</span>
                </div>
                <div class="stepper-divider"></div>
                <div class="stepper-step">
                    <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-semibold text-xs">2</span>
                    <span class="hidden sm:inline">Profil KEPK</span>
                </div>
                <div class="stepper-divider"></div>
                <div class="stepper-step">
                    <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-semibold text-xs">3</span>
                    <span class="hidden sm:inline">Ringkasan & Submit</span>
                </div>
            </div>
        </div>

        <form wire:submit="save" class="space-y-6">
            <!-- Section 1: KEPK Selection -->
            <div class="card">
                <div class="card-header bg-slate-50 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary-100 text-primary-800 flex items-center justify-center font-bold text-xs">A</span>
                    <h2 class="text-base font-semibold text-slate-800">Pilih Komite Etik (KEPK) Tujuan</h2>
                </div>
                <div class="card-body space-y-4">
                    <div class="form-group">
                        <label class="form-label" for="kepk_id">
                            Komite Etik Penelitian Kesehatan (KEPK) <span class="text-red-500">*</span>
                        </label>
                        <select id="kepk_id" wire:model="kepk_id" class="form-select">
                            <option value="">-- Pilih KEPK --</option>
                            @foreach ($kepks as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->name }} ({{ $k->institution->name ?? '-' }}) — Kode: {{ $k->code }}
                                </option>
                            @endforeach
                        </select>
                        @error('kepk_id') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: General Information -->
            <div class="card">
                <div class="card-header bg-slate-50 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary-100 text-primary-800 flex items-center justify-center font-bold text-xs">B</span>
                    <h2 class="text-base font-semibold text-slate-800">Informasi Pemohon / Institusi</h2>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 form-group">
                            <label class="form-label" for="name">
                                Nama Institusi / Rumah Sakit / Fakultas <span class="text-red-500">*</span>
                            </label>
                            <input id="name" type="text" wire:model="name" class="form-input" placeholder="Contoh: Universitas Muhammadiyah Surakarta" />
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="abbreviation">Singkatan</label>
                            <input id="abbreviation" type="text" wire:model="abbreviation" class="form-input" placeholder="Contoh: UMS" />
                            @error('abbreviation') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Alamat Lengkap</label>
                        <textarea id="address" wire:model="address" rows="2" class="form-textarea" placeholder="Jl. A. Yani, Pabelan, Kartasura..."></textarea>
                        @error('address') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="city">Kota / Kabupaten</label>
                            <input id="city" type="text" wire:model="city" class="form-input" placeholder="Surakarta" />
                            @error('city') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Nomor Telepon</label>
                            <input id="phone" type="text" wire:model="phone" class="form-input" placeholder="0271-717417" />
                            @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email Resmi</label>
                            <input id="email" type="email" wire:model="email" class="form-input" placeholder="kepk@ums.ac.id" />
                            @error('email') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('applications.index') }}" class="btn-outline" wire:navigate>
                    Batal
                </a>
                <button type="submit" class="btn-primary gap-2">
                    <span>Lanjutkan ke Tahap Profil</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
