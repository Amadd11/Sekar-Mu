<?php

use App\Livewire\Admin\KriteriaEvaluasi;
use App\Models\BagianEvaluasi;
use App\Models\ButirEvaluasi;
use App\Models\KelompokEvaluasi;
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

    $this->seed(InstrumenEvaluasiSeeder::class);
});

test('non-admin ditolak saat mengakses master kriteria evaluasi', function () {
    $ketua = User::factory()->ketuaKepk()->create();

    $this->actingAs($ketua)
        ->get(route('admin.kriteria.index'))
        ->assertForbidden();
});

test('admin dapat membuka modal kelola kelompok dan menambah kelompok baru dengan urutan otomatis', function () {
    $admin = User::factory()->admin()->create();
    $bagianA = BagianEvaluasi::where('kode', 'A')->first();
    $currentMaxUrutan = KelompokEvaluasi::where('bagian_evaluasi_id', $bagianA->id)->max('urutan') ?? 0;

    Livewire::actingAs($admin)
        ->test(KriteriaEvaluasi::class)
        ->call('bukaModalKelompok')
        ->assertSet('showKelompokModal', true)
        ->assertSet('kelompok_bagian_id', $bagianA->id)
        ->assertSet('kelompok_urutan', $currentMaxUrutan + 1)
        ->set('kelompok_nama', 'Kelompok Standar Uji Coba')
        ->call('simpanKelompok')
        ->assertHasNoErrors()
        ->assertSee('Kelompok acuan standar baru berhasil ditambahkan');

    $this->assertDatabaseHas('kelompok_evaluasi', [
        'bagian_evaluasi_id' => $bagianA->id,
        'nama' => 'Kelompok Standar Uji Coba',
        'urutan' => $currentMaxUrutan + 1,
    ]);
});

test('admin dapat menambah butir kriteria evaluasi baru', function () {
    $admin = User::factory()->admin()->create();
    $kelompok = KelompokEvaluasi::first();

    Livewire::actingAs($admin)
        ->test(KriteriaEvaluasi::class)
        ->call('bukaModalCreate')
        ->assertSet('showModal', true)
        ->set('kelompok_evaluasi_id', $kelompok->id)
        ->set('kode', 'Z9.9')
        ->set('pertanyaan', 'Apakah KEPK memiliki standar operasional khusus riset darurat?')
        ->set('standar', 'Standar WHO-CIOMS')
        ->call('simpanKriteria')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $this->assertDatabaseHas('butir_evaluasi', [
        'kode' => 'Z9.9',
        'kelompok_evaluasi_id' => $kelompok->id,
    ]);
});
