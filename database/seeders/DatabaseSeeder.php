<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        foreach (UserRole::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }

        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@sekarmu.test'],
            [
                'name' => 'Admin Sekar-Mu',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles([UserRole::ADMIN->value]);

        // Applicant user
        $applicant = User::updateOrCreate(
            ['email' => 'applicant@sekarmu.test'],
            [
                'name' => 'Pemohon Penelitian',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $applicant->syncRoles([UserRole::APPLICANT->value]);

        // Reviewer user
        $reviewer = User::updateOrCreate(
            ['email' => 'reviewer@sekarmu.test'],
            [
                'name' => 'Penelaah Etik (Reviewer)',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $reviewer->syncRoles([UserRole::REVIEWER->value]);
    }
}
