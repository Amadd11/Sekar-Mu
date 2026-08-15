<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationInformation;
use App\Models\ApplicationMember;
use App\Models\ApplicationProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ApplicationService
{
    /**
     * Create a new draft application with initial information and profile.
     *
     * @param  array<string, mixed>  $data
     */
    public function createDraft(User $user, array $data): Application
    {
        return DB::transaction(function () use ($user, $data) {
            $application = Application::create([
                'user_id' => $user->id,
                'kepk_id' => $data['kepk_id'],
                'status' => Application::STATUS_DRAFT,
            ]);

            ApplicationInformation::create([
                'application_id' => $application->id,
                'name' => $data['name'],
                'abbreviation' => $data['abbreviation'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
            ]);

            ApplicationProfile::create([
                'application_id' => $application->id,
                'description' => $data['description'] ?? null,
                'vision' => $data['vision'] ?? null,
                'mission' => $data['mission'] ?? null,
            ]);

            return $application->load(['kepk.institution', 'information', 'profile']);
        });
    }

    /**
     * Update application general information.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateInformation(Application $application, array $data): ApplicationInformation
    {
        return DB::transaction(function () use ($application, $data) {
            return ApplicationInformation::updateOrCreate(
                ['application_id' => $application->id],
                [
                    'name' => $data['name'],
                    'abbreviation' => $data['abbreviation'] ?? null,
                    'address' => $data['address'] ?? null,
                    'city' => $data['city'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'email' => $data['email'] ?? null,
                ]
            );
        });
    }

    /**
     * Update application profile (description, vision, mission).
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(Application $application, array $data): ApplicationProfile
    {
        return DB::transaction(function () use ($application, $data) {
            return ApplicationProfile::updateOrCreate(
                ['application_id' => $application->id],
                [
                    'description' => $data['description'] ?? null,
                    'vision' => $data['vision'] ?? null,
                    'mission' => $data['mission'] ?? null,
                ]
            );
        });
    }

    /**
     * Add a member to the application.
     *
     * @param  array<string, mixed>  $data
     */
    public function addMember(Application $application, array $data): ApplicationMember
    {
        return DB::transaction(function () use ($application, $data) {
            return ApplicationMember::create([
                'application_id' => $application->id,
                'name' => $data['name'],
                'position' => $data['position'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);
        });
    }

    /**
     * Remove a member from the application.
     */
    public function removeMember(ApplicationMember $member): bool
    {
        return (bool) $member->delete();
    }

    /**
     * Submit an application (transitions from draft / revision_required to submitted / resubmitted).
     */
    public function submit(Application $application): bool
    {
        if (! $application->isEditable()) {
            throw new InvalidArgumentException('Pengajuan ini tidak dapat diajukan karena statusnya terkunci.');
        }

        return DB::transaction(function () use ($application) {
            $newStatus = $application->isRevisionRequired()
                ? Application::STATUS_RESUBMITTED
                : Application::STATUS_SUBMITTED;

            return $application->update([
                'status' => $newStatus,
                'submitted_at' => now(),
            ]);
        });
    }

    /**
     * Delete an application.
     */
    public function delete(Application $application): bool
    {
        return DB::transaction(function () use ($application) {
            return (bool) $application->delete();
        });
    }
}
