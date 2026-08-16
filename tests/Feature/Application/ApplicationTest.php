<?php

use App\Livewire\Applications\Create;
use App\Livewire\Applications\Information;
use App\Livewire\Applications\Profile;
use App\Livewire\Applications\Show;
use App\Models\Application;
use App\Models\Institution;
use App\Models\Kepk;
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
        'name' => 'Universitas Muhammadiyah Surakarta',
        'city' => 'Surakarta',
    ]);

    $this->kepk = Kepk::create([
        'institution_id' => $this->institution->id,
        'name' => 'KEPK FK UMS',
        'code' => 'KEPK-UMS-001',
        'status' => 'active',
    ]);
});

test('applicant can view applications index page', function () {
    $applicant = User::factory()->applicant()->create();

    $this->actingAs($applicant)
        ->get(route('applications.index'))
        ->assertOk()
        ->assertSee('Daftar Pengajuan Etik');
});

test('applicant can create a draft application via livewire', function () {
    $applicant = User::factory()->applicant()->create();

    Livewire::actingAs($applicant)
        ->test(Create::class)
        ->set('kepk_id', $this->kepk->id)
        ->set('name', 'Fakultas Kedokteran UMS')
        ->set('abbreviation', 'FK-UMS')
        ->set('city', 'Surakarta')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('applications', [
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $this->assertDatabaseHas('application_informations', [
        'name' => 'Fakultas Kedokteran UMS',
        'abbreviation' => 'FK-UMS',
        'city' => 'Surakarta',
    ]);
});

test('applicant can update application information', function () {
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    Livewire::actingAs($applicant)
        ->test(Information::class, ['application' => $application])
        ->set('name', 'RS PKU Muhammadiyah Surakarta')
        ->set('city', 'Surakarta')
        ->set('phone', '0271-714578')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('applications.profile', $application));

    $this->assertDatabaseHas('application_informations', [
        'application_id' => $application->id,
        'name' => 'RS PKU Muhammadiyah Surakarta',
        'city' => 'Surakarta',
    ]);
});

test('applicant can update profile and add members', function () {
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    Livewire::actingAs($applicant)
        ->test(Profile::class, ['application' => $application])
        ->set('vision', 'Visi KEPK Unggul')
        ->set('mission', 'Misi KEPK Terpercaya')
        ->call('saveProfile')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('application_profiles', [
        'application_id' => $application->id,
        'vision' => 'Visi KEPK Unggul',
    ]);

    Livewire::actingAs($applicant)
        ->test(Profile::class, ['application' => $application])
        ->set('new_member_name', 'Dr. dr. Budi, Sp.A')
        ->set('new_member_position', 'Ketua KEPK')
        ->set('new_member_email', 'budi@ums.ac.id')
        ->call('addMember')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('application_members', [
        'application_id' => $application->id,
        'name' => 'Dr. dr. Budi, Sp.A',
        'position' => 'Ketua KEPK',
    ]);
});

test('applicant can submit draft application', function () {
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $application->information()->create([
        'name' => 'Fakultas Farmasi UMS',
    ]);

    Livewire::actingAs($applicant)
        ->test(Show::class, ['application' => $application])
        ->call('submitApplication')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('applications', [
        'id' => $application->id,
        'status' => 'submitted',
    ]);
});

test('applicant cannot access other applicant application', function () {
    $applicant1 = User::factory()->applicant()->create();
    $applicant2 = User::factory()->applicant()->create();

    $application1 = Application::create([
        'user_id' => $applicant1->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $this->actingAs($applicant2)
        ->get(route('applications.show', $application1))
        ->assertForbidden();
});
