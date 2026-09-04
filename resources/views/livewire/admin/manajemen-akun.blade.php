<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- 1. Full Header Card (Matching Evaluasi Diri & Borang Design) -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden relative">
        <!-- Top Gradient Accent Bar -->
        <div class="h-1 bg-gradient-to-r from-[#174668] via-teal-500 to-[#174668]"></div>

        <div class="p-6 sm:p-7 relative z-10 space-y-4">
            <!-- Top Meta Strip -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="bg-primary-50 text-primary-700 font-display text-xs px-3 py-1 rounded-lg font-bold border border-primary-200/70 shadow-2xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px]">shield_person</span>
                        <span>Panel Kontrol Administrator</span>
                    </span>
                    <span class="bg-slate-100 text-slate-700 font-mono text-xs px-3 py-1 rounded-lg font-bold border border-slate-200 shadow-2xs">
                        {{ $totalUsers }} Akun Terdaftar
                    </span>
                </div>
            </div>

            <!-- Main Title & Action Buttons Row -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 pt-1">
                <div class="space-y-1 max-w-3xl">
                    <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        Manajemen Akun & Hak Akses Pengguna
                    </h1>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                        Kelola akun Asesor Penilai independen, Pemohon KEPK, Tim Komite Etik, serta hak akses Administrator sistem Sekar-Mu.
                    </p>
                </div>

                <!-- Primary Action Button -->
                <div class="flex items-center gap-2.5 w-full sm:w-auto shrink-0">
                    <button
                        type="button"
                        wire:click="bukaModalCreate"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        <span>Tambah Akun Baru</span>
                    </button>
                </div>
            </div>
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

    <!-- 2. KPI Role Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pengguna</span>
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 font-display">{{ $totalUsers }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Seluruh akun terdaftar di sistem</p>
            </div>
        </div>

        <!-- Asessors -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Asesor Penilai</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">clinical_notes</span>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-blue-800 font-display">{{ $totalAsessors }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Penelaah protokol independen</p>
            </div>
        </div>

        <!-- Komite KEPK -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Komite KEPK</span>
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">health_and_safety</span>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-teal-800 font-display">{{ $totalKepk }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Ketua & Anggota KEPK</p>
            </div>
        </div>

        <!-- Admins -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Administrator</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-purple-800 font-display">{{ $totalAdmins }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Super Admin Pengelola</p>
            </div>
        </div>
    </div>

    <!-- 3. Filters & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <!-- Search Input -->
        <div class="w-full sm:flex-1 relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama pengguna, alamat surel..."
                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 ps-10 pe-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
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
                <option value="ketua_kepk">Ketua KEPK</option>
                <option value="asessor">Asesor Penilai</option>
                <option value="anggota">Anggota KEPK</option>
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
                        <th class="px-6 py-4 whitespace-nowrap">Peran / Hak Akses</th>
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
                                    @php
                                        $primaryRole = $user->roles->first()?->name ?? 'user';
                                        $avatarStyle = match($primaryRole) {
                                            'admin' => 'bg-purple-100 text-purple-800',
                                            'asessor' => 'bg-blue-100 text-blue-800',
                                            'ketua_kepk' => 'bg-teal-100 text-teal-800',
                                            'anggota' => 'bg-emerald-100 text-emerald-800',
                                            default => 'bg-primary-100 text-primary-800',
                                        };
                                    @endphp
                                    <div class="w-10 h-10 rounded-xl {{ $avatarStyle }} font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5 group-hover:text-primary-700 transition">
                                            <span>{{ $user->name }}</span>
                                            @if ($user->id === auth()->id())
                                                 <span class="text-[10px] bg-primary-50 text-primary-700 px-2 py-0.5 rounded-md font-bold border border-primary-200/80">(Anda)</span>
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
                                    <span class="material-symbols-outlined text-[13px]">verified</span>
                                    <span>Akun Terverifikasi</span>
                                </div>
                            </td>

                            <!-- Roles Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach ($user->roles as $role)
                                    @php
                                        $badgeStyle = match($role->name) {
                                            'admin' => 'bg-purple-50 text-purple-800 border-purple-200/80',
                                            'asessor' => 'bg-blue-50 text-blue-800 border-blue-200/80',
                                            'ketua_kepk' => 'bg-teal-50 text-teal-800 border-teal-200/80',
                                            'anggota' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200/80',
                                        };
                                        $roleLabel = match($role->name) {
                                            'admin' => 'Administrator',
                                            'asessor' => 'Asesor Penilai',
                                            'ketua_kepk' => 'Ketua KEPK',
                                            'anggota' => 'Anggota KEPK',
                                            default => ucfirst(str_replace('_', ' ', $role->name)),
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold border {{ $badgeStyle }} mr-1">
                                        <span class="material-symbols-outlined text-[13px]">badge</span>
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
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition shadow-2xs"
                                        title="Edit Akun"
                                    >
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                        <span>Edit</span>
                                    </button>

                                    @if ($user->id !== auth()->id())
                                        <button
                                            type="button"
                                            wire:click="konfirmasiHapus({{ $user->id }})"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer"
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
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                    <span class="material-symbols-outlined text-[32px]">person_off</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Tidak ada akun pengguna yang sesuai dengan filter pencarian.</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Coba ubah kata kunci atau filter peran.</p>
                                </div>
                                <button
                                    type="button"
                                    wire:click="bukaModalCreate"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-700 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer"
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

    <!-- Modals (Create/Edit Akun & Confirm Delete) -->
    @include('livewire.admin.partials.modal-user')
</div>
