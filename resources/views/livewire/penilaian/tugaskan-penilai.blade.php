<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-7 shadow-xs relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-teal-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative z-10">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-slate-600 transition" wire:navigate>Dashboard</a>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('pengajuan.index') }}" class="text-slate-400 hover:text-slate-600 transition" wire:navigate>Surat Pengajuan</a>
                    <span class="text-slate-300">/</span>
                    <span class="font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200/60">Penugasan Asesor</span>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight">
                        Penugasan Tim Asesor Penilai
                    </h1>
                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200 shadow-2xs">
                        No. {{ $suratPengajuan->formatted_id }}
                    </span>
                    <x-pengajuan.status-badge :status="$suratPengajuan->status" />
                </div>

                <p class="text-xs sm:text-sm text-slate-500 max-w-3xl leading-relaxed">
                    Tentukan tim penilai independen untuk memeriksa kelayakan etik dan bukti dukung instrumen
                    <strong class="text-slate-700 font-semibold">{{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'KEPK Pemohon' }}</strong>.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a
                    href="{{ route('pengajuan.show', $suratPengajuan) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 transition shadow-2xs"
                    wire:navigate
                >
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Detail Berkas</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-2xl flex items-center justify-between shadow-2xs animate-fade-in">
            <div class="flex items-center gap-2.5 font-semibold">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Grid: Selection Area (8 cols) & Context Sidebar (4 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Assessor Selection Workspace (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-7 shadow-xs space-y-6">
                <!-- Section Header with Counter -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="font-display text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-teal-600 text-[20px]">group_add</span>
                            <span>Pilih Asesor Terdaftar</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Centang asesor yang akan diberikan hak telaah resmi pada berkas ini.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold px-3 py-1 rounded-xl {{ count($selectedReviewerIds) > 0 ? 'bg-teal-500/10 text-teal-700 border border-teal-300/60' : 'bg-slate-100 text-slate-500' }}">
                            {{ count($selectedReviewerIds) }} Asesor Dipilih
                        </span>
                    </div>
                </div>

                <!-- Search & Quick Selection Toolbar -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-1">
                    <div class="w-full sm:flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-2.5 text-slate-400 text-[18px]">search</span>
                        <input
                            type="text"
                            wire:model.live.debounce.250ms="search"
                            placeholder="Cari nama atau email asesor..."
                            class="w-full text-xs rounded-xl border border-slate-300 py-2.5 ps-10 pe-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-500/20 shadow-2xs placeholder:text-slate-400 text-slate-800"
                        />
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end">
                        <button
                            type="button"
                            wire:click="selectAll"
                            class="px-3 py-2 text-xs font-semibold text-teal-700 hover:bg-teal-50 rounded-xl transition border border-teal-200/80 flex items-center gap-1 cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">done_all</span>
                            <span>Pilih Semua</span>
                        </button>
                        <button
                            type="button"
                            wire:click="deselectAll"
                            class="px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition border border-slate-200 flex items-center gap-1 cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">close</span>
                            <span>Bersihkan</span>
                        </button>
                    </div>
                </div>

                <!-- Form & Reviewer Cards List -->
                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @forelse ($daftarReviewer as $rev)
                            @php
                                $isSelected = in_array($rev->id, $selectedReviewerIds);
                                $initials = strtoupper(substr($rev->name, 0, 2));
                            @endphp
                            <div
                                wire:click="toggleReviewer({{ $rev->id }})"
                                class="group relative p-4 rounded-2xl border transition-all cursor-pointer select-none flex flex-col justify-between gap-3 {{ $isSelected ? 'bg-teal-50/60 border-teal-500 ring-2 ring-teal-500/20 shadow-xs' : 'bg-white border-slate-200/80 hover:border-slate-300 hover:bg-slate-50/70 shadow-2xs' }}"
                            >
                                <div class="flex items-start gap-3.5">
                                    <!-- Avatar Chip -->
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-xs shrink-0 transition {{ $isSelected ? 'bg-teal-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 group-hover:bg-slate-200' }}">
                                        {{ $initials }}
                                    </div>

                                    <!-- Reviewer Info -->
                                    <div class="overflow-hidden flex-1">
                                        <div class="font-bold text-xs text-slate-900 leading-snug truncate {{ $isSelected ? 'text-teal-900' : '' }}">
                                            {{ $rev->name }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 truncate mt-0.5">
                                            {{ $rev->email }}
                                        </div>

                                        <!-- Workload Chip -->
                                        <div class="mt-2.5 flex items-center gap-1.5">
                                            @if ($rev->beban_aktif_count == 0)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>Tersedia (0 Tugas)</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/60">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <span>{{ $rev->beban_aktif_count }} Berkas Aktif</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Bottom: Selection Badge & Custom Checkbox -->
                                <div class="pt-3 border-t border-slate-100/80 flex items-center justify-between">
                                    <span class="text-[11px] font-semibold {{ $isSelected ? 'text-teal-700 font-bold' : 'text-slate-400' }}">
                                        {{ $isSelected ? '✓ Ditugaskan' : 'Klik untuk memilih' }}
                                    </span>

                                    <div class="w-5 h-5 rounded-lg border flex items-center justify-center transition {{ $isSelected ? 'bg-teal-700 border-teal-700 text-white' : 'border-slate-300 bg-white group-hover:border-slate-400' }}">
                                        @if ($isSelected)
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="sm:col-span-2 p-12 text-center text-slate-400 border-2 border-dashed border-slate-200 rounded-2xl space-y-2">
                                <span class="material-symbols-outlined text-slate-300 text-[40px] block">search_off</span>
                                <p class="text-xs font-bold text-slate-600">Tidak ada asesor yang cocok dengan pencarian.</p>
                                <p class="text-[11px] text-slate-400">Pastikan nama atau email yang Anda cari sudah terdaftar dengan role Asesor.</p>
                            </div>
                        @endforelse
                    </div>

                    @error('selectedReviewerIds')
                        <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-500 text-[18px]">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Sticky Bottom Action Strip -->
                    <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-slate-500 text-xs">
                            <span class="material-symbols-outlined text-teal-600 text-[18px]">info</span>
                            <span>Asesor terpilih akan langsung menerima akses penelaahan di dashboard mereka.</span>
                        </div>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-xs font-bold text-white bg-teal-700 hover:bg-teal-600 active:bg-teal-800 shadow-md shadow-teal-700/20 transition cursor-pointer shrink-0"
                        >
                            <span class="material-symbols-outlined text-[18px]" wire:loading.remove wire:target="save">person_add</span>
                            <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" wire:loading wire:target="save"></span>
                            <span wire:loading.remove wire:target="save">Simpan Penugasan Asesor</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Dossier Summary & KEPPKN Guidelines (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Card 1: Ringkasan Berkas Target -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="material-symbols-outlined text-teal-700 text-[20px]">apartment</span>
                    <h3 class="font-display text-sm font-bold text-slate-900 uppercase tracking-wider">Identitas Pemohon</h3>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <div class="text-[11px] text-slate-400 font-bold uppercase">Nama Institusi / Lembaga</div>
                        <div class="font-bold text-slate-900 text-sm leading-snug mt-0.5">
                            {{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'Draft Baru' }}
                        </div>
                        @if (! empty($suratPengajuan->formulirAplikasi->singkatan))
                            <div class="text-[11px] text-slate-500 font-semibold mt-0.5">
                                ({{ $suratPengajuan->formulirAplikasi->singkatan }})
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                        <div>
                            <div class="text-[11px] text-slate-400 font-bold uppercase">Komisi Etik (KEPK)</div>
                            <div class="font-semibold text-slate-800 mt-0.5">
                                {{ $suratPengajuan->kepk->name ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-[11px] text-slate-400 font-bold uppercase">Kota / Wilayah</div>
                            <div class="font-semibold text-slate-800 mt-0.5">
                                {{ $suratPengajuan->formulirAplikasi->kota ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <div class="text-[11px] text-slate-400 font-bold uppercase">Nomor Berkas Resmi</div>
                        <div class="font-mono font-bold text-slate-800 text-xs mt-0.5">
                            {{ $suratPengajuan->formatted_id }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Pedoman Tata Kelola Penugasan (KEPPKN / WHO) -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-white/10">
                    <span class="material-symbols-outlined text-teal-400 text-[20px]">policy</span>
                    <h3 class="font-display text-xs font-bold uppercase tracking-wider text-teal-300">Pedoman Tata Kelola</h3>
                </div>

                <div class="space-y-3.5 text-xs text-slate-300 leading-relaxed">
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-teal-400 text-[18px] shrink-0 mt-0.5">verified_user</span>
                        <div>
                            <div class="font-bold text-white text-xs">Independensi Mutlak</div>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Hindari menugaskan asesor yang memiliki keterikatan institusional atau riwayat kerja sama langsung dengan KEPK pemohon guna mencegah konflik kepentingan.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-teal-400 text-[18px] shrink-0 mt-0.5">handshake</span>
                        <div>
                            <div class="font-bold text-white text-xs">Prinsip Peer-Review (Min. 2 Asesor)</div>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Disarankan menunjuk minimal 2 orang asesor penilai agar hasil telaah standar dan rekomendasi akreditasi teruji secara independen dan komprehensif.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-teal-400 text-[18px] shrink-0 mt-0.5">lock_open</span>
                        <div>
                            <div class="font-bold text-white text-xs">Otorisasi Akses Real-Time</div>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Hanya asesor yang tersimpan dalam daftar penugasan ini yang diberikan wewenang untuk membuka dan menilai instrumen evaluasi diri.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
