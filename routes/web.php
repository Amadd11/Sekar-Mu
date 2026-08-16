<?php

use App\Livewire\Pengajuan\Create as PengajuanCreate;
use App\Livewire\Pengajuan\Dokumen as PengajuanDokumen;
use App\Livewire\Pengajuan\EvaluasiDiri as PengajuanEvaluasiDiri;
use App\Livewire\Pengajuan\FormulirAplikasi as PengajuanFormulirAplikasi;
use App\Livewire\Pengajuan\Index as PengajuanIndex;
use App\Livewire\Pengajuan\ListProtokol as PengajuanListProtokol;
use App\Livewire\Pengajuan\Profil as PengajuanProfil;
use App\Livewire\Pengajuan\Show as PengajuanShow;
use App\Livewire\Penilaian\Index as PenilaianIndex;
use App\Livewire\Penilaian\LembarPenilaian as PenilaianWorkbench;
use App\Livewire\Penilaian\TugaskanPenilai as PenilaianTugaskan;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Modul Surat Pengajuan & Berkas KEPK (Applicant / Admin / Reviewer)
Route::middleware(['auth'])->group(function () {
    Route::get('/pengajuan', PengajuanIndex::class)->name('pengajuan.index');
    Route::get('/pengajuan/create', PengajuanCreate::class)->name('pengajuan.create');
    Route::get('/pengajuan/{suratPengajuan}', PengajuanShow::class)->name('pengajuan.show');
    Route::get('/pengajuan/{suratPengajuan}/formulir-aplikasi', PengajuanFormulirAplikasi::class)->name('pengajuan.formulir-aplikasi');
    Route::get('/pengajuan/{suratPengajuan}/profil', PengajuanProfil::class)->name('pengajuan.profil');
    Route::get('/pengajuan/{suratPengajuan}/evaluasi-diri', PengajuanEvaluasiDiri::class)->name('pengajuan.evaluasi-diri');
    Route::get('/pengajuan/{suratPengajuan}/list-protokol', PengajuanListProtokol::class)->name('pengajuan.list-protokol');
    Route::get('/pengajuan/{suratPengajuan}/dokumen', PengajuanDokumen::class)->name('pengajuan.dokumen');

    // Modul Penilaian Etik (Reviewer & Admin)
    Route::get('/penilaian', PenilaianIndex::class)->name('penilaian.index');
    Route::get('/penilaian/{suratPengajuan}', PenilaianWorkbench::class)->name('penilaian.show');
    Route::get('/pengajuan/{suratPengajuan}/tugaskan-penilai', PenilaianTugaskan::class)->name('penilaian.tugaskan');
});

require __DIR__.'/auth.php';
