# SEKAR-MU — Sistem Akreditasi Komite Etik Penelitian Kesehatan

<p align="center">
  <strong>Sistem Evaluasi dan Akreditasi Komite Etik Penelitian Kesehatan Universitas Muhammadiyah Yogyakarta</strong><br>
  Standar WHO-CIOMS & Komite Nasional Etik Penelitian Kesehatan (KNEPK) 164 Butir
</p>

---

## 🌟 Ringkasan Aplikasi

**SEKAR-MU** adalah platform web modern untuk digitalisasi dan otomasi akreditasi Komite Etik Penelitian Kesehatan (KEPK). Sistem ini memfasilitasi pengisian instrumen evaluasi diri 164 butir secara kolaboratif dan *real-time*, penelaahan asesor independen, verifikasi bukti dokumen pendukung, dan penetapan status kelulusan akreditasi.

---

## 👥 4 Role Pengguna Utama

1. **`admin` (Super Administrator)**:
   - Mengelola akun pengguna (*Ketua KEPK, Anggota, Asesor, Admin*).
   - Mengonfigurasi 164 butir kriteria evaluasi dan acuan standar.
   - Menugaskan asesor penilai ke permohonan akreditasi KEPK.
   - Menetapkan keputusan akhir akreditasi (**ACC / Terakreditasi** atau **Tidak Lolos**).

2. **`ketua_kepk` (Ketua Komite Etik)**:
   - Mengisi profil institusi, visi, misi, dan daftar protokol riset KEPK.
   - Mengisi evaluasi mandiri 164 butir dan mengunggah dokumen bukti bersama Anggota KEPK secara *real-time*.
   - Memantau progres, prediksi akreditasi, dan catatan rekomendasi asesor.

3. **`anggota` (Anggota Tim KEPK)**:
   - Berkolaborasi secara langsung mengisi butir evaluasi diri dan melampirkan berkas bukti dukung.
   - Memantau hasil capaian skor akreditasi KEPK.

4. **`asessor` (Tim Penilai Akreditasi Independen)**:
   - Menelaah borang evaluasi diri dan memeriksa berkas dokumen bukti secara *real-time*.
   - Memberikan skor verifikasi independen (A/B/C/D), catatan temuan, dan rekomendasi akreditasi resmi.

---

## ⚡ Siklus Status Permohonan (Streamlined 3-State Model)

Sistem menggunakan alur kerja kolaboratif *real-time* dengan 3 status utama:

```
[ in_progress ]  ────────► [ approved ]  (ACC / Terakreditasi)
(Proses Evaluasi) ────────► [ rejected ]  (Tidak Lolos)
```

- **`in_progress` (Proses Evaluasi)**: Status aktif di mana Ketua dan Anggota KEPK mengisi evaluasi diri dan mengunggah berkas, sementara Asesor dapat menilai secara bersamaan tanpa perlu pengajuan formal berulang.
- **`approved` (Terakreditasi)**: Status kelulusan akreditasi resmi yang disahkan oleh Admin.
- **`rejected` (Tidak Lolos)**: Status permohonan yang belum memenuhi standar minimum akreditasi.

---

## 📊 Aturan Kelengkapan Berkas & Penilaian

### 1. Aturan Kelengkapan Dokumen Bukti per Butir
- **0 Berkas**: *Belum Ada Dokumen*
- **1 Berkas**: *Belum Lengkap* (Bobot progres: 50%)
- **$\ge$ 2 Berkas**: *Lengkap* (Bobot progres: 100%)

### 2. Skala Penilaian Kepatuhan Standar
- **Nilai A (Terpenuhi Penuh)**: Bobot 100% (1.0 poin)
- **Nilai B (Terpenuhi Sebagian)**: Bobot 50% (0.5 poin)
- **Nilai C (Tidak Terpenuhi)**: Bobot 0% (0 poin)
- **Nilai D (Tidak Dapat Dinilai)**: Dikeluarkan dari pembagi/denominator

### 3. Ambang Batas Klasifikasi Akreditasi
- **Tipe A (Terakreditasi Penuh)**: Skor $\ge 80\%$ dan tanpa nilai C ($C = 0$).
- **Tipe B (Terakreditasi Bersyarat)**: Skor $\ge 65\%$ dan nilai C $\le 5$ butir.
- **Tipe C (Terakreditasi Minimal)**: Skor $\ge 50\%$.
- **Belum Memenuhi Syarat**: Skor $< 50\%$.

---

## 🛠️ Tech Stack & Arsitektur

- **Framework**: Laravel 11 / 12 (PHP 8.2+)
- **Frontend / UI**: Livewire 3 + Alpine.js + Tailwind CSS v4 (@tailwindcss/vite) + Material Symbols
- **Autentikasi & RBAC**: Laravel Breeze + `spatie/laravel-permission`
- **Database**: MySQL
- **PDF Engine**: Barryvdh DomPDF

---

## 🚀 Panduan Memulai (Quickstart)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup Environment
cp .env.example .env
php artisan key:generate

# 3. Migrasi Database & Seeder
php artisan migrate:fresh --seed

# 4. Compile Assets & Jalankan Server
npm run build
php artisan serve
```

### Akun Bawaan (Default Seeders):
- **Super Admin**: `admin@sekarmu.test` / `password`
- **Ketua KEPK**: `ketua@sekarmu.test` / `password`
- **Anggota KEPK**: `anggota@sekarmu.test` / `password`
- **Asesor Penilai**: `asessor@sekarmu.test` / `password`

---

## 📄 Lisensi
Hak Cipta © 2026 Universitas Muhammadiyah Yogyakarta. Dikembangkan untuk Komisi Etik Penelitian Kesehatan UMY.
