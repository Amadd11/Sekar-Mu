<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Top Header -->
    <x-pengajuan.header
        :surat="$suratPengajuan"
        :title="'Profil, Visi, Misi & Susunan Anggota KEPK'"
        :subtitle="'Kelola profil kelembagaan, visi misi, dan daftar keanggotaan komite etik.'"
    >
        <x-slot:actions>
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                &larr; Detail Pengajuan
            </a>
        </x-slot:actions>
    </x-pengajuan.header>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    <!-- 1. Form Visi & Misi -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-6 shadow-2xs">
        <h2 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">1. Profil & Visi Misi KEPK</h2>
        <form wire:submit="saveProfil" class="space-y-4 text-xs">
            <div>
                <label for="deskripsi" class="block font-semibold text-slate-700 mb-1">Deskripsi Singkat / Gambaran Umum</label>
                <textarea
                    wire:model="deskripsi"
                    id="deskripsi"
                    rows="3"
                    class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="visi" class="block font-semibold text-slate-700 mb-1">Visi KEPK</label>
                    <textarea
                        wire:model="visi"
                        id="visi"
                        rows="3"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    ></textarea>
                </div>

                <div>
                    <label for="misi" class="block font-semibold text-slate-700 mb-1">Misi KEPK</label>
                    <textarea
                        wire:model="misi"
                        id="misi"
                        rows="3"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    ></textarea>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                >
                    <span wire:loading.remove wire:target="saveProfil">💾 Simpan Profil</span>
                    <span wire:loading wire:target="saveProfil">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Anggota KEPK -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-6 shadow-2xs space-y-4">
        <h2 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">2. Susunan Pengurus & Anggota KEPK</h2>

        @if (session('anggota_status'))
            <div class="bg-emerald-50 text-emerald-800 text-xs p-2.5 rounded-lg border border-emerald-200">
                ✓ {{ session('anggota_status') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                    <tr>
                        <th class="px-4 py-3">Nama Lengkap</th>
                        <th class="px-4 py-3">Jabatan</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Telepon</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($suratPengajuan->anggotaKepk as $m)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $m->nama }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $m->jabatan ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $m->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $m->telepon ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <button
                                    type="button"
                                    wire:click="hapusAnggota({{ $m->id }})"
                                    wire:confirm="Hapus anggota ini?"
                                    class="text-red-600 hover:underline font-semibold"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada anggota yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Form Tambah Anggota -->
        <form wire:submit="tambahAnggota" class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <input
                    type="text"
                    wire:model="nama_anggota"
                    placeholder="Nama lengkap + gelar *"
                    class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-2.5 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                />
                @error('nama_anggota') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
            </div>
            <div>
                <input
                    type="text"
                    wire:model="jabatan_anggota"
                    placeholder="Jabatan (Ketua, Sekretaris, Anggota)"
                    class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-2.5 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                />
            </div>
            <div>
                <input
                    type="email"
                    wire:model="email_anggota"
                    placeholder="Email"
                    class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-2.5 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                />
            </div>
            <div>
                <button
                    type="submit"
                    class="w-full py-1.5 px-3 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-lg transition"
                >
                    + Tambah Anggota
                </button>
            </div>
        </form>
    </div>
</div>
