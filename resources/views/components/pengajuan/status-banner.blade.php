@props([
    'surat',
])

@php
    $status = $surat->status;
@endphp

@if ($surat->isDraft())
    <div class="bg-gradient-to-r from-teal-50/80 to-emerald-50/80 border border-teal-200 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">edit_note</span>
            </div>
            <div>
                <div class="font-display font-bold text-teal-950 text-sm flex items-center gap-2">
                    <span>Status Berkas:</span>
                    <x-pengajuan.status-badge :status="$status" />
                </div>
                <p class="text-xs text-teal-800 mt-1 max-w-2xl leading-relaxed">
                    Lengkapi seluruh borang evaluasi diri (164 butir), list protokol riset, dan dokumen lampiran sebelum diajukan ke tim penilai KEPK.
                </p>
            </div>
        </div>
        {{ $slot }}
    </div>
@elseif ($surat->isRevisionRequired())
    <div class="bg-amber-50 border border-amber-300 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">warning</span>
            </div>
            <div>
                <div class="font-display font-bold text-amber-950 text-sm flex items-center gap-2">
                    <span>Status Berkas:</span>
                    <x-pengajuan.status-badge :status="$status" />
                </div>
                <p class="text-xs text-amber-800 mt-1 max-w-2xl leading-relaxed">
                    Asesor telah memberikan catatan perbaikan. Silakan tinjau ulasan temuan di bawah, lakukan revisi, lalu klik tombol ajukan ulang.
                </p>
            </div>
        </div>
        {{ $slot }}
    </div>
@else
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[20px]">{{ $surat->status_icon }}</span>
            </div>
            <div>
                <div class="font-display font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span>Status Berkas:</span>
                    <x-pengajuan.status-badge :status="$status" />
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Diajukan pada {{ $surat->diajukan_pada?->format('d M Y, H:i') ?? '-' }}.
                </p>
            </div>
        </div>
    </div>
@endif
