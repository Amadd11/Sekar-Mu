<?php

use App\Livewire\Applications\Create as ApplicationCreate;
use App\Livewire\Applications\Index as ApplicationIndex;
use App\Livewire\Applications\Information as ApplicationInformation;
use App\Livewire\Applications\Profile as ApplicationProfile;
use App\Livewire\Applications\Show as ApplicationShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Application Management Routes (Applicant / Admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/applications', ApplicationIndex::class)->name('applications.index');
    Route::get('/applications/create', ApplicationCreate::class)->name('applications.create');
    Route::get('/applications/{application}', ApplicationShow::class)->name('applications.show');
    Route::get('/applications/{application}/information', ApplicationInformation::class)->name('applications.information');
    Route::get('/applications/{application}/profile', ApplicationProfile::class)->name('applications.profile');
});

require __DIR__.'/auth.php';
