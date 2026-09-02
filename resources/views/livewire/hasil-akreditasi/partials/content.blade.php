<!-- Card: Progress Capaian 5 Bagian Standar A-E -->
<div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-[18px]">stacked_bar_chart</span>
            </div>
            <h3 class="text-sm font-bold text-slate-900">Hasil Capaian per Komponen (Bagian A – E)</h3>
        </div>
        <a href="{{ route('pengajuan.evaluasi-diri', $suratPengajuan) }}" class="text-xs text-primary-700 font-bold hover:underline" wire:navigate>
            Buka Borang &rarr;
        </a>
    </div>

    <div class="space-y-4 text-xs">
        @php
        $defaultSections = [
        'A' => ['nama' => 'Regulasi, Kelembagaan, dan Tata Kelola', 'total' => 29],
        'B' => ['nama' => 'Keanggotaan dan Kompetensi', 'total' => 35],
        'C' => ['nama' => 'Operasional dan Prosedur', 'total' => 74],
        'D' => ['nama' => 'Fasilitas dan Sumber Daya', 'total' => 12],
        'E' => ['nama' => 'Penelitian Khusus', 'total' => 14],
        ];
        @endphp

        @foreach ($defaultSections as $secCode => $secMeta)
        @php
        $sData = $metrics['sections'][$secCode] ?? [
        'nama' => $secMeta['nama'],
        'answered_items' => 0,
        'total_items' => $secMeta['total'],
        'compliance_percentage' => 0,
        ];
        @endphp
        <div class="space-y-1.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-800">Bagian {{ $secCode }}: {{ $secMeta['nama'] }}</span>
                <span class="font-bold font-mono text-primary-700">{{ $sData['compliance_percentage'] }}% <span class="text-slate-400 font-normal">({{ $sData['answered_items'] }}/{{ $sData['total_items'] }})</span></span>
            </div>
            <div class="w-full bg-slate-200/70 rounded-full h-2 overflow-hidden">
                <div class="bg-primary-700 h-2 rounded-full transition-all duration-300" style="{{ 'width: ' . $sData['compliance_percentage'] . '%' }}"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Card: Ulasan & Rekomendasi Tim Asesor Penilai -->
