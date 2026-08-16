<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ResearchProtocol;
use Illuminate\Support\Facades\DB;

class ResearchProtocolService
{
    /**
     * Create a new research protocol for an application.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(Application $application, array $data): ResearchProtocol
    {
        return DB::transaction(function () use ($application, $data) {
            return ResearchProtocol::create([
                'application_id' => $application->id,
                'protocol_number' => $data['protocol_number'],
                'title' => $data['title'],
                'principal_investigator' => $data['principal_investigator'],
                'submission_date' => $data['submission_date'] ?? now()->toDateString(),
                'status' => $data['status'] ?? 'draft',
            ]);
        });
    }

    /**
     * Update an existing research protocol.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(ResearchProtocol $protocol, array $data): ResearchProtocol
    {
        return DB::transaction(function () use ($protocol, $data) {
            $protocol->update([
                'protocol_number' => $data['protocol_number'],
                'title' => $data['title'],
                'principal_investigator' => $data['principal_investigator'],
                'submission_date' => $data['submission_date'] ?? $protocol->submission_date,
                'status' => $data['status'] ?? $protocol->status,
            ]);

            return $protocol;
        });
    }

    /**
     * Delete a research protocol.
     */
    public function delete(ResearchProtocol $protocol): bool
    {
        return DB::transaction(function () use ($protocol) {
            return (bool) $protocol->delete();
        });
    }
}
