<?php

use App\Livewire\Pengajuan\Create;
use App\Livewire\Pengajuan\Dokumen as DokumenLivewire;
use App\Livewire\Pengajuan\EvaluasiDiri;
use App\Livewire\Pengajuan\FormulirAplikasi;
use App\Livewire\Pengajuan\Index;
use App\Livewire\Pengajuan\ListProtokol;
use App\Livewire\Pengajuan\Show;
use App\Livewire\Penilaian\Index as PenilaianIndex;
use App\Livewire\Penilaian\LembarPenilaian;
use App\Livewire\Penilaian\TugaskanPenilai;
use App\Models\BagianEvaluasi;
use App\Models\Institusi;
use App\Models\Kepk;
use App\Models\SuratPengajuan;
use App\Models\User;
use App\Services\EvaluasiDiriService;
use App\Services\PengajuanService;
use App\Services\PenilaianService;
use Database\Seeders\InstrumenEvaluasiSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    foreach (['admin', 'applicant', 'reviewer'] as $role) {
        Role::firstOrCreate(['name' => $role]);
    }

    $this->institusi = Institusi::create([
        'name' => 'Universitas Muhammadiyah Yogyakarta',
        'city' => 'Yogyakarta',
    ]);

    $this->kepk = Kepk::create([
        'institusi_id' => $this->institusi->id,
        'name' => 'KEPK UMY',
        'code' => 'KEPK-UMY-001',
        'status' => 'active',
    ]);

    $this->seed(InstrumenEvaluasiSeeder::class);
});

test('pemohon dapat membuat surat pengajuan baru', function () {
    $pemohon = User::factory()->applicant()->create();

    Livewire::actingAs($pemohon)
        ->test(Create::class)
        ->set('kepk_id', $this->kepk->id)
        ->set('nama_institusi', 'Fakultas Kedokteran UMY')
        ->set('kota', 'Yogyakarta')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('surat_pengajuan', [
        'user_id' => $pemohon->id,
        'status' => 'draft',
    ]);

    $this->assertDatabaseHas('formulir_aplikasi', [
        'nama_institusi' => 'Fakultas Kedokteran UMY',
    ]);
});

test('pemohon dapat mengisi evaluasi diri dan rekap skor terhitung', function () {
    $pemohon = User::factory()->applicant()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $butirPertama = BagianEvaluasi::first()->butir()->first();

    Livewire::actingAs($pemohon)
        ->test(EvaluasiDiri::class, ['suratPengajuan' => $surat])
        ->call('setSkor', $butirPertama->id, 'A')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('jawaban_evaluasi', [
        'surat_pengajuan_id' => $surat->id,
        'butir_evaluasi_id' => $butirPertama->id,
        'skor' => 'A',
    ]);
});

test('pemohon dapat mengelola list protokol dan dokumen lampiran', function () {
    Storage::fake('public');

    $pemohon = User::factory()->applicant()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    // List Protokol
    Livewire::actingAs($pemohon)
        ->test(ListProtokol::class, ['suratPengajuan' => $surat])
        ->set('nomor_protokol', 'PR-001')
        ->set('judul', 'Uji Efektivitas Ekstrak Daun Kelor')
        ->set('peneliti_utama', 'Dr. Ahmad')
        ->call('simpan')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('list_protokol', [
        'surat_pengajuan_id' => $surat->id,
        'nomor_protokol' => 'PR-001',
    ]);

    // Dokumen Lampiran
    $file = UploadedFile::fake()->create('sk_kepk.pdf', 500, 'application/pdf');

    Livewire::actingAs($pemohon)
        ->test(DokumenLivewire::class, ['suratPengajuan' => $surat])
        ->set('file', $file)
        ->call('unggah')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('dokumen', [
        'surat_pengajuan_id' => $surat->id,
        'nama_asli' => 'sk_kepk.pdf',
    ]);
});

test('alur lengkap: penugasan penilai, review rekomendasi, perbaikan, dan persetujuan akhir admin', function () {
    $admin = User::factory()->admin()->create();
    $pemohon = User::factory()->applicant()->create();
    $penilai = User::factory()->reviewer()->create();

    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    $surat->formulirAplikasi()->create([
        'nama_institusi' => 'Fakultas Farmasi UMY',
    ]);

    // 1. Admin menugaskan penilai
    Livewire::actingAs($admin)
        ->test(TugaskanPenilai::class, ['suratPengajuan' => $surat])
        ->set('selectedReviewerIds', [$penilai->id])
        ->call('save')
        ->assertHasNoErrors();

    $surat->refresh();
    expect($surat->status)->toBe('under_review');

    // 2. Penilai membuka lembar penilaian dan meminta perbaikan
    Livewire::actingAs($penilai)
        ->test(LembarPenilaian::class, ['suratPengajuan' => $surat])
        ->set('rekomendasi', 'revision_required')
        ->set('catatan', 'Lengkapi bukti SOP nomor 3.')
        ->call('simpanPenilaian')
        ->assertHasNoErrors();

    $surat->refresh();
    expect($surat->status)->toBe('revision_required');

    // 3. Pemohon mengajukan ulang (resubmit)
    Livewire::actingAs($pemohon)
        ->test(Show::class, ['suratPengajuan' => $surat])
        ->call('ajukanBerkas')
        ->assertHasNoErrors();

    $surat->refresh();
    expect($surat->status)->toBe('resubmitted');

    // 4. Admin menyetujui (Approved)
    Livewire::actingAs($admin)
        ->test(Show::class, ['suratPengajuan' => $surat])
        ->call('putuskanStatus', 'approved')
        ->assertHasNoErrors();

    $surat->refresh();
    expect($surat->status)->toBe('approved');
});
