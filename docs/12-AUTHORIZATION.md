# Authorization & Role-Based Access Control

## 1. 4 Role Pengguna Utama (Spatie Laravel Permission)

| Role | Label | Deskripsi Hak Akses |
| :--- | :--- | :--- |
| `admin` | Super Administrator | Akses penuh ke manajemen akun, kriteria 164 butir, penugasan asesor, dan keputusan akhir akreditasi (ACC/Tolak). |
| `ketua_kepk` | Ketua KEPK | Mengelola profil KEPK, formulir aplikasi, daftar protokol riset, evaluasi mandiri 164 butir, dan dokumen bukti. |
| `anggota` | Anggota KEPK | Berkolaborasi mengisi evaluasi mandiri 164 butir, dokumen bukti dukung, dan matriks rekapitulasi (dibatasi dari formulir institusi dan list protokol). |
| `asessor` | Asesor Akreditasi | Mengakses portal penilaian, menelaah dokumen bukti, mengisi skor asesor independen, dan memberikan rekomendasi akreditasi pada berkas yang ditugaskan. |

---

## 2. Resource-Level Policies

### `SuratPengajuanPolicy`
- **`viewAny(User $user)`**: Diizinkan untuk semua user terotentikasi.
- **`view(User $user, SuratPengajuan $surat)`**: Diizinkan untuk Admin, Asesor, Ketua KEPK, Anggota KEPK, atau pemilik berkas.
- **`create(User $user)`**: Diizinkan untuk Admin, Ketua KEPK, dan Anggota KEPK.
- **`update(User $user, SuratPengajuan $surat)`**: Diizinkan jika berkas berstatus `in_progress` (editable) oleh Admin, Ketua KEPK, Anggota KEPK, atau pemilik berkas.
- **`submit(User $user, SuratPengajuan $surat)`**: Diizinkan untuk Admin, Ketua KEPK, atau pemilik berkas.
- **`decide(User $user, SuratPengajuan $surat)`**: Khusus Admin (`$user->isAdmin()`).
- **`delete(User $user, SuratPengajuan $surat)`**: Diizinkan untuk Admin atau pemilik berkas (saat berkas berstatus draft).

### `DokumenPolicy` & `ListProtokolPolicy`
- Melindungi akses berkas dokumen lampiran dan protokol riset berdasarkan hak akses user terhadap pengajuan terkait.

### Asessor Workbench Guard (`LembarPenilaian`)
- Akses halaman workbench dilindungi middleware `role:asessor|admin`.
- Jika asesor belum ditugaskan secara resmi pada berkas pengajuan tersebut (`!$isAssigned`), sistem menampilkan layar peringatan ramah (*friendly guard screen*) yang menjelaskan bahwa berkas belum ditugaskan oleh Administrator.

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
