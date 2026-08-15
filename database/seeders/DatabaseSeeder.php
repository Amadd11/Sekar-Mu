<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationInformation;
use App\Models\ApplicationMember;
use App\Models\ApplicationProfile;
use App\Models\Institution;
use App\Models\Kepk;
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

        // 1. Create Roles
        foreach (User::ROLES as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2. Create Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@sekarmu.test'],
            [
                'name' => 'Admin Sekar-Mu',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles([User::ROLE_ADMIN]);

        $applicant = User::updateOrCreate(
            ['email' => 'applicant@sekarmu.test'],
            [
                'name' => 'Pemohon Penelitian',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $applicant->syncRoles([User::ROLE_APPLICANT]);

        $reviewer = User::updateOrCreate(
            ['email' => 'reviewer@sekarmu.test'],
            [
                'name' => 'Penelaah Etik (Reviewer)',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $reviewer->syncRoles([User::ROLE_REVIEWER]);

        // 3. Create Sample Institutions & KEPKs
        $institution1 = Institution::firstOrCreate(
            ['name' => 'Universitas Muhammadiyah Surakarta'],
            [
                'address' => 'Jl. A. Yani, Pabelan, Kartasura, Sukoharjo',
                'city' => 'Surakarta',
                'phone' => '0271-717417',
                'email' => 'humas@ums.ac.id',
            ]
        );

        $institution2 = Institution::firstOrCreate(
            ['name' => 'RS PKU Muhammadiyah Surakarta'],
            [
                'address' => 'Jl. Ronggowarsito No. 130, Timuran, Banjarsari',
                'city' => 'Surakarta',
                'phone' => '0271-714578',
                'email' => 'info@rspkusolo.com',
            ]
        );

        $kepk1 = Kepk::firstOrCreate(
            ['code' => 'KEPK-UMS-01'],
            [
                'institution_id' => $institution1->id,
                'name' => 'Komite Etik Penelitian Kesehatan FK UMS',
                'status' => 'active',
            ]
        );

        $kepk2 = Kepk::firstOrCreate(
            ['code' => 'KEPK-RS-PKU-01'],
            [
                'institution_id' => $institution2->id,
                'name' => 'Komite Etik Penelitian Kesehatan RS PKU Solo',
                'status' => 'active',
            ]
        );

        // 4. Create Initial Sample Application for Applicant
        $sampleApp = Application::firstOrCreate(
            [
                'user_id' => $applicant->id,
                'kepk_id' => $kepk1->id,
            ],
            [
                'status' => Application::STATUS_DRAFT,
                'submitted_at' => null,
            ]
        );

        ApplicationInformation::updateOrCreate(
            ['application_id' => $sampleApp->id],
            [
                'name' => 'Fakultas Farmasi Universitas Muhammadiyah Surakarta',
                'abbreviation' => 'FF-UMS',
                'address' => 'Kampus II UMS, Jl. A. Yani, Pabelan, Kartasura',
                'city' => 'Surakarta',
                'phone' => '0271-717417',
                'email' => 'farmasi@ums.ac.id',
            ]
        );

        ApplicationProfile::updateOrCreate(
            ['application_id' => $sampleApp->id],
            [
                'description' => 'Komite Etik yang melayani telaah etik penelitian di bidang klinis, biomedis, dan farmakoterapi.',
                'vision' => 'Menjadi pusat telaah etik penelitian terkemuka yang menjamin perlindungan subjek manusia sesuai kaidah Good Clinical Practice.',
                'mission' => "1. Melakukan telaah protokol penelitian secara objektif dan independen.\n2. Memberikan edukasi etika riset bagi civitas akademika.",
            ]
        );

        ApplicationMember::firstOrCreate(
            [
                'application_id' => $sampleApp->id,
                'name' => 'Prof. Dr. apt. Muhammad Ridwan, M.Sc.',
            ],
            [
                'position' => 'Ketua KEPK',
                'email' => 'ridwan@ums.ac.id',
                'phone' => '081234567890',
            ]
        );

        ApplicationMember::firstOrCreate(
            [
                'application_id' => $sampleApp->id,
                'name' => 'Dr. dr. Siti Nurhaliza, Sp.PK',
            ],
            [
                'position' => 'Sekretaris KEPK',
                'email' => 'siti.nurhaliza@ums.ac.id',
                'phone' => '081298765432',
            ]
        );
    }
}
