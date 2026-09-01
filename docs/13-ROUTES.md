# Routes Specification

## 1. Halaman Umum & Autentikasi
- `GET /` → Mengarahkan ke `/dashboard` jika login, atau `/login` jika belum.
- `GET /dashboard` → `App\Livewire\Dashboard` (Role-tailored dashboard untuk Admin, Asesor, Ketua & Anggota).
- `GET /profile` → Halaman profil akun pengguna.

## 2. Pengajuan Permohonan KEPK
- `GET /pengajuan` → `App\Livewire\Pengajuan\Index` (`pengajuan.index`)
- `GET /pengajuan/create` → `App\Livewire\Pengajuan\Create` (`pengajuan.create` - Ketua, Anggota & Admin)
- `GET /pengajuan/{suratPengajuan}` → `App\Livewire\HasilAkreditasi\Index` (`pengajuan.show`)

## 3. Modul Pengisian Berkas & Evaluasi Mandiri (Ketua, Anggota & Admin)
- `GET /pengajuan/{suratPengajuan}/formulir-aplikasi` → `App\Livewire\Pengajuan\FormulirAplikasi` (`pengajuan.formulir-aplikasi`)
- `GET /pengajuan/{suratPengajuan}/profil` → `App\Livewire\Pengajuan\Profil` (`pengajuan.profil`)
- `GET /pengajuan/{suratPengajuan}/evaluasi-diri` → `App\Livewire\Pengajuan\EvaluasiDiri` (`pengajuan.evaluasi-diri`)
- `GET /pengajuan/{suratPengajuan}/list-protokol` → `App\Livewire\Pengajuan\ListProtokol` (`pengajuan.list-protokol`)
- `GET /pengajuan/{suratPengajuan}/dokumen` → `App\Livewire\Pengajuan\Dokumen` (`pengajuan.dokumen`)

## 4. Modul Penilaian Asesor (Asesor & Admin)
- `GET /penilaian` → `App\Livewire\Penilaian\Index` (`penilaian.index`)
- `GET /penilaian/{suratPengajuan}` → `App\Livewire\Penilaian\LembarPenilaian` (`penilaian.show`)

## 5. Modul Khusus Administrator (Admin Only)
- `GET /pengajuan/{suratPengajuan}/tugaskan-penilai` → `App\Livewire\Penilaian\TugaskanPenilai` (`penilaian.tugaskan`)
- `GET /admin/kriteria` → `App\Livewire\Admin\KriteriaEvaluasi` (`admin.kriteria.index` & alias `admin.kriteria`)
- `GET /admin/users` → `App\Livewire\Admin\ManajemenAkun` (`admin.users.index` & alias `admin.users`)
