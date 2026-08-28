<?php

namespace App\Livewire;

use App\Models\SuratPengajuan;
use App\Services\ComplianceService;
use App\Services\PenilaianService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(ComplianceService $complianceService, PenilaianService $penilaianService): View
    {
        $user = auth()->user();

        $data = [
            'user' => $user,
        ];

        if ($user->isAsessor() && ! $user->isAdmin()) {
            $assignedSubmissions = SuratPengajuan::with([
                'kepk.institusi',
                'formulirAplikasi',
                'penilaianEtik.penilai',
                'penilai',
                'jawabanEvaluasi',
                'penilaianButirAsesor',
            ])
                ->whereHas('penilai', fn ($q) => $q->where('user_id', $user->id))
                ->latest()
                ->get()
                ->map(function ($s) use ($complianceService) {
                    $s->calculated_metrics = $complianceService->calculateComplianceMetrics($s);
                    return $s;
                });

            $data['assignedSubmissions'] = $assignedSubmissions;
            $data['totalAssigned'] = $assignedSubmissions->count();
            $data['inProgressCount'] = $assignedSubmissions->where('status', SuratPengajuan::STATUS_IN_PROGRESS)->count();
            $data['approvedCount'] = $assignedSubmissions->where('status', SuratPengajuan::STATUS_APPROVED)->count();
            $data['rejectedCount'] = $assignedSubmissions->where('status', SuratPengajuan::STATUS_REJECTED)->count();
        } elseif ($user->isAdmin()) {
            $allSubmissions = SuratPengajuan::with([
                'kepk.institusi',
                'formulirAplikasi',
                'penilai',
                'penilaianEtik.penilai',
                'jawabanEvaluasi.butir.kelompok.bagian',
                'penilaianButirAsesor',
                'user',
            ])
                ->latest()
                ->get()
                ->map(function ($s) use ($complianceService) {
                    $s->calculated_metrics = $complianceService->calculateComplianceMetrics($s);
                    return $s;
                });

            $latestSubmission = $allSubmissions->first();
            $latestMetrics = $latestSubmission ? $latestSubmission->calculated_metrics : null;

            $data['allSubmissions'] = $allSubmissions;
            $data['latestSubmission'] = $latestSubmission;
            $data['latestMetrics'] = $latestMetrics;
            $data['totalAll'] = $allSubmissions->count();
            $data['inProgressAll'] = $allSubmissions->where('status', SuratPengajuan::STATUS_IN_PROGRESS)->count();
            $data['needAssign'] = $allSubmissions->where(fn ($s) => $s->penilai->isEmpty())->count();
            $data['approvedAll'] = $allSubmissions->where('status', SuratPengajuan::STATUS_APPROVED)->count();
            $data['rejectedAll'] = $allSubmissions->where('status', SuratPengajuan::STATUS_REJECTED)->count();
            $data['totalAsesor'] = \App\Models\User::role('asessor')->count();
            $data['totalKepk'] = \App\Models\Kepk::count();
            $data['totalUsers'] = \App\Models\User::count();
            $data['asesorList'] = \App\Models\User::role('asessor')->with(['roles'])->get();
        } else {
            // Ketua KEPK & Anggota KEPK
            $suratPengajuan = SuratPengajuan::with([
                'kepk.institusi',
                'formulirAplikasi',
                'profilKepk',
                'jawabanEvaluasi.butir.kelompok.bagian',
                'penilai',
                'penilaianEtik.penilai',
                'penilaianButirAsesor',
            ])
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            if (! $suratPengajuan && ($user->isKetuaKepk() || $user->isAnggota())) {
                $suratPengajuan = SuratPengajuan::with([
                    'kepk.institusi',
                    'formulirAplikasi',
                    'profilKepk',
                    'jawabanEvaluasi.butir.kelompok.bagian',
                    'penilai',
                    'penilaianEtik.penilai',
                    'penilaianButirAsesor',
                ])
                    ->latest()
                    ->first();
            }

            $metrics = $suratPengajuan
                ? $complianceService->calculateComplianceMetrics($suratPengajuan)
                : [
                    'overall_compliance' => 0,
                    'overall_completion' => 0,
                    'total_items' => 164,
                    'total_answered' => 0,
                    'counts' => ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'total' => 0],
                    'sections' => [],
                    'prediction' => $complianceService->classifyAccreditation(0, 0, 0),
                ];

            $comparisonMatrix = $suratPengajuan
                ? $penilaianService->getComparisonMatrix($suratPengajuan)
                : ['total_items' => 164, 'total_matches' => 0, 'total_gaps' => 0, 'sections' => []];

            // Hitung kelengkapan dokumen bukti yang diunggah
            $totalAttachedItems = 0;
            if ($suratPengajuan) {
                foreach ($suratPengajuan->jawabanEvaluasi as $jawaban) {
                    if (! empty($jawaban->file_attachments) && is_array($jawaban->file_attachments) && count($jawaban->file_attachments) > 0) {
                        $totalAttachedItems++;
                    }
                }
            }

            $data['suratPengajuan'] = $suratPengajuan;
            $data['metrics'] = $metrics;
            $data['comparisonMatrix'] = $comparisonMatrix;
            $data['totalAttachedItems'] = $totalAttachedItems;
        }

        return view('livewire.dashboard', $data)->layout('layouts.app');
    }
}
