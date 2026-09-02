<?php

use App\Livewire\HasilAkreditasi\Index as HasilAkreditasiIndex;
use App\Livewire\Pengajuan\Create;
use App\Livewire\Pengajuan\Dokumen as DokumenLivewire;
use App\Livewire\Pengajuan\EvaluasiDiri;
use App\Livewire\Pengajuan\ListProtokol;
use App\Livewire\Penilaian\LembarPenilaian;
use App\Livewire\Penilaian\TugaskanPenilai;
use App\Models\BagianEvaluasi;
use App\Models\Institusi;
use App\Models\JawabanEvaluasi;
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

    foreach (['admin', 'ketua_kepk', 'asessor', 'anggota'] as $role) {
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
        'status' => 'in_progress',
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

    $ans = JawabanEvaluasi::where('surat_pengajuan_id', $surat->id)
        ->where('butir_evaluasi_id', $butirPertama->id)
        ->first();

    expect($ans)->not->toBeNull();
    expect($ans->catatan)->toBe('Struktur keanggotaan KEPK telah disahkan');
    expect($ans->hasAttachments())->toBeTrue();
    expect($ans->getAttachments()[0]['name'])->toBe('sk_rektor_kepk.pdf');
    expect($ans->kelengkapan_status)->toBe('belum_lengkap');

    // Upload berkas kedua (harus menambahkan, bukan menimpa)
    $fakeFile2 = UploadedFile::fake()->create('sop_telaah.pdf', 300, 'application/pdf');
    Livewire::actingAs($pemohon)
        ->test(EvaluasiDiri::class, ['suratPengajuan' => $surat])
        ->set("uploadedFiles.{$butirPertama->id}", $fakeFile2)
        ->call('uploadBerkas', $butirPertama->id)
        ->assertHasNoErrors();

    $ans->refresh();
    expect($ans->totalAttachmentCount())->toBe(2);
    expect($ans->kelengkapan_status)->toBe('lengkap');

    // Test hapus berkas
    Livewire::actingAs($pemohon)
        ->test(EvaluasiDiri::class, ['suratPengajuan' => $surat])
        ->call('hapusBerkas', $butirPertama->id)
        ->assertHasNoErrors();

    $ans->refresh();
    expect($ans->file_attachments)->toBeNull();
    expect($ans->hasAttachments())->toBeFalse();
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

    // Dokumen Evaluasi Diri per Bagian
    $butir1 = BagianEvaluasi::first()->butir()->first();
    $surat->jawabanEvaluasi()->create([
        'butir_evaluasi_id' => $butir1->id,
        'file_attachments' => [
            ['name' => 'sk_kepk.pdf', 'path' => 'evaluasi/sk_kepk.pdf', 'size' => 1024],
        ],
    ]);

    Livewire::actingAs($pemohon)
        ->test(DokumenLivewire::class, ['suratPengajuan' => $surat])
        ->assertSee('Arsip Dokumen Bukti Evaluasi Diri')
        ->assertSee('sk_kepk.pdf')
        ->assertHasNoErrors();
});

test('alur lengkap: penugasan penilai, review rekomendasi asesor secara real-time, dan persetujuan akhir admin', function () {
    $admin = User::factory()->admin()->create();
    $pemohon = User::factory()->applicant()->create();
    $penilai = User::factory()->reviewer()->create();

    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'in_progress',
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
    expect($surat->isEditable())->toBeTrue();
    expect($surat->status_label)->toBe('Proses Evaluasi');

    // 2. Penilai membuka lembar penilaian dan memberikan review real-time
    Livewire::actingAs($penilai)
        ->test(LembarPenilaian::class, ['suratPengajuan' => $surat])
        ->set('rekomendasi', 'revision_required')
        ->set('catatan', 'Lengkapi bukti SOP nomor 3.')
        ->call('simpanPenilaian')
        ->assertHasNoErrors();

    $surat->refresh();
    expect($surat->isEditable())->toBeTrue();

    // 3. Admin menetapkan keputusan akhir (Terakreditasi / Approved)
    Livewire::actingAs($admin)
        ->test(HasilAkreditasiIndex::class, ['suratPengajuan' => $surat])
        ->call('putuskanStatus', 'approved')
        ->assertHasNoErrors();

    $surat->refresh();
    expect($surat->status)->toBe('approved');
    expect($surat->status_label)->toBe('Terakreditasi');
    expect($surat->isApproved())->toBeTrue();
    expect($surat->isEditable())->toBeFalse();
});

test('pemohon dicegah mengakses halaman penilaian dan penugasan', function () {
    $pemohon = User::factory()->applicant()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'submitted',
    ]);

    $this->actingAs($pemohon)->get(route('penilaian.show', $surat))->assertForbidden();
    $this->actingAs($pemohon)->get(route('penilaian.tugaskan', $surat))->assertForbidden();
});

test('asessor dicegah membuat pengajuan dan menugaskan penilai', function () {
    $ketua = User::factory()->ketuaKepk()->create();
    $asessor = User::factory()->asessor()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $ketua->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $this->actingAs($asessor)->get(route('pengajuan.create'))->assertForbidden();
    $this->actingAs($asessor)->get(route('penilaian.tugaskan', $surat))->assertForbidden();
});

