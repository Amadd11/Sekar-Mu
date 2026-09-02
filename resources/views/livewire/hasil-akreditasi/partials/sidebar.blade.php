<!-- Card 1: Asesor Ditugaskan -->
<div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs space-y-4">
    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-700 text-[18px]">badge</span>
            <h3 class="text-sm font-bold text-slate-900">Asesor Ditugaskan</h3>
        </div>
        @if ($isAdmin)
            <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="text-[11px] font-bold text-primary-700 hover:underline" wire:navigate>
                Kelola
            </a>
        @endif
    </div>

    <div class="space-y-3">
        @forelse ($suratPengajuan->penilai as $rev)
            @php
            $penilaian = $suratPengajuan->penilaianEtik->firstWhere('penilai_id', $rev->id);
            @endphp
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                <div class="flex items-center gap-3 text-xs">
                    <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr($rev->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden flex-1">
                        <div class="font-bold text-slate-900 truncate">{{ $rev->name }}</div>
                        <div class="text-[11px] text-slate-500 truncate">{{ $rev->email }}</div>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-200/70 flex items-center justify-between text-[11px]">
                    <span class="text-slate-500 font-medium">Rekomendasi:</span>
                    @if ($penilaian)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $penilaian->badge_rekomendasi }}">
                            {{ $penilaian->label_rekomendasi }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="material-symbols-outlined text-[12px]">hourglass_top</span>
                            <span>Menunggu Telaah</span>
                        </span>
                    @endif
                </div>

                @if ($penilaian && $penilaian->catatan)
                    <p class="text-[11px] text-slate-600 bg-white p-2.5 rounded-xl border border-slate-200/60 italic leading-relaxed">
                        "{{ Str::limit($penilaian->catatan, 140) }}"
                    </p>
                @endif
            </div>
        @empty
            <p class="text-xs text-slate-400 italic text-center py-2">Belum ada asesor penilai ditugaskan.</p>
            @if ($isAdmin)
                <a href="{{ route('penilaian.tugaskan', $suratPengajuan) }}" class="block text-center py-2 px-3 bg-primary-700 text-white text-xs font-bold rounded-xl hover:bg-primary-600 transition shadow-2xs" wire:navigate>
                    + Tugaskan Asesor
                </a>
            @endif
        @endforelse
    </div>
</div>

<!-- Card 2: Komite Etik Info -->
<div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs space-y-3">
    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
        <span class="material-symbols-outlined text-primary-700 text-[18px]">verified_user</span>
        <h3 class="text-sm font-bold text-slate-900">Komite Etik (KEPK)</h3>
    </div>
    <div class="text-xs space-y-1">
        <div class="font-bold text-slate-900 text-sm leading-snug">{{ $suratPengajuan->kepk->name ?? '-' }}</div>
        <div class="text-slate-500">Institusi: {{ $suratPengajuan->kepk->institusi->name ?? '-' }}</div>
        <div class="font-mono text-slate-400 text-[11px] pt-1">Kode: {{ $suratPengajuan->kepk->code ?? '-' }}</div>
    </div>
</div>

<!-- Card 3: Informasi Meta Berkas -->
<div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs space-y-3">
    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
        <span class="material-symbols-outlined text-primary-700 text-[18px]">info</span>
        <h3 class="text-sm font-bold text-slate-900">Informasi Berkas</h3>
    </div>
    <div class="space-y-2.5 text-xs">
        <div class="flex justify-between py-1 border-b border-slate-100">
            <span class="text-slate-500">Pemohon / Pengaju:</span>
            <span class="font-semibold text-slate-800">{{ $suratPengajuan->user->name ?? '-' }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-slate-100">
            <span class="text-slate-500">Tanggal Dibuat:</span>
            <span class="text-slate-700 font-mono">{{ $suratPengajuan->created_at->format('d M Y, H:i') }}</span>
        </div>
        @if ($suratPengajuan->diajukan_pada)
            <div class="flex justify-between py-1 border-b border-slate-100">
                <span class="text-slate-500">Tanggal Diajukan:</span>
                <span class="text-slate-700 font-mono">{{ $suratPengajuan->diajukan_pada->format('d M Y, H:i') }}</span>
            </div>
        @endif
        <div class="flex justify-between items-center py-1">
            <span class="text-slate-500">Status Saat Ini:</span>
            <x-pengajuan.status-badge :status="$suratPengajuan->status" />
        </div>
    </div>
</div>
