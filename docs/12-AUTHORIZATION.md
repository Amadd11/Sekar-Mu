# Authorization & Role-Based Access Control

## 1. 4 Role Pengguna Utama (Spatie Laravel Permission)

| Role | Label | Deskripsi Hak Akses |
| :--- | :--- | :--- |
| `admin` | Super Administrator | Akses penuh ke manajemen akun, kriteria 164 butir, penugasan asesor, dan keputusan akhir akreditasi (ACC/Tolak). |
| `ketua_kepk` | Ketua KEPK | Mengelola profil KEPK, daftar protokol, evaluasi mandiri 164 butir, dan dokumen bukti. |
| `anggota` | Anggota KEPK | Berkolaborasi mengisi evaluasi mandiri 164 butir dan mengunggah berkas bukti dukung. |
| `asessor` | Asesor Akreditasi | Mengakses portal penilaian, menelaah dokumen bukti, mengisi skor asesor independen, dan memberikan rekomendasi akreditasi. |

---

## 2. Resource-Level Policies (`SuratPengajuanPolicy`)

- **`viewAny(User $user)`**: Diizinkan untuk semua user terotentikasi.
- **`view(User $user, SuratPengajuan $surat)`**: Diizinkan untuk Admin, Asesor yang ditugaskan, Ketua KEPK, Anggota KEPK, atau pemilik berkas.
- **`create(User $user)`**: Diizinkan untuk Admin, Ketua KEPK, dan Anggota KEPK.
- **`update(User $user, SuratPengajuan $surat)`**: Diizinkan jika berkas berstatus `in_progress` (editable) oleh Admin, Ketua KEPK, Anggota KEPK, atau pemilik berkas.
- **`decide(User $user, SuratPengajuan $surat)`**: Hanya diizinkan untuk Admin (`$user->isAdmin()`).
- **`delete(User $user, SuratPengajuan $surat)`**: Diizinkan untuk Admin atau pemilik berkas.

---

## 3. Blade Authorization Directives

```blade
@role('admin')
    {{-- Khusus Menu / Tombol Super Admin --}}
@endrole

@role('asessor|admin')
    {{-- Khusus Portal & Lembar Penilaian Asesor --}}
@endrole

@can('decide', $suratPengajuan)
    {{-- Tombol ACC dan Tolak Pengajuan --}}
@endcan
```
