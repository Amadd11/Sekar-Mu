# SEKAR-MU — Project Overview

## Stack
- Laravel 11 / 12 (PHP 8.2+)
- TALL Stack: Tailwind CSS v4 (@tailwindcss/vite), Alpine.js, Laravel Breeze, Livewire 3
- Blade Components
- spatie/laravel-permission (Role & Permission Management)
- MySQL
- Barryvdh DomPDF

## Architecture
Route → Livewire Component → Service Layer → Eloquent Model → MySQL Database

## Core Roles
1. `admin` (Super Administrator): Mengelola pengguna, kriteria 164 butir, penugasan asesor, dan pengesahan status kelulusan (ACC/Tolak).
2. `ketua_kepk` (Ketua KEPK): Mengisi profil KEPK, daftar protokol, evaluasi mandiri, berkas dokumen, dan memantau hasil akreditasi.
3. `anggota` (Anggota Tim KEPK): Berkolaborasi secara real-time mengisi evaluasi diri dan dokumen bukti dukung.
4. `asessor` (Asesor Akreditasi): Menelaah instrumen evaluasi diri, memverifikasi dokumen bukti, dan memberikan rekomendasi akreditasi independen.

## Status Permohonan (Streamlined 3-State Lifecycle)
1. `in_progress` (Proses Evaluasi): Status kerja kolaboratif real-time saat Ketua/Anggota mengisi dan Asesor menelaah.
2. `approved` (Terakreditasi): Status resmi yang telah disahkan oleh Admin.
3. `rejected` (Tidak Lolos): Status permohonan yang ditolak atau belum memenuhi standar minimum.

## Naming & Conventions
- Roles and statuses use standard string constants in `SuratPengajuan` (`STATUS_IN_PROGRESS`, `STATUS_APPROVED`, `STATUS_REJECTED`).
- Authorization via Spatie Permission + Laravel Policies (`SuratPengajuanPolicy`).
