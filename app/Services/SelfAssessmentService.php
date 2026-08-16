<?php

namespace App\Services;

use App\Models\Application;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentItem;
use App\Models\AssessmentSection;
use Illuminate\Support\Facades\DB;

class SelfAssessmentService
{
    /**
     * Save or update an assessment answer for a specific item.
     *
     * @param  array<string, mixed>  $data
     */
    public function saveAnswer(Application $application, int $itemId, array $data): AssessmentAnswer
    {
        return DB::transaction(function () use ($application, $itemId, $data) {
            return AssessmentAnswer::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'assessment_item_id' => $itemId,
                ],
                [
                    'score' => $data['score'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'evidence' => $data['evidence'] ?? null,
                ]
            );
        });
    }

    /**
     * Calculate progress for each section for the given application.
     *
     * @return array<string, array<string, mixed>>
     */
    public function calculateProgress(Application $application): array
    {
        $sections = AssessmentSection::with('items')->orderBy('order')->get();
        $answeredItemIds = $application->answers()
            ->whereNotNull('score')
            ->pluck('assessment_item_id')
            ->toArray();

        $progress = [];

        foreach ($sections as $section) {
            $totalItems = $section->items->count();
            $sectionItemIds = $section->items->pluck('id')->toArray();
            $answeredInSection = count(array_intersect($sectionItemIds, $answeredItemIds));
            $percentage = $totalItems > 0 ? (int) round(($answeredInSection / $totalItems) * 100) : 0;

            $progress[$section->code] = [
                'name' => $section->name,
                'total' => $totalItems,
                'answered' => $answeredInSection,
                'percentage' => $percentage,
            ];
        }

        return $progress;
    }

    /**
     * Calculate score breakdown summary (Total, A, B, C) for the given application.
     *
     * @return array<string, int>
     */
    public function calculateScoreSummary(Application $application): array
    {
        $answers = $application->answers()->whereNotNull('score')->get();

        return [
            'total' => $answers->count(),
            'score_a' => $answers->where('score', 'A')->count(),
            'score_b' => $answers->where('score', 'B')->count(),
            'score_c' => $answers->where('score', 'C')->count(),
        ];
    }
}
