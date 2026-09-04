<div>
    @if (! $isAssigned && ! auth()->user()->isAdmin())
        @include('livewire.penilaian.partials.guard')
    @else
        <div class="space-y-6 max-w-7xl mx-auto pb-12">
            <!-- Header & 4 KPI Metric Cards -->
            @include('livewire.penilaian.partials.header')

            <!-- Main Content Routed via Tab Navigation ($activeTab) -->
            @php
                $perItemAnswersWithFiles = $suratPengajuan->jawabanEvaluasi->filter(fn($j) => $j->hasAttachments());
                $totalItemFilesCount = $perItemAnswersWithFiles->sum(fn($j) => count($j->getAttachments()));
                $totalAllFiles = $suratPengajuan->dokumen->count() + $totalItemFilesCount;
                $allComments = $semuaPenilaian->flatMap->catatanPenilaian;
            @endphp

            <!-- 1. TAB PENILAIAN BUTIR STANDAR (BORANG) -->
            @if ($activeTab === 'penilaian')
                @include('livewire.penilaian.partials.borang')

            <!-- 2. TAB DOKUMEN & PROTOKOL RISET -->
            @elseif ($activeTab === 'dokumen')
                @include('livewire.penilaian.partials.dokumen')

            <!-- 3. TAB FORM REKOMENDASI & REVIEW THREAD -->
            @elseif ($activeTab === 'rekomendasi')
                @include('livewire.penilaian.partials.rekomendasi')
            @endif
        </div>
    @endif
</div>