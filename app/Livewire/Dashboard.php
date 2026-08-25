<?php

namespace App\Livewire;

use App\Models\SuratPengajuan;
use App\Services\ComplianceService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(ComplianceService $complianceService): View
    {
        $user = auth()->user();

        $data = [
            'user' => $user,
        ];

        if ($user->isReviewer() && ! $user->isAdmin()) {
            $assignedSubmissions = $user->pengajuanDinilai()
                ->with(['kepk.institusi', 'formulirAplikasi', 'penilaianEtik'])
                ->latest()
                ->get();

            $data['assignedSubmissions'] = $assignedSubmissions;
            $data['totalAssigned'] = $assignedSubmissions->count();
            $data['pendingReview'] = $assignedSubmissions->whereIn('status', ['submitted', 'under_review', 'resubmitted'])->count();
            $data['revisionRequired'] = $assignedSubmissions->where('status', 'revision_required')->count();
            $data['approvedCount'] = $assignedSubmissions->where('status', 'approved')->count();
        } elseif ($user->isAdmin()) {
            $allSubmissions = SuratPengajuan::with(['kepk.institusi', 'formulirAplikasi', 'penilai', 'user'])
                ->latest()
                ->get();

            $data['allSubmissions'] = $allSubmissions;
            $data['totalAll'] = $allSubmissions->count();
            $data['needAssign'] = $allSubmissions->where('status', 'submitted')->where(fn ($s) => $s->penilai->isEmpty())->count();
            $data['underReviewAll'] = $allSubmissions->whereIn('status', ['under_review', 'resubmitted'])->count();
            $data['approvedAll'] = $allSubmissions->where('status', 'approved')->count();
        } else {
            $suratPengajuan = SuratPengajuan::with(['kepk.institusi', 'formulirAplikasi', 'profilKepk', 'jawabanEvaluasi.butir.kelompok.bagian'])
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            if (! $suratPengajuan && ($user->isKetuaKepk() || $user->isAnggotaKepk())) {
                $suratPengajuan = SuratPengajuan::with(['kepk.institusi', 'formulirAplikasi', 'profilKepk', 'jawabanEvaluasi.butir.kelompok.bagian'])
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

            $gapAnalysis = $suratPengajuan
                ? $complianceService->calculateGapAnalysis($suratPengajuan)
                : ['critical_findings_count' => 0, 'critical_findings' => [], 'top_improvements' => []];

            $data['suratPengajuan'] = $suratPengajuan;
            $data['metrics'] = $metrics;
            $data['gapAnalysis'] = $gapAnalysis;
        }

        return view('livewire.dashboard', $data)->layout('layouts.app');
    }
}
