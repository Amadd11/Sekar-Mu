<!-- 5. Modal Dialog: Tambah / Edit Akun Pengguna -->
@if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-user-title" role="dialog" aria-modal="true">
        <!-- Light Subtle Backdrop -->
        <div class="fixed inset-0 bg-slate-900/30 backdrop-blur-xs transition-opacity" wire:click="tutupModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80">
                <!-- Modal Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">{{ $isEditing ? 'manage_accounts' : 'person_add' }}</span>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-base text-slate-900 leading-tight" id="modal-user-title">
                                {{ $isEditing ? 'Edit Akun Pengguna' : 'Tambah Akun Pengguna Baru' }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $isEditing ? 'Perbarui identitas, surel, atau peran akses pengguna.' : 'Buat kredensial akun baru untuk mengakses sistem Sekar-Mu.' }}
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
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Nama Lengkap & Gelar <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                placeholder="Contoh: Dr. dr. H. Budi Santoso, Sp.FK"
                                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                            />
                            @error('name') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat Email -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Alamat Surel / Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                wire:model="email"
                                placeholder="Contoh: budi.santoso@umy.ac.id"
                                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                            />
                            @error('email') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Peran / Role -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Peran / Hak Akses Sistem <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model="role"
                                class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-800 font-medium"
                            >
                                <option value="asessor">Asesor Penilai</option>
                                <option value="ketua_kepk">Ketua KEPK</option>
                                <option value="anggota">Anggota KEPK</option>
                                <option value="admin">Administrator (Super Admin)</option>
                            </select>
                            @error('role') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password & Konfirmasi -->
                        <div class="pt-3 border-t border-slate-100 space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block font-bold text-slate-700">
                                        Password {{ $isEditing ? '(Opsional / Kosongkan jika tetap)' : '*' }}
                                    </label>
                                </div>
                                <input
                                    type="password"
                                    wire:model.live.debounce.300ms="password"
                                    placeholder="{{ $isEditing ? 'Kosongkan jika password tidak diubah' : 'Minimal 8 karakter' }}"
                                    class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                                />
                                @error('password') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                            </div>

                            @if (! $isEditing || ! empty($password))
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1.5">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        wire:model="password_confirmation"
                                        placeholder="Ketik ulang password baru..."
                                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                                    />
                                    @error('password_confirmation') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
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
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="simpanUser" class="flex items-center gap-1.5">
                                <span>{{ $isEditing ? 'Simpan Perubahan' : 'Buat Akun' }}</span>
                                <span class="material-symbols-outlined text-[16px]">save</span>
                            </span>
                            <span wire:loading wire:target="simpanUser" class="flex items-center gap-1.5">
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

<!-- Reusable Modal Konfirmasi Hapus Akun -->
<x-confirm-modal
    :show="$showDeleteModal"
    title="Hapus Akun Pengguna?"
    type="danger"
    icon="person_remove"
    confirmText="Ya, Hapus Akun"
    cancelText="Batalkan"
    onConfirm="hapusUser"
    onCancel="batalHapus"
>
    <p>
        Apakah Anda yakin ingin menghapus akun pengguna <strong class="text-slate-900 font-semibold">{{ $deletingUserName }}</strong>? Akun ini tidak akan lagi dapat mengakses sistem SekarMU.
    </p>
    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-[11px] font-semibold text-left flex items-start gap-2 mt-2">
        <span class="material-symbols-outlined text-amber-600 text-[16px] shrink-0 mt-0.5">warning</span>
        <span>Tindakan ini tidak dapat dibatalkan.</span>
    </div>
</x-confirm-modal>
