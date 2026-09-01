<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Top Header -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="'Formulir Aplikasi (B01-02) — Identitas Institusi'"
        :subtitle="'Lengkapi data legalitas dan identitas resmi institusi pemohon akreditasi komite etik.'">
        <x-slot:actions>
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5" wire:navigate>
                <span>&larr;</span>
                <span>Detail Pengajuan</span>
            </a>
        </x-slot:actions>
    </x-pengajuan.header>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                <span class="font-semibold">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
        <div class="flex items-center gap-2 pb-4 mb-6 border-b border-slate-100">
            <span class="material-symbols-outlined text-primary-700 text-[22px]">apartment</span>
            <div>
                <h2 class="font-display text-sm font-bold text-slate-900 uppercase tracking-wider">Identitas Legalitas Institusi</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Informasi resmi institusi pengusul akreditasi KEPK.</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-5 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label for="nomor_berkas" class="block font-bold text-slate-700 mb-1.5">
                        Nomor Berkas / Surat Pengajuan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nomor_berkas"
                        id="nomor_berkas"
                        placeholder="Contoh: KEPK/2026/001 atau 01/UNPAD/2026"
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
                    <span class="text-[11px] text-slate-400 block mt-1">Kosongkan jika ingin menggunakan penomoran otomatis sistem (No. {{ $suratPengajuan->id }}).</span>
                    @error('nomor_berkas') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="nama_institusi" class="block font-bold text-slate-700 mb-1.5">
                        Nama Institusi / Lembaga Pemohon <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nama_institusi"
                        id="nama_institusi"
                        placeholder="Contoh: Fakultas Kedokteran dan Ilmu Kesehatan Universitas Muhammadiyah Yogyakarta"
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
                    @error('nama_institusi') <span class="text-red-500 text-[11px] block mt-1 font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="singkatan" class="block font-bold text-slate-700 mb-1.5">Singkatan / Akronim</label>
                    <input
                        type="text"
                        wire:model="singkatan"
                        id="singkatan"
                        placeholder="Contoh: FKIK-UMY"
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
                </div>

                <div>
                    <label for="kota" class="block font-bold text-slate-700 mb-1.5">Kota / Kabupaten</label>
                    <input
                        type="text"
                        wire:model="kota"
                        id="kota"
                        placeholder="Contoh: Yogyakarta"
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat" class="block font-bold text-slate-700 mb-1.5">Alamat Lengkap Kantor</label>
                    <textarea
                        wire:model="alamat"
                        id="alamat"
                        rows="3"
                        placeholder="Alamat kantor sekretariat KEPK..."
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"></textarea>
                </div>

                <div>
                    <label for="telepon" class="block font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input
                        type="text"
                        wire:model="telepon"
                        id="telepon"
                        placeholder="Contoh: 0274-387656"
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
                </div>

                <div>
                    <label for="email" class="block font-bold text-slate-700 mb-1.5">Email Resmi KEPK</label>
                    <input
                        type="email"
                        wire:model="email"
                        id="email"
                        placeholder="Contoh: kepk@umy.ac.id"
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition" wire:navigate>
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        <span>Simpan Data Formulir</span>
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
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