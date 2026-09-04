<?php

use App\Livewire\Penilaian\LembarPenilaian;
use App\Models\BagianEvaluasi;
use App\Models\Institusi;
use App\Models\Kepk;
use App\Models\SuratPengajuan;
use App\Models\User;
use Database\Seeders\InstrumenEvaluasiSeeder;
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

test('asesor dapat melihat berkas bukti evaluasi diri terkelompokkan dengan benar per bagian standar', function () {
    $admin = User::factory()->admin()->create();
    $asessor = User::factory()->asessor()->create();
    $pemohon = User::factory()->applicant()->create();

    $surat = SuratPengajuan::create([
        'user_id' => $pemohon->id,
        'kepk_id' => $this->kepk->id,
        'status' => 'in_progress',
    ]);
    $surat->penilai()->attach($asessor->id, ['ditugaskan_oleh' => $admin->id, 'tanggal_penugasan' => now()]);

    $bagianA = BagianEvaluasi::where('kode', 'A')->first();
    $butirA = $bagianA->kelompok()->first()->butir()->first();

    // Buat jawaban dengan lampiran berkas pada butir bagian A
    $surat->jawabanEvaluasi()->create([
        'butir_evaluasi_id' => $butirA->id,
        'bukti' => 'SK KEPK No. 123/2026',
        'file_attachments' => [
            ['name' => 'sk_struktur_kepk.pdf', 'path' => 'evaluasi/sk_struktur_kepk.pdf', 'size' => 2048],
            ['name' => 'daftar_riwayat_hidup.pdf', 'path' => 'evaluasi/daftar_riwayat_hidup.pdf', 'size' => 4096],
        ],
    ]);

    // Asesor membuka Tab Dokumen pada Lembar Penilaian
    Livewire::actingAs($asessor)
        ->test(LembarPenilaian::class, ['suratPengajuan' => $surat])
        ->set('activeTab', 'dokumen')
        ->assertSee('sk_struktur_kepk.pdf')
        ->assertSee('daftar_riwayat_hidup.pdf')
        ->assertSee('SK KEPK No. 123/2026')
        ->assertSee('2 Berkas')
        ->assertDontSee('Daftar Protokol Penelitian KEPK')
        ->assertHasNoErrors();
});
