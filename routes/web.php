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
use App\Livewire\Admin\KriteriaEvaluasi as AdminKriteriaEvaluasi;
use Illuminate\Support\Facades\Route;

// Halaman utama (Root) langsung mengarah ke Login untuk tamu, atau Dashboard untuk yang sudah masuk
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// 1. Modul Pembuatan Pengajuan Baru (Ketua KEPK, Anggota & Admin)
Route::middleware(['auth', 'role:ketua_kepk|anggota|admin'])->group(function () {
    Route::get('/pengajuan/create', PengajuanCreate::class)->name('pengajuan.create');
});

// 2. Rute Umum Terotentikasi (Shared: Ketua KEPK, Anggota, Asessor, Admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/pengajuan', PengajuanIndex::class)->name('pengajuan.index');
    Route::get('/pengajuan/{suratPengajuan}', PengajuanShow::class)->name('pengajuan.show');
});

// 3. Modul Pengisian Berkas (Ketua KEPK, Anggota, Admin)
Route::middleware(['auth', 'role:ketua_kepk|anggota|admin'])->group(function () {
    Route::get('/pengajuan/{suratPengajuan}/formulir-aplikasi', PengajuanFormulirAplikasi::class)->name('pengajuan.formulir-aplikasi');
    Route::get('/pengajuan/{suratPengajuan}/profil', PengajuanProfil::class)->name('pengajuan.profil');
    Route::get('/pengajuan/{suratPengajuan}/evaluasi-diri', PengajuanEvaluasiDiri::class)->name('pengajuan.evaluasi-diri');
    Route::get('/pengajuan/{suratPengajuan}/list-protokol', PengajuanListProtokol::class)->name('pengajuan.list-protokol');
    Route::get('/pengajuan/{suratPengajuan}/dokumen', PengajuanDokumen::class)->name('pengajuan.dokumen');
});

// 4. Modul Penilaian Etik (Asessor & Admin)
Route::middleware(['auth', 'role:asessor|admin'])->group(function () {
    Route::get('/penilaian', PenilaianIndex::class)->name('penilaian.index');
    Route::get('/penilaian/{suratPengajuan}', PenilaianWorkbench::class)->name('penilaian.show');
});

// 5. Modul Ekspor Laporan & Berkas PDF (ReportService)
Route::middleware(['auth'])->group(function () {
    Route::get('/pengajuan/{suratPengajuan}/pdf/hasil-akreditasi', [\App\Http\Controllers\ReportController::class, 'hasilAkreditasi'])->name('pengajuan.pdf.hasil-akreditasi');
    Route::get('/pengajuan/{suratPengajuan}/pdf/evaluasi-diri', [\App\Http\Controllers\ReportController::class, 'evaluasiDiri'])->name('pengajuan.pdf.evaluasi-diri');
    Route::get('/pengajuan/{suratPengajuan}/pdf/matriks-gap', [\App\Http\Controllers\ReportController::class, 'matriksGap'])->name('pengajuan.pdf.matriks-gap');
});

// 6. Modul Khusus Administrator (Admin Only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/pengajuan/{suratPengajuan}/tugaskan-penilai', PenilaianTugaskan::class)->name('penilaian.tugaskan');
    Route::get('/admin/kriteria', AdminKriteriaEvaluasi::class)->name('admin.kriteria.index');
    Route::get('/admin/users', \App\Livewire\Admin\ManajemenAkun::class)->name('admin.users.index');
});

require __DIR__.'/auth.php';

