<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Top Header -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900">Buat Surat Pengajuan Baru (B01-01)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Mulai proses permohonan akreditasi dan evaluasi etik KEPK baru.</p>
        </div>
        <div>
            <a href="{{ route('pengajuan.index') }}" class="btn-outline btn-sm text-xs" wire:navigate>&larr; Batal & Kembali</a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-6 shadow-2xs">
        <form wire:submit="save" class="space-y-6 text-xs">
            <!-- 1. Tujuan KEPK -->
            <div class="space-y-3 pb-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-800">1. Komisi Etik Tujuan Pengajuan</h3>
                <div>
                    <label for="kepk_id" class="block font-semibold text-slate-700 mb-1">
                        Pilih KEPK Akreditasi <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="kepk_id"
                        id="kepk_id"
                        class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    >
                        <option value="">-- Pilih KEPK --</option>
                        @foreach ($daftarKepk as $k)
                            <option value="{{ $k->id }}">{{ $k->name }} ({{ $k->institusi->name ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('kepk_id') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- 2. Formulir Aplikasi (Identitas) -->
            <div class="space-y-3 pb-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-800">2. Identitas Lembaga / Institusi Pemohon</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="nama_institusi" class="block font-semibold text-slate-700 mb-1">
                            Nama Institusi / Fakultas <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="nama_institusi"
                            id="nama_institusi"
                            placeholder="Contoh: Fakultas Kedokteran dan Ilmu Kesehatan UMY"
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                        @error('nama_institusi') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="singkatan" class="block font-semibold text-slate-700 mb-1">Singkatan / Akronim</label>
                        <input
                            type="text"
                            wire:model="singkatan"
                            id="singkatan"
                            placeholder="Contoh: FKIK-UMY"
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                    </div>

                    <div>
                        <label for="kota" class="block font-semibold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input
                            type="text"
                            wire:model="kota"
                            id="kota"
                            placeholder="Contoh: Yogyakarta"
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea
                            wire:model="alamat"
                            id="alamat"
                            rows="2"
                            placeholder="Alamat kantor sekretariat KEPK..."
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        ></textarea>
                    </div>

                    <div>
                        <label for="telepon" class="block font-semibold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input
                            type="text"
                            wire:model="telepon"
                            id="telepon"
                            placeholder="Contoh: 0274-387656"
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                    </div>

                    <div>
                        <label for="email" class="block font-semibold text-slate-700 mb-1">Email Resmi KEPK</label>
                        <input
                            type="email"
                            wire:model="email"
                            id="email"
                            placeholder="Contoh: kepk@institusi.ac.id"
                            class="w-full text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                        />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('pengajuan.index') }}" class="btn-outline btn-sm text-xs" wire:navigate>Batal</a>
                <button
                    type="submit"
                    class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 active:bg-teal-900 text-white font-semibold text-xs rounded-lg shadow-2xs transition"
                >
                    <span wire:loading.remove wire:target="save">💾 Buat Draft Surat Pengajuan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
