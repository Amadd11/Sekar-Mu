<?php

namespace App\Services;

use App\Models\CorrectiveAction;
use App\Models\SuratPengajuan;
use Illuminate\Support\Facades\DB;

class CorrectiveActionService
{
    /**
     * Create a new corrective action item.
     *
     * @param  array<string, mixed>  $data
     */
    public function createAction(SuratPengajuan $surat, array $data): CorrectiveAction
    {
        return DB::transaction(function () use ($surat, $data) {
            return CorrectiveAction::create([
                'surat_pengajuan_id' => $surat->id,
                'butir_evaluasi_id' => $data['butir_evaluasi_id'] ?? null,
                'finding' => $data['finding'],
                'risk' => $data['risk'] ?? null,
                'action' => $data['action'],
                'pic_name' => $data['pic_name'] ?? null,
                'priority' => $data['priority'] ?? CorrectiveAction::PRIORITY_MEDIUM,
                'deadline' => $data['deadline'] ?? null,
                'status' => CorrectiveAction::STATUS_OPEN,
                'evidence_path' => $data['evidence_path'] ?? null,
                'verification_notes' => null,
            ]);
        });
    }

    /**
     * Update an existing corrective action item.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateAction(CorrectiveAction $action, array $data): CorrectiveAction
    {
        return DB::transaction(function () use ($action, $data) {
            $action->update([
                'butir_evaluasi_id' => $data['butir_evaluasi_id'] ?? $action->butir_evaluasi_id,
                'finding' => $data['finding'] ?? $action->finding,
                'risk' => $data['risk'] ?? $action->risk,
                'action' => $data['action'] ?? $action->action,
                'pic_name' => $data['pic_name'] ?? $action->pic_name,
                'priority' => $data['priority'] ?? $action->priority,
                'deadline' => $data['deadline'] ?? $action->deadline,
                'status' => $data['status'] ?? $action->status,
                'evidence_path' => $data['evidence_path'] ?? $action->evidence_path,
                'verification_notes' => $data['verification_notes'] ?? $action->verification_notes,
            ]);

            return $action->fresh();
        });
    }

    /**
     * Update status with verification notes.
     */
    public function updateStatus(CorrectiveAction $action, string $status, ?string $notes = null): CorrectiveAction
    {
        return DB::transaction(function () use ($action, $status, $notes) {
            $updateData = ['status' => $status];
            if ($notes !== null) {
                $updateData['verification_notes'] = $notes;
            }

            $action->update($updateData);

            return $action->fresh();
        });
    }

    /**
     * Auto-generate corrective actions for any critical finding not yet covered.
     *
     * @return int Count of newly generated corrective actions
     */
    public function syncCriticalFindings(SuratPengajuan $surat, ComplianceService $complianceService): int
    {
        $findings = $complianceService->getCriticalFindings($surat);
        $count = 0;

        DB::transaction(function () use ($surat, $findings, &$count) {
            foreach ($findings as $finding) {
                $exists = CorrectiveAction::where('surat_pengajuan_id', $surat->id)
                    ->where('butir_evaluasi_id', $finding['butir_id'])
                    ->exists();

                if (! $exists) {
                    CorrectiveAction::create([
                        'surat_pengajuan_id' => $surat->id,
                        'butir_evaluasi_id' => $finding['butir_id'],
                        'finding' => "Temuan Kritis pada Bagian {$finding['kode_bagian']} (Butir #{$finding['urutan']}): {$finding['pertanyaan']}",
                        'risk' => 'Ketidakpatuhan kritis yang dapat menggugurkan pemenuhan standar akreditasi Tipe A/B KEPK.',
                        'action' => 'Penyusunan/revisi dokumen regulasi atau SOP terkait, pemenuhan bukti pendukung, dan evaluasi kepatuhan.',
                        'priority' => CorrectiveAction::PRIORITY_HIGH,
                        'deadline' => now()->addDays(30)->toDateString(),
                        'status' => CorrectiveAction::STATUS_OPEN,
                    ]);
                    $count++;
                }
            }
        });

        return $count;
    }

    /**
     * Delete a corrective action item.
     */
    public function deleteAction(CorrectiveAction $action): bool
    {
        return DB::transaction(function () use ($action) {
            return (bool) $action->delete();
        });
    }
}