test('asessor yang ditugaskan dapat membuka lembar penilaian sedangkan yang belum ditugaskan ditolak', function () {
    $ketua = User::factory()->ketuaKepk()->create();
    $asessor = User::factory()->asessor()->create();
    $asessorLain = User::factory()->asessor()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $ketua->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    // Asesor yang belum ditugaskan melihat layar peringatan belum ditugaskan
    $this->actingAs($asessorLain)
        ->get(route('penilaian.show', $surat))
        ->assertSuccessful()
        ->assertSee('Akses Lembar Kerja Penilaian Belum Ditugaskan');

    // Asesor yang resmi ditugaskan berhasil mengakses lembar kerja
    $surat->penilai()->attach($asessor->id);
    $this->actingAs($asessor)
        ->get(route('penilaian.show', $surat))
        ->assertSuccessful()
        ->assertDontSee('Akses Lembar Kerja Penilaian Belum Ditugaskan');
});

test('role yang berhak dapat mengakses route masing-masing', function () {
    $admin = User::factory()->admin()->create();
    $ketua = User::factory()->ketuaKepk()->create();
    $asessor = User::factory()->asessor()->create();

    $surat = SuratPengajuan::create([
        'user_id' => $ketua->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $surat->penilai()->attach($asessor->id);

    $this->actingAs($asessor)->get(route('penilaian.show', $surat))->assertSuccessful();
    $this->actingAs($admin)->get(route('penilaian.tugaskan', $surat))->assertSuccessful();
    $this->actingAs($admin)->get(route('pengajuan.create'))->assertSuccessful();
    $this->actingAs($ketua)->get(route('pengajuan.create'))->assertSuccessful();
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

    // Beri nilai C pada salah satu butir
    $sampleItem = \App\Models\ButirEvaluasi::first();
    $surat->jawabanEvaluasi()->updateOrCreate(
        ['butir_evaluasi_id' => $sampleItem->id],
        ['skor' => 'C', 'catatan' => 'SOP belum disahkan']
    );

    // Karena ada C, maka tidak lagi Tipe A (turun ke Tipe B)
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

test('anggota kepk dapat membuka dan mengisi evaluasi diri serta list protokol', function () {
    $ketua = User::factory()->ketuaKepk()->create();
    $anggota = User::factory()->anggota()->create();

    $surat = SuratPengajuan::create([
        'user_id' => $ketua->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $butirPertama = BagianEvaluasi::first()->butir()->first();

    // Anggota KEPK dapat membuka dan mengisi evaluasi diri
    Livewire::actingAs($anggota)
        ->test(EvaluasiDiri::class, ['suratPengajuan' => $surat])
        ->set("catatan.{$butirPertama->id}", 'Diisi oleh Anggota KEPK')
        ->assertHasNoErrors();

    // Anggota KEPK dapat membuka dan menambah list protokol
    Livewire::actingAs($anggota)
        ->test(ListProtokol::class, ['suratPengajuan' => $surat])
        ->set('nomor_protokol', 'PROT-ANGGOTA-001')
        ->set('judul', 'Penelitian Klinis Vaksin Baru')
        ->set('peneliti_utama', 'Dr. Siti, Sp.A')
        ->call('simpan')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('list_protokol', [
        'surat_pengajuan_id' => $surat->id,
        'nomor_protokol' => 'PROT-ANGGOTA-001',
    ]);
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

test('status kelengkapan butir evaluasi diri: 1 berkas belum lengkap dan 2 berkas lengkap', function () {
    $ketua = User::factory()->ketuaKepk()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $ketua->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    $butir1 = BagianEvaluasi::first()->butir()->first();
    $butir2 = BagianEvaluasi::first()->butir()->skip(1)->first();

    $evaluasiService = app(\App\Services\EvaluasiDiriService::class);

    // 1 berkas -> belum lengkap
    $ans1 = $evaluasiService->saveAnswer($surat, $butir1->id, [
        'file_attachments' => [
            ['name' => 'dokumen1.pdf', 'path' => 'evaluasi/dokumen1.pdf', 'size' => 1024],
        ],
    ]);

    // 2 berkas -> lengkap
    $ans2 = $evaluasiService->saveAnswer($surat, $butir2->id, [
        'file_attachments' => [
            ['name' => 'dokumen1.pdf', 'path' => 'evaluasi/dokumen1.pdf', 'size' => 1024],
            ['name' => 'dokumen2.pdf', 'path' => 'evaluasi/dokumen2.pdf', 'size' => 2048],
        ],
    ]);

    expect($ans1->kelengkapan_status)->toBe('belum_lengkap');
    expect($ans1->kelengkapan_label)->toBe('Belum Lengkap');

    expect($ans2->kelengkapan_status)->toBe('lengkap');
    expect($ans2->kelengkapan_label)->toBe('Lengkap');

    $progress = $evaluasiService->calculateProgress($surat);
    $bagianA = $progress[BagianEvaluasi::first()->kode];
    expect($bagianA['terjawab'])->toBe(1);
    expect($bagianA['belum_lengkap'])->toBe(1);
});

test('role anggota dapat mengakses evaluasi diri dan hasil akreditasi', function () {
    $ketua = User::factory()->ketuaKepk()->create();
    $anggota = User::factory()->anggota()->create();
    $surat = SuratPengajuan::create([
        'user_id' => $ketua->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'draft',
    ]);

    // Anggota dapat mengakses halaman detail pengajuan (hasil)
    $this->actingAs($anggota)
        ->get(route('pengajuan.show', $surat))
        ->assertOk()
        ->assertSee('Evaluasi Diri (164 Butir)')
        ->assertSee('Hasil Penilaian & Prediksi Akreditasi');

    // Anggota dapat mengakses halaman evaluasi diri
    $this->actingAs($anggota)
        ->get(route('pengajuan.evaluasi-diri', $surat))
        ->assertOk();
});
