<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Top Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl select-none">📑</span>
                <h1 class="text-lg font-bold text-slate-900">
                    Daftar Protokol Penelitian (Research Protocols)
                </h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Pengajuan: <strong class="text-slate-800">{{ $application->information->name ?? 'Pengajuan Etik' }}</strong> • No: #APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('applications.self-assessment', $application) }}" class="btn-outline btn-sm gap-1 text-xs" wire:navigate>
                &larr; Borang Evaluasi Diri
            </a>
            <a href="{{ route('applications.documents', $application) }}" class="btn-primary btn-sm gap-1 text-xs" wire:navigate>
                <span>Upload Dokumen Lampiran &rarr;</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center justify-between">
            <span>✓ {{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Protocol Form (Left 1 Col) -->
        <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900">
                    {{ $editingProtocolId ? 'Edit Protokol Riset' : 'Tambah Protokol Baru' }}
                </h2>
                @if ($editingProtocolId)
                    <button type="button" wire:click="resetForm" class="text-xs text-slate-500 hover:text-slate-700">
                        Batal
                    </button>
                @endif
            </div>

            <form wire:submit="save" class="space-y-4 text-xs">
                <!-- Protocol Number -->
                <div>
                    <label for="protocol_number" class="block font-semibold text-slate-700 mb-1">
                        Nomor Protokol <span class="text-red-500">*</span>
                    </label>
                    <input
                        wire:model="protocol_number"
                        id="protocol_number"
                        type="text"
                        placeholder="Contoh: PROT/2026/08/001"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    />
                    @error('protocol_number')
                        <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block font-semibold text-slate-700 mb-1">
                        Judul Penelitian <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        wire:model="title"
                        id="title"
                        rows="3"
                        placeholder="Tuliskan judul lengkap protokol penelitian..."
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    ></textarea>
                    @error('title')
                        <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PI (Principal Investigator) -->
                <div>
                    <label for="principal_investigator" class="block font-semibold text-slate-700 mb-1">
                        Ketua Peneliti (PI) <span class="text-red-500">*</span>
                    </label>
                    <input
                        wire:model="principal_investigator"
                        id="principal_investigator"
                        type="text"
                        placeholder="Nama lengkap peneliti utama beserta gelar"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    />
                    @error('principal_investigator')
                        <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submission Date -->
                <div>
                    <label for="submission_date" class="block font-semibold text-slate-700 mb-1">
                        Tanggal Pengajuan Protokol
                    </label>
                    <input
                        wire:model="submission_date"
                        id="submission_date"
                        type="date"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    />
                    @error('submission_date')
                        <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block font-semibold text-slate-700 mb-1">
                        Status Protokol
                    </label>
                    <select
                        wire:model="status"
                        id="status"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    >
                        <option value="draft">Draft</option>
                        <option value="submitted">Diajukan</option>
                        <option value="under_review">Sedang Ditelaah</option>
                        <option value="approved">Disetujui</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold rounded-lg shadow-2xs transition"
                    >
                        <span wire:loading.remove wire:target="save">{{ $editingProtocolId ? 'Perbarui Protokol' : 'Tambah Protokol' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Protocol List Table (Right 2 Cols) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Daftar Protokol Terdaftar</h3>
                    <p class="text-[11px] text-slate-400">Total: {{ $protocols->count() }} protokol riset dalam berkas ini.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                        <tr>
                            <th class="px-5 py-3">No. Protokol</th>
                            <th class="px-5 py-3">Judul Penelitian & Peneliti</th>
                            <th class="px-5 py-3">Tgl Diajukan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($protocols as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-700">
                                    {{ $item->protocol_number }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-semibold text-slate-900 leading-snug">
                                        {{ $item->title }}
                                    </div>
                                    <div class="text-[11px] text-teal-700 mt-0.5">
                                        👤 {{ $item->principal_investigator }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-[11px]">
                                    {{ $item->submission_date ? $item->submission_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-1">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $item->id }})"
                                        class="px-2.5 py-1 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded text-[11px] font-medium transition"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus protokol riset ini?"
                                        class="px-2.5 py-1 text-red-700 bg-red-50 hover:bg-red-100 rounded text-[11px] font-medium transition"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada protokol penelitian yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
