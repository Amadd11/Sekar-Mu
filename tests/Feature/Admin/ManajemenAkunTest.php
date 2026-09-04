<?php

use App\Livewire\Admin\ManajemenAkun;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    foreach (['admin', 'ketua_kepk', 'asessor', 'anggota'] as $role) {
        Role::firstOrCreate(['name' => $role]);
    }
});

test('non-admin ditolak saat mengakses manajemen akun', function () {
    $asessor = User::factory()->asessor()->create();

    $this->actingAs($asessor)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('admin dapat membuat akun pengguna baru', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(ManajemenAkun::class)
        ->call('bukaModalCreate')
        ->set('name', 'Asesor Dr. Hendra')
        ->set('email', 'hendra@asesor.id')
        ->set('role', 'asessor')
        ->set('password', 'Password123#')
        ->set('password_confirmation', 'Password123#')
        ->call('simpanUser')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $this->assertDatabaseHas('users', [
        'email' => 'hendra@asesor.id',
    ]);

    $created = User::where('email', 'hendra@asesor.id')->first();
    expect($created->hasRole('asessor'))->toBeTrue();
});

test('admin dapat mengedit nama dan peran tanpa mengubah password', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create([
        'name' => 'Nama Awal',
        'email' => 'user.awal@sekarmu.id',
        'password' => Hash::make('OldPassword123#'),
    ]);
    $user->assignRole('anggota');

    Livewire::actingAs($admin)
        ->test(ManajemenAkun::class)
        ->call('bukaModalEdit', $user->id)
        ->set('name', 'Nama Baru')
        ->set('role', 'ketua_kepk')
        ->call('simpanUser')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $user->refresh();
    expect($user->name)->toBe('Nama Baru');
    expect($user->hasRole('ketua_kepk'))->toBeTrue();
    expect(Hash::check('OldPassword123#', $user->password))->toBeTrue();
});

test('admin dapat memperbarui password user dengan konfirmasi yang cocok', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create([
        'password' => Hash::make('OldPassword123#'),
    ]);
    $user->assignRole('asessor');

    Livewire::actingAs($admin)
        ->test(ManajemenAkun::class)
        ->call('bukaModalEdit', $user->id)
        ->set('password', 'NewSecret2026#')
        ->set('password_confirmation', 'NewSecret2026#')
        ->call('simpanUser')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $user->refresh();
    expect(Hash::check('NewSecret2026#', $user->password))->toBeTrue();
});
