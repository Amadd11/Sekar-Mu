<?php

use App\Livewire\Applications\SelfAssessment;
use App\Models\Application;
use App\Models\AssessmentGroup;
use App\Models\AssessmentItem;
use App\Models\AssessmentSection;
use App\Models\Institution;
use App\Models\Kepk;
use App\Models\User;
use App\Services\SelfAssessmentService;
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

    $this->section = AssessmentSection::create([
        'code' => 'A',
        'name' => 'Struktur dan Komposisi KEP',
        'order' => 1,
    ]);

    $this->group = AssessmentGroup::create([
        'assessment_section_id' => $this->section->id,
        'name' => 'Organisasi KEPK',
        'order' => 1,
    ]);

    $this->item1 = AssessmentItem::create([
        'assessment_group_id' => $this->group->id,
        'question' => 'Apakah KEPK memiliki SK pendirian resmi?',
        'order' => 1,
    ]);

    $this->item2 = AssessmentItem::create([
        'assessment_group_id' => $this->group->id,
        'question' => 'Apakah KEPK memiliki buku pedoman SOP baku?',
        'order' => 2,
    ]);
});

test('applicant can view self assessment page and answer questionnaire', function () {
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    Livewire::actingAs($applicant)
        ->test(SelfAssessment::class, ['application' => $application])
        ->assertSee('Struktur dan Komposisi KEP')
        ->set("answers.{$this->item1->id}.score", 'A')
        ->set("answers.{$this->item1->id}.evidence", 'SK Rektor No. 123/2025')
        ->set("answers.{$this->item1->id}.comment", 'Sudah terverifikasi')
        ->call('saveItem', $this->item1->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('assessment_answers', [
        'application_id' => $application->id,
        'assessment_item_id' => $this->item1->id,
        'score' => 'A',
        'evidence' => 'SK Rektor No. 123/2025',
    ]);
});

test('self assessment service accurately calculates progress and scores', function () {
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $service = app(SelfAssessmentService::class);

    $service->saveAnswer($application, $this->item1->id, ['score' => 'A']);
    $service->saveAnswer($application, $this->item2->id, ['score' => 'B']);

    $progress = $service->calculateProgress($application);
    expect($progress['A']['answered'])->toBe(2);
    expect($progress['A']['percentage'])->toBe(100);

    $summary = $service->calculateScoreSummary($application);
    expect($summary['total'])->toBe(2);
    expect($summary['score_a'])->toBe(1);
    expect($summary['score_b'])->toBe(1);
    expect($summary['score_c'])->toBe(0);
});
