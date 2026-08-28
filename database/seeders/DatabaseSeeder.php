<?php

namespace Database\Seeders;

use App\Models\Institusi;
use App\Models\Kepk;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles (Hanya 4 Role Utama Sesuai Ketentuan)
        $roles = ['admin', 'ketua_kepk', 'asessor', 'anggota'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekarmu.test'],
            [
                'name' => 'Admin KEPK (Super Admin)',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['admin']);

        $ketua = User::firstOrCreate(
            ['email' => 'ketua@sekarmu.test'],
            [
                'name' => 'Dr. dr. H. Budi Santoso, Sp.FK (Ketua KEPK)',
                'password' => Hash::make('password'),
            ]
        );
        $ketua->syncRoles(['ketua_kepk']);

        $asessor = User::firstOrCreate(
            ['email' => 'asessor@sekarmu.test'],
            [
                'name' => 'Prof. Siti Rahayu, Ph.D (Asesor Akreditasi)',
                'password' => Hash::make('password'),
            ]
        );
        $asessor->syncRoles(['asessor']);

        $anggota = User::firstOrCreate(
            ['email' => 'anggota@sekarmu.test'],
            [
                'name' => 'apt. Farida Nur Aini, M.Sc. (Anggota KEPK)',
                'password' => Hash::make('password'),
            ]
        );
        $anggota->syncRoles(['anggota']);

        // 3. Institusi & KEPK
        $institusi = Institusi::firstOrCreate(
            ['name' => 'Universitas Muhammadiyah Yogyakarta'],
            [
                'address' => 'Jl. Brawijaya, Geblagan, Tamantirto, Kec. Kasihan, Kabupaten Bantul',
                'city' => 'Yogyakarta',
                'phone' => '0274-387656',
                'email' => 'info@umy.ac.id',
            ]
        );

        $kepk = Kepk::firstOrCreate(
            ['code' => 'KEPK-UMY-001'],
            [
                'institusi_id' => $institusi->id,
                'name' => 'Komisi Etik Penelitian Kesehatan UMY',
                'status' => 'active',
            ]
        );

        // 4. Seeder Instrumen Evaluasi Diri (Bagian A-E)
        $this->call(InstrumenEvaluasiSeeder::class);

        // 5. Sample Surat Pengajuan
        $surat = SuratPengajuan::firstOrCreate(
            [
                'user_id' => $ketua->id,
                'kepk_id' => $kepk->id,
            ],
            [
                'status' => SuratPengajuan::STATUS_IN_PROGRESS,
            ]
        );

        $surat->penilai()->syncWithoutDetaching([$asessor->id]);

        $surat->formulirAplikasi()->firstOrCreate(
            ['surat_pengajuan_id' => $surat->id],
            [
                'nama_institusi' => 'Fakultas Kedokteran dan Ilmu Kesehatan UMY',
                'singkatan' => 'FKIK-UMY',
                'alamat' => 'Kampus Terpadu UMY, Jl. Brawijaya',
                'kota' => 'Yogyakarta',
                'telepon' => '0274-387656',
                'email' => 'fkik@umy.ac.id',
            ]
        );

        $surat->profilKepk()->firstOrCreate(
            ['surat_pengajuan_id' => $surat->id],
            [
                'deskripsi' => 'Komisi Etik Penelitian Kesehatan yang independen, kompeten, dan berintegritas.',
                'visi' => 'Menjadi KEPK rujukan berstandar internasional yang berlandaskan nilai-nilai keislaman.',
                'misi' => '1. Menelaah protokol penelitian kesehatan secara objektif dan independen.\n2. Melindungi harkat, martabat, dan hak-hak subjek penelitian.',
            ]
        );
    }
}
