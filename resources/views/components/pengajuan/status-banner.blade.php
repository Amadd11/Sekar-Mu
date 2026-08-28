@props([
    'surat',
])

@php
    $status = $surat->status;
@endphp

@if ($surat->isInProgress())
    <div class="bg-gradient-to-r from-blue-50/90 via-teal-50/80 to-blue-50/90 border border-blue-200/90 rounded-2xl p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center shrink-0 shadow-2xs">
                <span class="material-symbols-outlined text-[22px]">sync</span>
            </div>
            <div>
                <div class="font-display font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span>Status Berkas:</span>
                    <x-pengajuan.status-badge :status="$status" />
                </div>
                <p class="text-xs text-slate-600 mt-1 max-w-2xl leading-relaxed">
                    Pengisian borang evaluasi diri & pengunggahan berkas berjalan secara kolaboratif real-time. Asesor dapat langsung menelaah dan memberikan skor.
                </p>
            </div>
        </div>
        {{ $slot }}
    </div>
@elseif ($surat->isApproved())
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 shadow-xs flex items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 shadow-2xs">
                <span class="material-symbols-outlined text-[22px]">verified</span>
            </div>
            <div>
                <div class="font-display font-bold text-emerald-950 text-sm flex items-center gap-2">
                    <span>Status Berkas:</span>
                    <x-pengajuan.status-badge :status="$status" />
                </div>
                <p class="text-xs text-emerald-800 mt-0.5 leading-relaxed">
                    Pengajuan telah selesai dinilai dan dinyatakan resmi <strong>Terakreditasi</strong>.
                </p>
            </div>
        </div>
        {{ $slot }}
    </div>
@elseif ($surat->isRejected())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-xs flex items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-800 flex items-center justify-center shrink-0 shadow-2xs">
                <span class="material-symbols-outlined text-[22px]">cancel</span>
            </div>
            <div>
                <div class="font-display font-bold text-rose-950 text-sm flex items-center gap-2">
                    <span>Status Berkas:</span>
                    <x-pengajuan.status-badge :status="$status" />
                </div>
                <p class="text-xs text-rose-800 mt-0.5 leading-relaxed">
                    Pengajuan berstatus <strong>Tidak Lolos</strong> akreditasi.
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
            </div>
        </div>
        {{ $slot }}
    </div>
@endif
