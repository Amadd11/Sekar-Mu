<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">#APP-{{ str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\SuratPengajuan::statusBadgeClasses($suratPengajuan->status) }}">
                    {{ \App\Models\SuratPengajuan::statusLabel($suratPengajuan->status) }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">List Protokol Penelitian (B01-04)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Daftar judul dan protokol riset yang diajukan untuk ditelaah etiknya.</p>
        </div>

        <div>
            <a href="{{ route('pengajuan.show', $suratPengajuan) }}" class="btn-outline btn-sm text-xs" wire:navigate>
                &larr; Kembali ke Detail Pengajuan
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Protokol -->
        @if ($suratPengajuan->isEditable())
            <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4 h-fit">
                <div class="border-b border-slate-100 pb-2">
                    <h2 class="text-sm font-bold text-slate-900">
                        {{ $editingId ? 'Edit Protokol Riset' : 'Tambah Protokol Riset' }}
                    </h2>
                </div>

                <form wire:submit="simpan" class="space-y-3 text-xs">
                    <div>
                        <label for="nomor_protokol" class="block font-semibold text-slate-700 mb-1">Nomor Protokol *</label>
                        <input
                            type="text"
                            wire:model="nomor_protokol"
                            id="nomor_protokol"
                            placeholder="Misal: PR-2026-001"
                            class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                        @error('nomor_protokol') <span class="text-red-500 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="judul" class="block font-semibold text-slate-700 mb-1">Judul Riset *</label>
                        <textarea
                            wire:model="judul"
                            id="judul"
                            rows="2"
                            placeholder="Judul lengkap penelitian..."
                            class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        ></textarea>
                        @error('judul') <span class="text-red-500 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="peneliti_utama" class="block font-semibold text-slate-700 mb-1">Peneliti Utama *</label>
                        <input
                            type="text"
                            wire:model="peneliti_utama"
                            id="peneliti_utama"
                            placeholder="Nama peneliti utama..."
                            class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                        @error('peneliti_utama') <span class="text-red-500 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="tanggal_pengajuan" class="block font-semibold text-slate-700 mb-1">Tanggal Pengajuan</label>
                        <input
                            type="date"
                            wire:model="tanggal_pengajuan"
                            id="tanggal_pengajuan"
                            class="w-full text-xs rounded-lg border-slate-300 py-1.5 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        @if ($editingId)
                            <button type="button" wire:click="resetForm" class="btn-ghost btn-sm text-xs">Batal</button>
                        @endif
                        <button
                            type="submit"
                            class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                        >
                            {{ $editingId ? 'Update Protokol' : 'Simpan Protokol' }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- List Data Protokol -->
        <div class="{{ $suratPengajuan->isEditable() ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Daftar Protokol Terdaftar ({{ $protokolList->count() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                        <tr>
                            <th class="px-4 py-3">No. Protokol</th>
                            <th class="px-4 py-3">Judul Penelitian</th>
                            <th class="px-4 py-3">Peneliti Utama</th>
                            <th class="px-4 py-3">Tanggal</th>
                            @if ($suratPengajuan->isEditable())
                                <th class="px-4 py-3 text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($protokolList as $p)
                            <tr>
                                <td class="px-4 py-3 font-mono font-bold text-slate-700">{{ $p->nomor_protokol }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->judul }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $p->peneliti_utama }}</td>
                                <td class="px-4 py-3 text-slate-400">{{ $p->tanggal_pengajuan?->format('d M Y') ?? '-' }}</td>
                                @if ($suratPengajuan->isEditable())
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <button type="button" wire:click="edit({{ $p->id }})" class="text-teal-700 hover:underline font-semibold mr-2">Edit</button>
                                        <button type="button" wire:click="hapus({{ $p->id }})" wire:confirm="Hapus protokol ini?" class="text-red-600 hover:underline font-semibold">Hapus</button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada data protokol riset terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