@if ($suratPengajuan->penilaianEtik->isNotEmpty())
<div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-[18px]">rate_review</span>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900">Ulasan & Rekomendasi Tim Asesor Penilai</h3>
                <p class="text-[11px] text-slate-500">Hasil telaah independen oleh asesor penilai yang ditugaskan</p>
            </div>
        </div>
        <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200">
            {{ $suratPengajuan->penilaianEtik->count() }} Ulasan Masuk
        </span>
    </div>

    <div class="space-y-4">
        @foreach ($suratPengajuan->penilaianEtik as $penilaian)
            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-3 text-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/60 pb-2.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-800 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr($penilaian->penilai->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 block text-xs">{{ $penilaian->penilai->name ?? 'Asesor' }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">
                                {{ $penilaian->penilai->email ?? '-' }} • 
                                {{ $penilaian->tanggal_keputusan ? \Carbon\Carbon::parse($penilaian->tanggal_keputusan)->format('d M Y') : $penilaian->updated_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold border shadow-2xs {{ $penilaian->badge_rekomendasi }}">
                            {{ $penilaian->label_rekomendasi }}
                        </span>
                    </div>
                </div>

                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Pertimbangan & Catatan Asesor:</span>
                    <p class="text-slate-700 leading-relaxed bg-white p-3.5 rounded-xl border border-slate-200/60 whitespace-pre-line italic">
                        "{{ $penilaian->catatan ?? 'Tidak ada catatan kesimpulan tambahan.' }}"
                    </p>
                </div>

                @if ($penilaian->catatanPenilaian->isNotEmpty())
                    <div class="pt-2 border-t border-slate-200/60 space-y-1.5">
                        <span class="text-[10px] font-bold text-slate-500 uppercase block">Catatan Butir Terkait ({{ $penilaian->catatanPenilaian->count() }}):</span>
                        <div class="space-y-1.5">
                            @foreach ($penilaian->catatanPenilaian as $c)
                                <div class="p-2.5 rounded-xl bg-white border border-slate-200/60 text-[11px] flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[15px] {{ $c->selesai ? 'text-emerald-600' : 'text-amber-500' }} shrink-0 mt-0.5">
                                        {{ $c->selesai ? 'check_circle' : 'pending' }}
                                    </span>
                                    <div class="flex-1">
                                        <span class="text-slate-800">{{ $c->catatan }}</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5 font-mono">Penilai: {{ $c->user->name ?? 'User' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Section 1: Identitas Institusi -->
<div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-[18px]">apartment</span>
            </div>
            <h3 class="text-sm font-bold text-slate-900">1. Identitas Institusi Pengusul</h3>
        </div>
        @can('update', $suratPengajuan)
        <button type="button" wire:click="bukaModalFormulir" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-primary-700 bg-primary-50 hover:bg-primary-100 transition cursor-pointer">
            <span class="material-symbols-outlined text-[14px]">edit</span>
            <span>Edit Formulir</span>
        </button>
        @endcan
    </div>

    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
        <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
            <dt class="text-slate-400 font-medium">Nama Institusi</dt>
            <dd class="text-slate-900 font-bold text-sm mt-0.5">{{ $suratPengajuan->formulirAplikasi->nama_institusi ?? '-' }}</dd>
        </div>
        <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
            <dt class="text-slate-400 font-medium">Singkatan</dt>
            <dd class="text-slate-800 font-bold text-sm mt-0.5">{{ $suratPengajuan->formulirAplikasi->singkatan ?? '-' }}</dd>
        </div>
        <div class="sm:col-span-2 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
            <dt class="text-slate-400 font-medium">Alamat Lengkap</dt>
            <dd class="text-slate-700 mt-0.5 leading-relaxed">{{ $suratPengajuan->formulirAplikasi->alamat ?? '-' }}</dd>
        </div>
        <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
            <dt class="text-slate-400 font-medium">Kota / Kabupaten</dt>
            <dd class="text-slate-800 font-semibold mt-0.5">{{ $suratPengajuan->formulirAplikasi->kota ?? '-' }}</dd>
        </div>
        <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
            <dt class="text-slate-400 font-medium">Kontak & Surel</dt>
            <dd class="text-slate-700 mt-0.5">{{ $suratPengajuan->formulirAplikasi->telepon ?? '-' }} / {{ $suratPengajuan->formulirAplikasi->email ?? '-' }}</dd>
        </div>
    </dl>
</div>

<!-- Section 2: Visi & Misi KEPK -->
<div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
            </div>
            <h3 class="text-sm font-bold text-slate-900">2. Visi & Misi KEPK</h3>
        </div>
        @can('update', $suratPengajuan)
        <button type="button" wire:click="bukaModalProfil" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-primary-700 bg-primary-50 hover:bg-primary-100 transition cursor-pointer">
            <span class="material-symbols-outlined text-[14px]">edit</span>
            <span>Edit Profil</span>
        </button>
        @endcan
    </div>

    <div class="space-y-3 text-xs">
        <div>
            <div class="text-slate-400 font-medium mb-1">Deskripsi / Gambaran Umum Komite:</div>
            <p class="text-slate-700 leading-relaxed bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100 whitespace-pre-line">{{ $suratPengajuan->profilKepk->deskripsi ?? 'Belum diisi.' }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                <div class="text-primary-700 font-bold mb-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">flag</span>
                    <span>Visi KEPK:</span>
                </div>
                <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $suratPengajuan->profilKepk->visi ?? 'Belum diisi.' }}</p>
            </div>
            <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                <div class="text-primary-700 font-bold mb-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">task_alt</span>
                    <span>Misi KEPK:</span>
                </div>
                <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $suratPengajuan->profilKepk->misi ?? 'Belum diisi.' }}</p>
            </div>
        </div>
    </div>
</div>