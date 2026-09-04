<div class="max-w-4xl mx-auto py-12 px-4 space-y-6">
    <div class="bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-xs text-center relative overflow-hidden space-y-6">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-amber-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center mx-auto shadow-2xs">
            <span class="material-symbols-outlined text-[42px]">lock_clock</span>
        </div>

        <div class="space-y-2 max-w-xl mx-auto">
            <div class="flex items-center justify-center gap-2">
                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200">
                    No. {{ $suratPengajuan->formatted_id }}
                </span>
            </div>
            <h1 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug">
                Akses Lembar Kerja Penilaian Belum Ditugaskan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Anda belum ditugaskan oleh Administrator untuk menelaah berkas permohonan akreditasi
                <strong class="text-slate-700 font-semibold">{{ $suratPengajuan->formulirAplikasi->nama_institusi ?? 'KEPK Pemohon' }}</strong>.
            </p>
        </div>

        <div class="p-5 sm:p-6 rounded-2xl bg-slate-50/80 border border-slate-200/70 max-w-lg mx-auto text-left space-y-3.5 shadow-2xs">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-800 border-b border-slate-200/60 pb-2.5">
                <span class="material-symbols-outlined text-teal-600 text-[18px]">verified_user</span>
                <span>Mengapa Halaman Ini Muncul?</span>
            </div>
            <ul class="text-xs text-slate-600 space-y-2.5 leading-relaxed">
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-[16px] shrink-0 mt-0.5">check_circle</span>
                    <span>Berdasarkan standar etika KEPPKN & WHO, asesor hanya dapat menelaah berkas permohonan yang ditugaskan secara resmi oleh Administrator.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-[16px] shrink-0 mt-0.5">check_circle</span>
                    <span>Apabila Administrator telah menugaskan Anda pada berkas ini, tugas akan otomatis muncul di antrean <strong>Dashboard Asesor</strong>.</span>
                </li>
            </ul>
        </div>

        <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-xs font-bold text-white bg-primary-700 hover:bg-primary-600 active:bg-primary-800 transition shadow-md shadow-primary-700/20 cursor-pointer"
                wire:navigate>
                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                <span>Kembali ke Dashboard Asesor</span>
            </a>
        </div>
    </div>
</div>
