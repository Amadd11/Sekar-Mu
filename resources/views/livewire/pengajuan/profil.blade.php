<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Top Header -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="'Profil, Visi, Misi & Susunan Anggota KEPK'"
        :subtitle="'Kelola profil kelembagaan, visi misi komite, dan daftar susunan keanggotaan KEPK.'">
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <!-- 1. Form Visi & Misi -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs space-y-5">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                <span class="material-symbols-outlined text-primary-700 text-[20px]">visibility</span>
                <h2 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">1. Profil & Visi Misi KEPK</h2>
            </div>

            <form wire:submit="saveProfil" class="space-y-4 text-xs">
                <div>
                    <label for="deskripsi" class="block font-bold text-slate-700 mb-1.5">Deskripsi Singkat / Gambaran Umum</label>
                    <textarea
                        wire:model="deskripsi"
                        id="deskripsi"
                        rows="3"
                        placeholder="Uraikan gambaran umum dan latar belakang komite etik..."
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                    ></textarea>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="visi" class="block font-bold text-slate-700 mb-1.5">Visi KEPK</label>
                        <textarea
                            wire:model="visi"
                            id="visi"
                            rows="3"
                            placeholder="Visi komite etik..."
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                        ></textarea>
                    </div>

                    <div>
                        <label for="misi" class="block font-bold text-slate-700 mb-1.5">Misi KEPK</label>
                        <textarea
                            wire:model="misi"
                            id="misi"
                            rows="3"
                            placeholder="Misi komite etik..."
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                        ></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition cursor-pointer"
                    >
                        <span wire:loading.remove wire:target="saveProfil" class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">save</span>
                            <span>Simpan Profil</span>
                        </span>
                        <span wire:loading wire:target="saveProfil" class="flex items-center gap-1.5">
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

        <!-- 2. Anggota KEPK -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-700 text-[20px]">group</span>
                    <h2 class="font-display text-xs font-bold text-slate-900 uppercase tracking-wider">2. Anggota KEPK ({{ $suratPengajuan->anggotaKepk->count() }})</h2>
                </div>
            </div>

            @if (session('anggota_status'))
                <div class="bg-emerald-50 text-emerald-800 text-xs p-3 rounded-xl border border-emerald-200 shadow-2xs">
                    ✓ {{ session('anggota_status') }}
                </div>
            @endif

            <div class="overflow-x-auto border border-slate-100 rounded-xl">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Nama Lengkap</th>
                            <th class="px-4 py-3">Jabatan</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($suratPengajuan->anggotaKepk as $m)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $m->nama }}</td>
                                <td class="px-4 py-3 text-slate-700">
                                    <span class="bg-slate-100 px-2 py-0.5 rounded-md font-medium text-slate-800">{{ $m->jabatan ?? 'Anggota' }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 font-mono text-[11px]">{{ $m->email ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        wire:click="hapusAnggota({{ $m->id }})"
                                        wire:confirm="Yakin ingin menghapus anggota '{{ $m->nama }}' ini?"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus Anggota"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada anggota yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Form Tambah Anggota -->
            <form wire:submit="tambahAnggota" class="pt-4 border-t border-slate-100 space-y-3 text-xs">
                <div class="font-bold text-slate-700 uppercase tracking-wider text-[11px]">Tambah Anggota Baru:</div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-3">
                        <input
                            type="text"
                            wire:model="nama_anggota"
                            placeholder="Nama lengkap + gelar *"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                        />
                        @error('nama_anggota') <span class="text-red-500 text-[10px] block mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input
                            type="text"
                            wire:model="jabatan_anggota"
                            placeholder="Jabatan (Ketua, Sekretaris, Anggota)"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                        />
                    </div>
                    <div>
                        <input
                            type="email"
                            wire:model="email_anggota"
                            placeholder="Alamat Email"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                        />
                    </div>
                    <div>
                        <button
                            type="submit"
                            class="w-full h-full py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-xs flex items-center justify-center gap-1 cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Tambah</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
