<?php

use App\Livewire\Applications\Documents;
use App\Models\Application;
use App\Models\Document;
use App\Models\Institution;
use App\Models\Kepk;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Storage::fake('public');

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

test('applicant can upload, download, and delete document', function () {
    $applicant = User::factory()->applicant()->create();

    $application = Application::create([
        'user_id' => $applicant->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $fakeFile = UploadedFile::fake()->create('protokol-penelitian.pdf', 1024, 'application/pdf');

    // Upload
    Livewire::actingAs($applicant)
        ->test(Documents::class, ['application' => $application])
        ->set('file', $fakeFile)
        ->call('upload')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('documents', [
        'application_id' => $application->id,
        'uploaded_by' => $applicant->id,
        'original_name' => 'protokol-penelitian.pdf',
    ]);

    $document = Document::where('application_id', $application->id)->first();
    Storage::disk('public')->assertExists($document->path);

    // Download
    Livewire::actingAs($applicant)
        ->test(Documents::class, ['application' => $application])
        ->call('download', $document->id)
        ->assertFileDownloaded('protokol-penelitian.pdf');

    // Delete
    Livewire::actingAs($applicant)
        ->test(Documents::class, ['application' => $application])
        ->call('delete', $document->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('documents', [
        'id' => $document->id,
    ]);

    Storage::disk('public')->assertMissing($document->path);
});
