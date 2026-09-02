<!-- Modal 1: Edit Formulir Aplikasi (Identitas Institusi) -->
@if ($showFormulirModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background Backdrop Overlay -->
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="tutupModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative z-10 transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-200">
                <div class="bg-white p-6 sm:p-7">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-2xl bg-primary-50 text-primary-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">apartment</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-title">
                                Edit Identitas Institusi Pengusul
                            </h3>
                        </div>
                        <button type="button" wire:click="tutupModal" class="text-slate-400 hover:text-slate-600 transition p-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <form wire:submit="simpanFormulir" class="space-y-4 pt-4 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Institusi <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="nama_institusi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="Nama resmi institusi...">
                            @error('nama_institusi') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Singkatan Institusi</label>
                                <input type="text" wire:model="singkatan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="Misal: FKIK-UMY">
                                @error('singkatan') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                                <input type="text" wire:model="kota" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="Kota institusi...">
                                @error('kota') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                            <textarea wire:model="alamat" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="Alamat kampus / kantor..."></textarea>
                            @error('alamat') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nomor Telepon</label>
                                <input type="text" wire:model="telepon" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="Nomor telepon...">
                                @error('telepon') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Alamat Surel (Email)</label>
                                <input type="email" wire:model="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="email@institusi.ac.id">
                                @error('email') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                            <button type="button" wire:click="tutupModal" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 active:bg-primary-800 transition shadow-2xs cursor-pointer flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">save</span>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Modal 2: Edit Profil & Visi Misi KEPK -->
@if ($showProfilModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-profil-title" role="dialog" aria-modal="true">
        <!-- Background Backdrop Overlay -->
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="tutupModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative z-10 transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white p-6 sm:p-7">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-2xl bg-primary-50 text-primary-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-profil-title">
                                Edit Gambaran Umum, Visi & Misi KEPK
                            </h3>
                        </div>
                        <button type="button" wire:click="tutupModal" class="text-slate-400 hover:text-slate-600 transition p-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <form wire:submit="simpanProfil" class="space-y-4 pt-4 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Deskripsi / Gambaran Umum Komite</label>
                            <textarea wire:model="deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="Deskripsikan latar belakang, sejarah, atau peran komite etik..."></textarea>
                            @error('deskripsi') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Visi KEPK</label>
                            <textarea wire:model="visi" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="Visi komite etik..."></textarea>
                            @error('visi') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Misi KEPK</label>
                            <textarea wire:model="misi" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition" placeholder="1. Misi pertama&#10;2. Misi kedua..."></textarea>
                            @error('misi') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                            <button type="button" wire:click="tutupModal" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 active:bg-primary-800 transition shadow-2xs cursor-pointer flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">save</span>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Modal 3: Reusable Modal Konfirmasi Hapus Berkas -->
<x-confirm-modal
    :show="$showDeleteModal"
    title="Hapus Berkas Pengajuan?"
    type="danger"
    icon="delete_forever"
    confirmText="Ya, Hapus"
    cancelText="Batalkan"
    onConfirm="hapusDraft"
    onCancel="batalHapus"
>
    <div class="flex items-center justify-center gap-2 mb-2">
        <span class="font-mono text-xs font-bold text-rose-700 bg-rose-50 px-3 py-0.5 rounded-xl border border-rose-200">
            No. {{ $suratPengajuan->formatted_id }}
        </span>
    </div>
    <p>
        Apakah Anda yakin ingin menghapus berkas permohonan ini? Seluruh data isian borang, instrumen evaluasi diri, dan dokumen lampiran akan <strong class="text-slate-700 font-semibold">dihapus secara permanen</strong> dari sistem.
    </p>
    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-[11px] font-semibold text-left flex items-start gap-2 mt-2">
        <span class="material-symbols-outlined text-amber-600 text-[16px] shrink-0 mt-0.5">warning</span>
        <span>Tindakan ini tidak dapat dibatalkan. Pastikan Anda tidak lagi memerlukan arsip permohonan ini.</span>
    </div>
</x-confirm-modal>
