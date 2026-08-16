<?php

use App\Livewire\Applications\Show;
use App\Livewire\Reviews\AssignReviewers;
use App\Livewire\Reviews\Index;
use App\Livewire\Reviews\Review as ReviewWorkbench;
use App\Models\Application;
use App\Models\Institution;
use App\Models\Kepk;
use App\Models\User;
use App\Services\ReviewService;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $roles = ['admin', 'applicant', 'reviewer'];
    foreach ($roles as $role) {
        Role::firstOrCreate(['name' => $role]);
    }

    $this->institution = Institution::create([
        'name' => 'Universitas Muhammadiyah Yogyakarta',
        'city' => 'Yogyakarta',
    ]);

    $this->kepk = Kepk::create([
        'institution_id' => $this->institution->id,
        'name' => 'KEPK UMY',
        'code' => 'KEPK-UMY-001',
        'status' => 'active',
    ]);
});

test('admin can assign reviewers to application', function () {
    $admin = User::factory()->admin()->create();
    $applicant = User::factory()->applicant()->create();
    $reviewer = User::factory()->reviewer()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    Livewire::actingAs($admin)
        ->test(AssignReviewers::class, ['application' => $application])
        ->set('selectedReviewerIds', [$reviewer->id])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('application_reviewers', [
        'application_id' => $application->id,
        'user_id' => $reviewer->id,
    ]);

    $application->refresh();
    expect($application->status)->toBe('under_review');
});

test('reviewer can view assigned application and submit recommendation with comments', function () {
    $applicant = User::factory()->applicant()->create();
    $reviewer = User::factory()->reviewer()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    $service = app(ReviewService::class);
    $service->assignReviewers($application, [$reviewer->id], $reviewer);

    // View index
    Livewire::actingAs($reviewer)
        ->test(Index::class)
        ->assertSee("#APP-" . str_pad($application->id, 5, '0', STR_PAD_LEFT));

    // Submit review recommendation
    Livewire::actingAs($reviewer)
        ->test(ReviewWorkbench::class, ['application' => $application])
        ->set('recommendation', 'revision_required')
        ->set('notes', 'Mohon lengkapi SOP nomor 4.')
        ->call('submitReview')
        ->assertHasNoErrors()
        ->set('newComment', 'Perlu bukti akreditasi lab.')
        ->call('postComment')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('reviews', [
        'application_id' => $application->id,
        'reviewer_id' => $reviewer->id,
        'recommendation' => 'revision_required',
    ]);

    $this->assertDatabaseHas('review_comments', [
        'user_id' => $reviewer->id,
        'comment' => 'Perlu bukti akreditasi lab.',
    ]);

    $application->refresh();
    expect($application->status)->toBe('revision_required');
});

test('applicant can resubmit revision and admin can finalize approved decision', function () {
    $admin = User::factory()->admin()->create();
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'revision_required',
    ]);

    $application->information()->create([
        'name' => 'Fakultas Kedokteran UMY',
    ]);

    // Applicant resubmits
    Livewire::actingAs($applicant)
        ->test(Show::class, ['application' => $application])
        ->call('resubmitApplication')
        ->assertHasNoErrors();

    $application->refresh();
    expect($application->status)->toBe('resubmitted');

    // Admin approves
    Livewire::actingAs($admin)
        ->test(Show::class, ['application' => $application])
        ->call('finalizeDecision', 'approved')
        ->assertHasNoErrors();

    $application->refresh();
    expect($application->status)->toBe('approved');
});
