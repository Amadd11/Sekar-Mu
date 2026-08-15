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
                <h1 class="page-title text-xl">Profil & Anggota KEPK</h1>
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
        <!-- Navigation Tabs -->
        <div class="card p-2 bg-white">
            <div class="flex flex-wrap gap-1">
                <a href="{{ route('applications.information', $application) }}" class="px-4 py-2 text-xs font-medium rounded-lg text-slate-600 hover:bg-slate-100" wire:navigate>
                    1. Informasi Umum
                </a>
                <a href="{{ route('applications.profile', $application) }}" class="px-4 py-2 text-xs font-semibold rounded-lg bg-primary-700 text-white" wire:navigate>
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

        <!-- Section 1: Profil KEPK (Visi, Misi, Deskripsi) -->
        <form wire:submit="saveProfile" class="space-y-6">
            <div class="card">
                <div class="card-header bg-slate-50">
                    <h2 class="text-base font-semibold text-slate-800">Visi, Misi & Gambaran Umum KEPK</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tuliskan profil singkat, visi, dan misi komite etik penelitian kesehatan Anda.</p>
                </div>
                <div class="card-body space-y-4">
                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi / Gambaran Umum</label>
                        <textarea id="description" wire:model="description" rows="3" class="form-textarea" placeholder="Gambaran umum komite etik, sejarah singkat, atau lingkup kerja..."></textarea>
                        @error('description') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="vision">Visi KEPK</label>
                            <textarea id="vision" wire:model="vision" rows="3" class="form-textarea" placeholder="Menjadi komite etik penelitian kesehatan yang terpercaya dan unggul..."></textarea>
                            @error('vision') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="mission">Misi KEPK</label>
                            <textarea id="mission" wire:model="mission" rows="3" class="form-textarea" placeholder="1. Menegakkan prinsip etik penelitian...&#10;2. Memberikan layanan telaah cepat dan tepat..."></textarea>
                            @error('mission') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer flex items-center justify-between">
                    <span class="text-xs text-slate-400">Pastikan profil terisi dengan lengkap.</span>
                    <button type="submit" class="btn-primary btn-sm">
                        Simpan Profil
                    </button>
                </div>
            </div>
        </form>

        <!-- Section 2: Anggota KEPK -->
        <div class="card">
            <div class="card-header bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Susunan Anggota KEPK</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar ketua, sekretaris, dan anggota penelaah etik yang bertugas.</p>
                </div>
                <span class="badge bg-slate-100 text-slate-700">
                    Total: {{ $application->members->count() }} Anggota
                </span>
            </div>

            @if (session('member_status'))
                <div class="p-4 bg-green-50 text-green-800 border-b border-green-200 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('member_status') }}</span>
                </div>
            @endif

            <div class="p-6 space-y-6">
                <!-- Form Tambah Anggota -->
                <form wire:submit="addMember" class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tambah Anggota Baru</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="form-group">
                            <label class="form-label text-xs">Nama Lengkap & Gelar *</label>
                            <input type="text" wire:model="new_member_name" class="form-input text-xs" placeholder="Dr. dr. Ahmad, Sp.A" />
                            @error('new_member_name') <span class="form-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label text-xs">Jabatan di KEPK</label>
                            <input type="text" wire:model="new_member_position" class="form-input text-xs" placeholder="Ketua / Sekretaris / Penelaah" />
                            @error('new_member_position') <span class="form-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label text-xs">Email</label>
                            <input type="email" wire:model="new_member_email" class="form-input text-xs" placeholder="email@domain.com" />
                            @error('new_member_email') <span class="form-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label text-xs">No. Telepon / HP</label>
                            <input type="text" wire:model="new_member_phone" class="form-input text-xs" placeholder="08123456789" />
                            @error('new_member_phone') <span class="form-error text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button type="submit" class="btn-secondary btn-sm gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Anggota
                        </button>
                    </div>
                </form>

                <!-- Daftar Anggota Table -->
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Jabatan</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($application->members as $index => $member)
                                <tr>
                                    <td class="text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="font-semibold text-slate-900">{{ $member->name }}</td>
                                    <td>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $member->position ?? 'Anggota' }}
                                        </span>
                                    </td>
                                    <td class="text-slate-500 text-xs">{{ $member->email ?? '-' }}</td>
                                    <td class="text-slate-500 text-xs">{{ $member->phone ?? '-' }}</td>
                                    <td class="text-right">
                                        <button
                                            type="button"
                                            wire:click="deleteMember({{ $member->id }})"
                                            wire:confirm="Yakin ingin menghapus anggota ini?"
                                            class="text-xs text-red-600 hover:text-red-800 font-semibold"
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-slate-400 text-xs">
                                        Belum ada anggota yang didaftarkan. Gunakan form di atas untuk menambahkan anggota KEPK.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer flex items-center justify-between">
                <a href="{{ route('applications.information', $application) }}" class="btn-outline btn-sm" wire:navigate>
                    &larr; Kembali ke Informasi
                </a>
                <a href="{{ route('applications.show', $application) }}" class="btn-primary btn-sm gap-1.5" wire:navigate>
                    <span>Lanjut ke Ringkasan & Status</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
