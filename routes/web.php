<?php

use App\Livewire\Applications\Create as ApplicationCreate;
use App\Livewire\Applications\Documents as ApplicationDocuments;
use App\Livewire\Applications\Index as ApplicationIndex;
use App\Livewire\Applications\Information as ApplicationInformation;
use App\Livewire\Applications\Profile as ApplicationProfile;
use App\Livewire\Applications\ResearchProtocols as ApplicationResearchProtocols;
use App\Livewire\Applications\SelfAssessment as ApplicationSelfAssessment;
use App\Livewire\Applications\Show as ApplicationShow;
use App\Livewire\Reviews\AssignReviewers as AssignReviewers;
use App\Livewire\Reviews\Index as ReviewIndex;
use App\Livewire\Reviews\Review as ReviewWorkbench;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Application Management Routes (Applicant / Admin / Reviewer)
Route::middleware(['auth'])->group(function () {
    Route::get('/applications', ApplicationIndex::class)->name('applications.index');
    Route::get('/applications/create', ApplicationCreate::class)->name('applications.create');
    Route::get('/applications/{application}', ApplicationShow::class)->name('applications.show');
    Route::get('/applications/{application}/information', ApplicationInformation::class)->name('applications.information');
    Route::get('/applications/{application}/profile', ApplicationProfile::class)->name('applications.profile');
    Route::get('/applications/{application}/self-assessment', ApplicationSelfAssessment::class)->name('applications.self-assessment');
    Route::get('/applications/{application}/protocols', ApplicationResearchProtocols::class)->name('applications.protocols');
    Route::get('/applications/{application}/documents', ApplicationDocuments::class)->name('applications.documents');

    // Phase 3: Reviewer & Assignment Routes
    Route::get('/reviews', ReviewIndex::class)->name('reviews.index');
    Route::get('/reviews/{application}', ReviewWorkbench::class)->name('reviews.show');
    Route::get('/applications/{application}/assign-reviewers', AssignReviewers::class)->name('reviews.assign');
});

require __DIR__.'/auth.php';
