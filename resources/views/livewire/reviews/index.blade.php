<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header Banner -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl select-none">🔍</span>
                <h1 class="text-lg font-bold text-slate-900">
                    Portal Telaah Etik KEPK (Reviewer Workspace)
                </h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Daftar berkas permohonan etik yang ditugaskan kepada Anda untuk diperiksa kelayakannya.
            </p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-2xs flex flex-col sm:flex-row items-center gap-3">
        <div class="w-full sm:flex-1 relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nomor berkas, nama institusi..."
                class="w-full text-xs rounded-lg border-slate-300 py-2 ps-9 pe-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
            />
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="w-full sm:w-auto">
            <select
                wire:model.live="statusFilter"
                class="w-full sm:w-48 text-xs rounded-lg border-slate-300 py-2 px-3 focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
            >
                <option value="">Semua Status</option>
                <option value="submitted">Diajukan</option>
                <option value="under_review">Sedang Ditelaah</option>
                <option value="revision_required">Perlu Revisi</option>
                <option value="resubmitted">Diajukan Ulang</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">No. Berkas</th>
                        <th class="px-5 py-3.5">Institusi & Pemohon</th>
                        <th class="px-5 py-3.5">Status Pengajuan</th>
                        <th class="px-5 py-3.5">Hasil Review Anda</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($applications as $app)
                        @php
                            $userReview = $app->reviews->firstWhere('reviewer_id', auth()->id());
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-4 font-mono font-bold text-slate-700">
                                #APP-{{ str_pad($app->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900 leading-snug">
                                    {{ $app->information->name ?? 'Tanpa Nama' }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $app->kepk->name ?? '-' }} • {{ $app->information->city ?? '-' }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ \App\Models\Application::statusBadgeClasses($app->status) }}">
                                    {{ \App\Models\Application::statusLabel($app->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if ($userReview)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold border {{ \App\Models\Review::recommendationBadgeClasses($userReview->recommendation) }}">
                                        {{ \App\Models\Review::recommendationLabel($userReview->recommendation) }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Belum Ditelaah</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a
                                    href="{{ route('reviews.show', $app) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#174668] hover:bg-[#133e5f] transition shadow-2xs"
                                    wire:navigate
                                >
                                    <span>🔍 Buka Telaah</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                Tidak ada berkas pengajuan etik yang perlu ditelaah saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($applications->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
