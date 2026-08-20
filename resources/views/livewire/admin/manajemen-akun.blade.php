<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Header Banner -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden">
        <!-- Radial Glow -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="text-xl select-none">👥</span>
                <span class="bg-primary-50 text-primary-700 text-xs font-bold px-2.5 py-0.5 rounded-md border border-primary-200/70">
                    Administrator
                </span>
            </div>
            <h1 class="font-display text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">
                Manajemen Akun & Hak Akses Pengguna
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                Kelola akun Asesor Penilai, Pemohon KEPK, Ketua/Anggota Komite, serta Administrator sistem Sekar-Mu.
            </p>
        </div>

        <div class="relative z-10">
            <button
                type="button"
                wire:click="bukaModalCreate"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
            >
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>+ Tambah Akun Pengguna</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
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

    <!-- 2. KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pengguna</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                </span>
            </div>
            <div class="text-2xl font-black text-slate-900 font-display mt-2">{{ $totalUsers }}</div>
            <p class="text-[11px] text-slate-400 mt-0.5">Semua akun terdaftar</p>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Asesor Penilai</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">clinical_notes</span>
                </span>
            </div>
            <div class="text-2xl font-black text-blue-800 font-display mt-2">{{ $totalReviewers }}</div>
            <p class="text-[11px] text-slate-400 mt-0.5">Reviewer independen</p>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pemohon / KEPK</span>
                <span class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">health_and_safety</span>
                </span>
            </div>
            <div class="text-2xl font-black text-teal-800 font-display mt-2">{{ $totalApplicants }}</div>
            <p class="text-[11px] text-slate-400 mt-0.5">Ketua & Anggota KEPK</p>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Administrator</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">shield_person</span>
                </span>
            </div>
            <div class="text-2xl font-black text-purple-800 font-display mt-2">{{ $totalAdmins }}</div>
            <p class="text-[11px] text-slate-400 mt-0.5">Super Admin KEPK</p>
        </div>
    </div>

    <!-- 3. Filters & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <!-- Search Input -->
        <div class="w-full sm:flex-1 relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama pengguna, alamat email..."
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 ps-10 pe-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
            />
            <span class="material-symbols-outlined text-slate-400 absolute left-3 top-2.5 text-[18px]">search</span>
        </div>

        <!-- Filter Role -->
        <div class="w-full sm:w-56">
            <select
                wire:model.live="roleFilter"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium"
            >
                <option value="">Semua Peran / Role</option>
                <option value="admin">Administrator</option>
                <option value="reviewer">Asesor / Reviewer</option>
                <option value="ketua_kepk">Ketua KEPK</option>
                <option value="anggota_kepk">Anggota KEPK</option>
                <option value="applicant">Pemohon (Applicant)</option>
            </select>
        </div>

        <!-- Per Page -->
        <div class="w-full sm:w-28">
            <select
                wire:model.live="perPage"
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-700 font-medium"
            >
                <option value="10">10 / hal</option>
                <option value="15">15 / hal</option>
                <option value="25">25 / hal</option>
                <option value="50">50 / hal</option>
            </select>
        </div>
    </div>

    <!-- 4. Users Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse min-w-[750px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-6 py-4">Nama Pengguna</th>
                        <th class="px-6 py-4">Alamat Email</th>
                        <th class="px-6 py-4 whitespace-nowrap">Peran / Role</th>
                        <th class="px-6 py-4 whitespace-nowrap">Terdaftar Sejak</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($usersList as $user)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- Name & Avatar -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-primary-100 text-primary-700 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if ($user->id === auth()->id())
                                                <span class="text-[10px] bg-primary-50 text-primary-700 px-1.5 py-0.2 rounded font-semibold border border-primary-200">(Anda)</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5 font-mono">ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4">
                                <div class="text-slate-700 font-medium font-mono text-xs">{{ $user->email }}</div>
                                <div class="text-[10px] text-emerald-600 mt-0.5 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">verified</span>
                                    <span>Terverifikasi</span>
                                </div>
                            </td>

                            <!-- Roles Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach ($user->roles as $role)
                                    @php
                                        $badgeStyle = match($role->name) {
                                            'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'reviewer' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'ketua_kepk' => 'bg-teal-100 text-teal-800 border-teal-200',
                                            'anggota_kepk' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                        $roleLabel = match($role->name) {
                                            'admin' => 'Administrator',
                                            'reviewer' => 'Asesor Penilai',
                                            'ketua_kepk' => 'Ketua KEPK',
                                            'anggota_kepk' => 'Anggota KEPK',
                                            'applicant' => 'Pemohon Akreditasi',
                                            default => ucfirst(str_replace('_', ' ', $role->name)),
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold border {{ $badgeStyle }} mr-1">
                                        <span class="material-symbols-outlined text-[13px]">person</span>
                                        <span>{{ $roleLabel }}</span>
                                    </span>
                                @endforeach
                            </td>

                            <!-- Created Date -->
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs whitespace-nowrap">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        wire:click="bukaModalEdit({{ $user->id }})"
                                        class="p-1.5 rounded-lg text-primary-700 hover:bg-primary-50 transition"
                                        title="Edit Akun"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    @if ($user->id !== auth()->id())
                                        <button
                                            type="button"
                                            wire:click="hapusUser({{ $user->id }})"
                                            wire:confirm="Yakin ingin menghapus akun pengguna '{{ $user->name }}' ini?"
                                            class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition"
                                            title="Hapus Akun"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-400 space-y-3">
                                <span class="material-symbols-outlined text-slate-300 text-[48px] block">person_off</span>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada akun pengguna yang sesuai dengan filter.</p>
                                <button
                                    type="button"
                                    wire:click="bukaModalCreate"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-700 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition"
                                >
                                    <span class="material-symbols-outlined text-[16px]">person_add</span>
                                    <span>Tambah Akun Baru</span>
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($usersList->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $usersList->links() }}
            </div>
        @endif
    </div>

    <!-- 5. Modal Dialog: Tambah / Edit Akun Pengguna -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-user-title" role="dialog" aria-modal="true">
            <!-- Light Subtle Backdrop -->
            <div class="fixed inset-0 bg-slate-900/25 transition-opacity" wire:click="tutupModal"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80">
                    <!-- Modal Header -->
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl select-none">👤</span>
                            <div>
                                <h3 class="font-display font-bold text-base text-slate-900 leading-tight" id="modal-user-title">
                                    {{ $isEditing ? 'Edit Akun Pengguna' : 'Tambah Akun Pengguna Baru' }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $isEditing ? 'Perbarui identitas, alamat email, atau peran akses pengguna.' : 'Buat kredensial akun baru untuk mengakses sistem Sekar-Mu.' }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="tutupModal"
                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                        >
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <!-- Modal Body Form -->
                    <form wire:submit="simpanUser">
                        <div class="p-6 space-y-4 text-xs">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Nama Lengkap & Gelar <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    placeholder="Contoh: Dr. dr. H. Budi Santoso, Sp.FK"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs"
                                />
                                @error('name') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Alamat Email -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Alamat Surel / Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    wire:model="email"
                                    placeholder="Contoh: budi.santoso@umy.ac.id"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs"
                                />
                                @error('email') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Peran / Role -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Peran / Hak Akses <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model="role"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-800 font-medium"
                                >
                                    <option value="reviewer">Asesor / Reviewer Penilai</option>
                                    <option value="applicant">Pemohon Akreditasi (Applicant)</option>
                                    <option value="ketua_kepk">Ketua KEPK</option>
                                    <option value="anggota_kepk">Anggota KEPK</option>
                                    <option value="admin">Administrator (Super Admin)</option>
                                </select>
                                @error('role') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Password & Konfirmasi -->
                            <div class="pt-2 border-t border-slate-100 space-y-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block font-bold text-slate-700">
                                            Password {{ $isEditing ? '(Opsional / Kosongkan jika tidak diubah)' : '*' }}
                                        </label>
                                    </div>
                                    <input
                                        type="password"
                                        wire:model="password"
                                        placeholder="{{ $isEditing ? 'Kosongkan jika password tetap' : 'Minimal 8 karakter' }}"
                                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs"
                                    />
                                    @error('password') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                                </div>

                                @if (! $isEditing || ! empty($password))
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">
                                            Konfirmasi Password <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="password"
                                            wire:model="password_confirmation"
                                            placeholder="Ketik ulang password baru..."
                                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs"
                                        />
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Modal Footer Actions -->
                        <div class="p-4 sm:px-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-2xl">
                            <button
                                type="button"
                                wire:click="tutupModal"
                                class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200/70 transition"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                            >
                                <span wire:loading.remove class="flex items-center gap-1.5">
                                    <span>{{ $isEditing ? 'Simpan Perubahan' : 'Buat Akun' }}</span>
                                    <span class="material-symbols-outlined text-[16px]">save</span>
                                </span>
                                <span wire:loading class="flex items-center gap-1.5">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Menyimpan...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
