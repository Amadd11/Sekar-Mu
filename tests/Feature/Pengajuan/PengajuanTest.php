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

    $this->institusi = Institusi::firstOrCreate(
        ['name' => 'Universitas Muhammadiyah Yogyakarta'],
        ['city' => 'Yogyakarta']
    );

    $this->kepk = Kepk::firstOrCreate(
        ['code' => 'KEPK-UMY-001'],
        [
            'institusi_id' => $this->institusi->id,
            'name' => 'KEPK UMY',
            'status' => 'active',
        ]
    );

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

test('pemohon dapat mengisi kelengkapan bukti, catatan, dan mengunggah berkas per butir', function () {
    Storage::fake('public');

    $pemohon = User::factory()->applicant()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $butirPertama = BagianEvaluasi::first()->butir()->first();
    $fakeFile = UploadedFile::fake()->create('sk_rektor_kepk.pdf', 500, 'application/pdf');

    Livewire::actingAs($pemohon)
        ->test(EvaluasiDiri::class, ['suratPengajuan' => $surat])
        ->set("bukti.{$butirPertama->id}", 'SK Rektor No. 12/2025')
        ->set("catatan.{$butirPertama->id}", 'Struktur keanggotaan KEPK telah disahkan')
        ->set("uploadedFiles.{$butirPertama->id}", $fakeFile)
        ->call('uploadBerkas', $butirPertama->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('jawaban_evaluasi', [
        'surat_pengajuan_id' => $surat->id,
        'butir_evaluasi_id' => $butirPertama->id,
        'file_name' => 'sk_rektor_kepk.pdf',
        'catatan' => 'Struktur keanggotaan KEPK telah disahkan',
    ]);

    // Test hapus berkas
    Livewire::actingAs($pemohon)
        ->test(EvaluasiDiri::class, ['suratPengajuan' => $surat])
        ->call('hapusBerkas', $butirPertama->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('jawaban_evaluasi', [
        'surat_pengajuan_id' => $surat->id,
        'butir_evaluasi_id' => $butirPertama->id,
        'file_path' => null,
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

test('pemohon dicegah mengakses halaman penilaian dan penugasan', function () {
    $pemohon = User::factory()->applicant()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    $this->actingAs($pemohon)->get(route('penilaian.index'))->assertForbidden();
    $this->actingAs($pemohon)->get(route('penilaian.tugaskan', $surat))->assertForbidden();
});

test('reviewer dicegah membuat pengajuan dan menugaskan penilai', function () {
    $pemohon = User::factory()->applicant()->create();
    $reviewer = User::factory()->reviewer()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    $this->actingAs($reviewer)->get(route('pengajuan.create'))->assertForbidden();
    $this->actingAs($reviewer)->get(route('penilaian.tugaskan', $surat))->assertForbidden();
});

test('reviewer tidak ditugaskan dicegah menilai berkas', function () {
    $pemohon = User::factory()->applicant()->create();
    $unassignedReviewer = User::factory()->reviewer()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    $this->actingAs($unassignedReviewer)->get(route('penilaian.show', $surat))->assertForbidden();
});

test('role yang berhak dapat mengakses route masing-masing', function () {
    $admin = User::factory()->admin()->create();
    $pemohon = User::factory()->applicant()->create();
    $reviewer = User::factory()->reviewer()->create();

    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);
    $surat->penilai()->attach($reviewer->id, ['ditugaskan_oleh' => $admin->id, 'tanggal_penugasan' => now()]);

    $this->actingAs($reviewer)->get(route('penilaian.show', $surat))->assertSuccessful();
    $this->actingAs($admin)->get(route('penilaian.tugaskan', $surat))->assertSuccessful();
    $this->actingAs($admin)->get(route('pengajuan.create'))->assertSuccessful();
    $this->actingAs($pemohon)->get(route('pengajuan.create'))->assertSuccessful();
});

test('compliance service menghitung skor 164 butir, klasifikasi akreditasi, dan critical findings', function () {
    $pemohon = User::factory()->applicant()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $allButir = \App\Models\ButirEvaluasi::all();
    expect($allButir->count())->toBe(164);

    $complianceService = app(\App\Services\ComplianceService::class);

    // Initial empty metrics
    $initialMetrics = $complianceService->calculateComplianceMetrics($surat);
    expect($initialMetrics['overall_compliance'])->toBe(0);
    expect($initialMetrics['prediction']['type'])->toBe('Belum Memenuhi Syarat');

    // Beri nilai A pada 140 butir (140/164 ~ 85%)
    foreach ($allButir->take(140) as $butir) {
        $surat->jawabanEvaluasi()->create([
            'butir_evaluasi_id' => $butir->id,
            'skor' => 'A',
        ]);
    }

    $metrics = $complianceService->calculateComplianceMetrics($surat);
    expect($metrics['overall_compliance'])->toBeGreaterThanOrEqual(80);
    expect($metrics['prediction']['type'])->toBe('Tipe A');

    // Beri nilai C pada butir kritis
    $criticalItem = \App\Models\ButirEvaluasi::where('is_critical', true)->first();
    $surat->jawabanEvaluasi()->updateOrCreate(
        ['butir_evaluasi_id' => $criticalItem->id],
        ['skor' => 'C', 'catatan' => 'SOP belum disahkan']
    );

    $findings = $complianceService->getCriticalFindings($surat);
    expect($findings)->not->toBeEmpty();
    expect($findings[0]['butir_id'])->toBe($criticalItem->id);

    // Karena ada C, maka tidak lagi Tipe A
    $metricsAfterC = $complianceService->calculateComplianceMetrics($surat);
    expect($metricsAfterC['prediction']['type'])->toBe('Tipe B');
});

test('asesor dapat menilai independen dan menghasilkan matriks komparasi gap', function () {
    $pemohon = User::factory()->applicant()->create();
    $penilai = User::factory()->reviewer()->create();
    $admin = User::factory()->admin()->create();

    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);
    $surat->penilai()->attach($penilai->id, ['ditugaskan_oleh' => $admin->id, 'tanggal_penugasan' => now()]);

    $butir1 = \App\Models\ButirEvaluasi::first();
    $butir2 = \App\Models\ButirEvaluasi::skip(1)->first();

    // Pemohon mengisi Self-Assessment (butir1 = A, butir2 = A)
    $surat->jawabanEvaluasi()->create(['butir_evaluasi_id' => $butir1->id, 'skor' => 'A']);
    $surat->jawabanEvaluasi()->create(['butir_evaluasi_id' => $butir2->id, 'skor' => 'A']);

    // Asesor menilai independen (butir1 = A (match), butir2 = B (gap))
    $penilaianService = app(\App\Services\PenilaianService::class);
    $penilaianService->saveItemAssessment($surat, $penilai, $butir1->id, ['skor' => 'A']);
    $penilaianService->saveItemAssessment($surat, $penilai, $butir2->id, ['skor' => 'B', 'temuan' => 'Bukti implementasi belum lengkap']);

    $matrix = $penilaianService->getComparisonMatrix($surat, $penilai->id);
    expect($matrix['total_matches'])->toBeGreaterThanOrEqual(1);
    expect($matrix['total_gaps'])->toBeGreaterThanOrEqual(1);
});

test('corrective action service dapat membuat dan memperbarui status siklus tindakan perbaikan', function () {
    $pemohon = User::factory()->applicant()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    $service = app(\App\Services\CorrectiveActionService::class);
    $action = $service->createAction($surat, [
        'finding' => 'SK Susunan Keanggotaan KEPK belum diperbarui',
        'risk' => 'Legalitas telaah protokol berpotensi tidak sah',
        'action' => 'Penerbitan SK Rektor terbaru untuk kepengurusan KEPK',
        'pic_name' => 'Dr. Budi',
        'priority' => 'HIGH',
        'deadline' => now()->addDays(14)->toDateString(),
    ]);

    expect($action->status)->toBe('OPEN');
    expect($action->priority)->toBe('HIGH');

    $updated = $service->updateStatus($action, 'IN_PROGRESS', 'Sedang proses tanda tangan Rektor.');
    expect($updated->status)->toBe('IN_PROGRESS');
    expect($updated->verification_notes)->toBe('Sedang proses tanda tangan Rektor.');
});
