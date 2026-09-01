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