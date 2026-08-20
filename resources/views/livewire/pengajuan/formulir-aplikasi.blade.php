<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Top Header -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="'Formulir Aplikasi (B01-02) — Identitas Institusi'"
        :subtitle="'Lengkapi data legalitas dan identitas resmi institusi pemohon akreditasi.'">
        <x-slot:actions>
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                Detail Pengajuan
            </a>
        </x-slot:actions>
    </x-pengajuan.header>

    @if (session('status'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
        <span>✓ {{ session('status') }}</span>
    </div>
    @endif

    <div class="bg-white border border-slate-200/90 rounded-xl p-6 shadow-2xs">
        <form wire:submit="save" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="nama_institusi" class="block font-semibold text-slate-700 mb-1">
                        Nama Institusi / Lembaga Pemohon <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nama_institusi"
                        id="nama_institusi"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600" />
                    @error('nama_institusi') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="singkatan" class="block font-semibold text-slate-700 mb-1">Singkatan / Akronim</label>
                    <input
                        type="text"
                        wire:model="singkatan"
                        id="singkatan"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600" />
                </div>

                <div>
                    <label for="kota" class="block font-semibold text-slate-700 mb-1">Kota / Kabupaten</label>
                    <input
                        type="text"
                        wire:model="kota"
                        id="kota"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600" />
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat" class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea
                        wire:model="alamat"
                        id="alamat"
                        rows="2"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"></textarea>
                </div>

                <div>
                    <label for="telepon" class="block font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                    <input
                        type="text"
                        wire:model="telepon"
                        id="telepon"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600" />
                </div>

                <div>
                    <label for="email" class="block font-semibold text-slate-700 mb-1">Email Resmi</label>
                    <input
                        type="email"
                        wire:model="email"
                        id="email"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>Batal</a>
                <button
                    type="submit"
                    class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold text-xs rounded-lg shadow-2xs transition">
                    <span wire:loading.remove wire:target="save">💾 Simpan Data Formulir</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>