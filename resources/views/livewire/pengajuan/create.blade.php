<div class="space-y-6 max-w-4xl mx-auto pb-12">
    <!-- Top Header -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="text-xl select-none">🌸</span>
                <span class="bg-primary-50 text-primary-700 text-xs font-bold px-2.5 py-0.5 rounded-md border border-primary-200/70">
                    B01-01
                </span>
            </div>
            <h1 class="font-display text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">
                Buat Surat Pengajuan Baru
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Mulai proses permohonan asesmen dan evaluasi mandiri standar akreditasi KEPK.
            </p>
        </div>

        <div class="relative z-10">
            <a href="{{ route('pengajuan.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-xs inline-flex items-center gap-1.5" wire:navigate>
                <span>&larr;</span>
                <span>Batal & Kembali</span>
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
        <form wire:submit="save" class="space-y-6 text-xs">
            <!-- 1. Tujuan KEPK -->
            <div class="space-y-3 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-700 text-[20px]">health_and_safety</span>
                    <h3 class="font-display text-sm font-bold text-slate-900">1. Komisi Etik Tujuan Pengajuan</h3>
                </div>
                <div>
                    <label for="kepk_id" class="block font-semibold text-slate-700 mb-1.5">
                        Pilih KEPK Akreditasi <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="kepk_id"
                        id="kepk_id"
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs text-slate-800"
                    >
                        <option value="">-- Pilih KEPK --</option>
                        @foreach ($daftarKepk as $k)
                            <option value="{{ $k->id }}">{{ $k->name }} ({{ $k->institusi->name ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('kepk_id') <span class="text-red-500 text-[11px] block mt-1.5 font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- 2. Formulir Aplikasi (Identitas) -->
            <div class="space-y-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-700 text-[20px]">apartment</span>
                    <h3 class="font-display text-sm font-bold text-slate-900">2. Identitas Lembaga / Institusi Pemohon</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="nama_institusi" class="block font-semibold text-slate-700 mb-1.5">
                            Nama Institusi / Fakultas <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="nama_institusi"
                            id="nama_institusi"
                            placeholder="Contoh: Fakultas Kedokteran dan Ilmu Kesehatan UMY"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                        />
                        @error('nama_institusi') <span class="text-red-500 text-[11px] block mt-1.5 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="singkatan" class="block font-semibold text-slate-700 mb-1.5">Singkatan / Akronim</label>
                        <input
                            type="text"
                            wire:model="singkatan"
                            id="singkatan"
                            placeholder="Contoh: FKIK-UMY"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                        />
                    </div>

                    <div>
                        <label for="kota" class="block font-semibold text-slate-700 mb-1.5">Kota / Kabupaten</label>
                        <input
                            type="text"
                            wire:model="kota"
                            id="kota"
                            placeholder="Contoh: Yogyakarta"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="block font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                        <textarea
                            wire:model="alamat"
                            id="alamat"
                            rows="2"
                            placeholder="Alamat kantor sekretariat KEPK..."
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                        ></textarea>
                    </div>

                    <div>
                        <label for="telepon" class="block font-semibold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input
                            type="text"
                            wire:model="telepon"
                            id="telepon"
                            placeholder="Contoh: 0274-387656"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                        />
                    </div>

                    <div>
                        <label for="email" class="block font-semibold text-slate-700 mb-1.5">Email Resmi KEPK</label>
                        <input
                            type="email"
                            wire:model="email"
                            id="email"
                            placeholder="Contoh: kepk@umy.ac.id"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                        />
                    </div>
                </div>
            </div>

            <!-- 3. Surat Pengantar -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-700 text-[20px]">description</span>
                    <h3 class="font-display text-sm font-bold text-slate-900">3. Surat Pengantar & Dokumen Awal</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nomor_surat" class="block font-semibold text-slate-700 mb-1.5">Nomor Surat Pengantar</label>
                        <input
                            type="text"
                            wire:model="nomor_surat"
                            id="nomor_surat"
                            placeholder="Contoh: 123/B.01/KEPK-UMY/VIII/2026"
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 shadow-2xs placeholder:text-slate-400"
                        />
                    </div>

                    <div>
                        <label for="file_surat" class="block font-semibold text-slate-700 mb-1.5">Unggah Surat Pengantar (PDF, max 10MB)</label>
                        <input
                            type="file"
                            wire:model="file_surat"
                            id="file_surat"
                            accept=".pdf"
                            class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer"
                        />
                        @error('file_surat') <span class="text-red-500 text-[11px] block mt-1.5 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('pengajuan.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-semibold transition" wire:navigate>
                    Batal
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-700 hover:bg-primary-600 active:bg-primary-800 text-white font-bold text-xs rounded-xl shadow-md shadow-primary-700/20 transition"
                >
                    <span wire:loading.remove>Simpan & Lanjutkan &rarr;</span>
                    <span wire:loading class="flex items-center gap-1">
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
