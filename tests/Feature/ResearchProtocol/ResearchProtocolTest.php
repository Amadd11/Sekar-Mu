<?php

use App\Livewire\Applications\ResearchProtocols;
use App\Models\Application;
use App\Models\Institution;
use App\Models\Kepk;
use App\Models\ResearchProtocol;
use App\Models\User;
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

test('applicant can add, edit, and delete research protocol', function () {
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    // Create protocol
    Livewire::actingAs($applicant)
        ->test(ResearchProtocols::class, ['application' => $application])
        ->set('protocol_number', 'PROT/2026/01')
        ->set('title', 'Uji Efektivitas Ekstrak Daun Kelor')
        ->set('principal_investigator', 'Dr. Ahmad Fauzi')
        ->set('submission_date', '2026-08-16')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('research_protocols', [
        'application_id' => $application->id,
        'protocol_number' => 'PROT/2026/01',
        'title' => 'Uji Efektivitas Ekstrak Daun Kelor',
        'principal_investigator' => 'Dr. Ahmad Fauzi',
    ]);

    $protocol = ResearchProtocol::where('protocol_number', 'PROT/2026/01')->first();

    // Edit protocol
    Livewire::actingAs($applicant)
        ->test(ResearchProtocols::class, ['application' => $application])
        ->call('edit', $protocol->id)
        ->set('title', 'Uji Klinis Ekstrak Daun Kelor Fase 2')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('research_protocols', [
        'id' => $protocol->id,
        'title' => 'Uji Klinis Ekstrak Daun Kelor Fase 2',
    ]);

    // Delete protocol
    Livewire::actingAs($applicant)
        ->test(ResearchProtocols::class, ['application' => $application])
        ->call('delete', $protocol->id);

    $this->assertDatabaseMissing('research_protocols', [
        'id' => $protocol->id,
    ]);
});
