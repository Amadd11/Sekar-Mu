# PRD — SEKAR-MU (Sistem Evaluasi dan Akreditasi Komite Etik Penelitian Kesehatan Universitas Muhammadiyah Yogyakarta)

**Version:** 2.1  
**Status:** Product Requirement Document (Final Draft)  
**Target Stack:** Laravel 13 + TALL Stack (Tailwind CSS, Alpine.js, Laravel Breeze, Livewire) + Blade + MySQL  
**Architecture:** Form Request + Service Layer + Eloquent + Policy + Clean Architecture  
**Source-code Naming:** Strict Domain-Driven Naming (Dilarang menggunakan kode formulir seperti `B01-01`, `B01Controller`).

---

## 1. Ringkasan Eksekutif & Domain Overview

SEKAR-MU adalah platform web terpadu untuk digitalisasi proses **Evaluasi Mandiri (Internal KEPK)**, **Multi-File Evidence Management**, **Asesmen Independen Asesor**, **Compliance Engine**, **Gap Analysis**, **Corrective Action**, serta **Sinkronisasi Dashboard & Pelaporan Akreditasi KEPK UMY** berbasis standar Komite Nasional Etik Penelitian Kesehatan (KNEPK).

### 1.1 Komposisi 164 Butir Kriteria Evaluasi

| Kode | Komponen Standar Akreditasi | Jumlah Butir |
|---|---|---:|
| **A** | Regulasi, Kelembagaan, dan Tata Kelola | 29 |
| **B** | Keanggotaan dan Kompetensi | 35 |
| **C** | Operasional dan Prosedur | 74 |
| **D** | Fasilitas dan Sumber Daya | 12 |
| **E** | Penelitian Khusus | 14 |
| | **Total Butir Penilaian** | **164** |

### 1.2 Skala Penilaian & Bobot Nilai

- **A — Terpenuhi Penuh** (Bobot: 100)
- **B — Terpenuhi Sebagian** (Bobot: 50)
- **C — Tidak Terpenuhi** (Bobot: 0)
- **D — Tidak Dapat Dinilai** (Dikeluarkan dari pembagi/denominator, wajib disertai alasan)

### 1.3 Kriteria Prediksi Klasifikasi Kelulusan Akreditasi

- **Tipe A:** Skor $\ge 80\%$ dan tidak memiliki nilai C sama sekali ($	ext{Count}(C) = 0$).
- **Tipe B:** Skor $\ge 65\%$ dan nilai C maksimal 5 butir ($	ext{Count}(C) \le 5$).
- **Tipe C:** Skor $\ge 50\%$.
- **Belum Memenuhi Syarat:** Skor $< 50\%$.

---

## 2. Masalah Utama & Solusi Produk

| Masalah Eksisting | Solusi SEKAR-MU v2.1 |
|---|---|
| Istilah *self-assessment* ambigu dan bercampur dengan review luar | Standardisasi istilah menjadi **Evaluasi Mandiri (Internal)** vs **Asesmen Asesor (Eksternal)** |
| 1 butir evaluasi hanya bisa menampung 1 file bukti | **Multi-File Evidence Engine** per butir evaluasi dengan metadata label & preview |
| Penilaian asesor berisiko menimpa draft internal KEPK | **Dual-Layer Scoring System** (layer evaluasi internal terpisah dari layer asesor) |
| Selisih persepsi nilai antara KEPK dan Asesor sulit dilacak | **Real-time Gap Analysis & Comparison Matrix** di dashboard Ketua & Anggota |
| Temuan ketidaksesuaian tidak tertindaklanjuti | **Corrective Action Management** terintegrasi dengan PIC & deadline |

---

## 3. Struktur Peran Pengguna (4 User Roles)

### 3.1 Super Admin
- Mengelola akun pengguna, autentikasi, dan penetapan role.
- Mengonfigurasi master data 164 butir kriteria evaluasi dan parameter compliance engine.
- Mengelola backup/restore data dan memantau audit trail aktivitas sistem.

### 3.2 Ketua KEPK
- Bertindak sebagai penanggung jawab utama evaluasi internal KEPK.
- Mendelegasikan butir evaluasi tertentu kepada Anggota KEPK.
- Meninjau, memberi nilai final evaluasi mandiri, dan mengunggah/memvalidasi dokumen bukti.
- Mengakses **Dashboard Eksekutif Ketua**:
  - Overall Compliance Score & Prediksi Tipe Akreditasi.
  - Perbandingan langsung (*side-by-side*) antara Nilai Evaluasi Mandiri vs Nilai Asesor.
  - Rekap Critical Findings, Gap Analysis, dan status Corrective Action.
- Mengesahkan berkas evaluasi untuk dibuka ke Asesor.

### 3.3 Anggota KEPK
- Mengisi nilai dan mengunggah multi-file evidence pada butir evaluasi yang ditugaskan.
- Mengajukan draf evaluasi kepada Ketua KEPK.
- Mengakses **Dashboard Anggota KEPK**:
  - Memantau progres butir kriteria yang menjadi tanggung jawabnya.
  - Melihat feedback, temuan, dan downgrade nilai dari Asesor.
  - Menerima tugas tindakan perbaikan (*corrective action*) dan melampirkan bukti revisi.

### 3.4 Asesor
- Bekerja di **Workspace Asesor Independen** (tidak dapat mengedit berkas evaluasi internal KEPK).
- Memeriksa dokumen multi-file evidence yang diunggah KEPK.
- Memberikan penilaian independen (A/B/C/D), mencatat temuan (*findings*), dan memberikan rekomendasi perbaikan.
- Memverifikasi dokumen perbaikan (*corrective action verification*).
- Menghasilkan Berita Acara & Laporan Asesmen Akreditasi.

---

## 4. Arsitektur Pemisahan Penilaian (Dual-Layer Scoring & Gap Analysis)

Nilai Evaluasi Internal KEPK dan Nilai Asesmen Asesor disimpan dalam entitas terpisah untuk menjaga independensi audit.

```
                  [ Butir Kriteria (assessment_items) ]
                                    │
           ┌────────────────────────┴────────────────────────┐
           ▼                                                 ▼
[ Layer Evaluasi KEPK ]                             [ Layer Asesmen Asesor ]
(evaluations)                                       (assessor_evaluations)
├── score_internal (A/B/C/D)                        ├── score_assessor (A/B/C/D)
├── internal_notes                                  ├── assessor_findings / notes
└── evaluation_evidences (Multi-file)               └── verification_status
           │                                                 │
           └────────────────────────┬────────────────────────┘
                                    ▼
                      [ Gap Analysis & Dashboard Sync ]
                      ├── Score Gap = (Internal - Assessor)
                      ├── Critical Findings Notification
                      └── Real-time Dashboard (Ketua & Anggota)
```

### Matriks Komparasi Evaluasi vs Asesor
| Kode Butir | Parameter Evaluasi | Nilai Evaluasi KEPK | Nilai Asesor | Selisih (Gap) | Temuan / Catatan Asesor | Tindakan Lanjut |
|---|---|:---:|:---:|:---:|---|---|
| **A1.1** | SK Pembentukan KEPK | **A** (100) | **A** (100) | 0 | Dokumen legalitas valid & aktif | Tidak ada |
| **B2.3** | Pakta Integritas & COI | **A** (100) | **B** (50) | -50 | 2 anggota belum upload form COI | Unggah revisi bukti |
| **C3.4** | SOP Post-Approval Review | **B** (50) | **C** (0) | -50 | Tidak tersedia format laporan SAE | Buka Corrective Action |

---

## 5. Spesifikasi Fitur Multi-File Evidence

Setiap butir kriteria evaluasi mendukung pengunggahan banyak dokumen pendukung sekaligus tanpa batas tunggal.

### 5.1 Karakteristik Multi-File Upload
- **Format Didukung:** PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.
- **Ukuran Maksimum:** 10 MB per file.
- **Metadata Tersimpan:** Nama asli file, hash path penyimpanan, ukuran file, tipe MIME, label/keterangan dokumen, user pengunggah, dan timestamp.
- **Manajemen Berkas:**
  - Tambah file secara berkala tanpa menghapus file sebelumnya.
  - Labeling berkas (contoh: "SK Rektor", "Lampiran Struktur Organisasi", "Bukti Pelatihan").
  - Penghapusan file bukti dengan proteksi otorisasi Policy.
  - Preview langsung di browser (PDF & Gambar) dan unduh aman (Private Storage Download).

### 5.2 Skema Entitas Evidence
```
evaluations (1) ────< (N) evaluation_evidences
                          ├── id
                          ├── evaluation_id (FK)
                          ├── file_name
                          ├── file_path
                          ├── file_size
                          ├── mime_type
                          ├── description
                          ├── uploaded_by (FK -> users)
                          └── timestamps
```

---

## 6. Compliance Engine & Business Rules

### 6.1 Critical Item Rule
- Butir dengan flag `critical = true` yang dinilai **C** (baik oleh KEPK maupun Asesor) otomatis memicu status **🚨 CRITICAL NON-COMPLIANCE**.
- Sistem wajib mewajibkan pembentukan tiket **Corrective Action** prioritas tinggi.

### 6.2 Evidence Strength Rule
Nilai A tidak dapat divalidasi jika tingkat kekuatan bukti (*Evidence Strength*) belum terpenuhi:
- **E0:** Tidak ada bukti pendukung.
- **E1:** Dokumen ada tetapi belum lengkap/belum disahkan.
- **E2:** Dokumen lengkap dan disahkan pimpinan.
- **E3:** Dokumen lengkap + bukti implementasi riil (notulen/daftar hadir/logbook).
- **E4:** Dokumen lengkap + implementasi + bukti monitoring & evaluasi berkala.

### 6.3 Dependency Rule (Parent-Child Dependency)
Jika butir induk bernilai **C** (contoh: SK Pembentukan KEPK tidak ada), maka seluruh butir turunan (kewenangan, masa berlaku, struktur) otomatis terkunci pada nilai **C / Perlu Verifikasi**.

---

## 7. Modul Corrective Action Management

Alur penanganan temuan ketidaksesuaian:

```text
[ Temuan Nilai C / Catatan Asesor ]
               │
               ▼
   [ Tiket Corrective Action ] ──► Penetapan PIC & Deadline
               │
               ▼
      [ Status: OPEN ]
               │ (Pengerjaan perbaikan)
               ▼
   [ Status: IN_PROGRESS ]
               │ (Unggah bukti perbaikan)
               ▼
    [ Status: SUBMITTED ]
               │ (Verifikasi oleh Asesor / Ketua)
               ▼
    [ Status: VERIFIED ] ──► [ Status: CLOSED ]
```

---

## 8. Spesifikasi Teknis & Database Architecture

### 8.1 Tech Stack
- **Framework:** Laravel 13 (PHP 8.3+)
- **Frontend / UI:** Livewire 3 + Alpine.js + Tailwind CSS + Blade Component
- **Authentication:** Laravel Breeze
- **Database:** MySQL 8.0+
- **File Storage:** Laravel Storage Disk (Private S3/Local with Signed URLs)

### 8.2 Entity Relationship & Core Tables
1. `users` (id, name, email, password, role, is_active)
2. `institutions` (id, name, address, website)
3. `kepk_profiles` (id, institution_id, committee_name, category, established_year, head_name, secretary_name)
4. `kepk_members` (id, kepk_profile_id, user_id, name, role_title, expertise, gender, is_affiliated, cv_path, coi_path, confidentiality_path)
5. `assessment_sections` (id, code, title, order) -> *A s/d E*
6. `assessment_items` (id, section_id, code, parameter, requirement, required_evidence, is_critical, parent_item_id, order) -> *164 item*
7. `evaluations` (id, kepk_profile_id, assessment_item_id, score, notes, evidence_strength, assigned_to, evaluated_by, status, timestamps)
8. `evaluation_evidences` (id, evaluation_id, file_name, file_path, mime_type, file_size, description, uploaded_by, timestamps)
9. `assessor_evaluations` (id, evaluation_id, assessor_id, score, findings, recommendations, verification_status, timestamps)
10. `corrective_actions` (id, evaluation_id, finding, risk_level, action_plan, pic_id, priority, deadline, status, verification_notes, timestamps)
11. `corrective_action_evidences` (id, corrective_action_id, file_name, file_path, file_size, uploaded_by, timestamps)
12. `research_protocols` (id, kepk_profile_id, protocol_number, title, pi_name, review_type, submission_date, decision, approval_date)
13. `audit_logs` (id, user_id, action, module, record_id, old_values, new_values, ip_address, created_at)

---

## 9. Struktur Service Layer & Clean Code Mapping

Sesuai ketentuan PRD, dilarang menempatkan business logic pada Controller atau Livewire:

- `App\Services\EvaluationService`: Menangani penyimpanan nilai evaluasi, delegasi butir, dan kalkulasi skor komponen.
- `App\Services\EvidenceService`: Menangani sanitasi file, multi-upload, storage security, dan deletion.
- `App\Services\AssessorEvaluationService`: Menangani input nilai independen asesor dan komparasi skor.
- `App\Services\ComplianceEngineService`: Menghitung status kelulusan (Tipe A/B/C), rule critical item, parent-child dependency, dan gap analysis.
- `App\Services\CorrectiveActionService`: Orkestrasikan lifecycle tindakan perbaikan.
- `App\Services\ReportService`: Kompilasi data evaluasi, matriks perbandingan, dan generate PDF akreditasi.

---

## 10. Dashboard Matrix & Wireframe Requirement

### 10.1 Tampilan Dashboard Ketua KEPK
- **Executive Widget:** Skor KEPK vs Skor Asesor, Prediksi Kelulusan, Total Butir Terisi ($n/164$).
- **Score Breakdown A–E:** Bar chart / Progress per komponen.
- **Komparasi & Gap Analysis Grid:** Tabel 164 butir dengan filter selisih nilai (Hanya tampilkan nilai yang turun).
- **Critical Issues & Action Tracker:** Widget tiket perbaikan yang mendekati deadline ($D-7, D-3$).

### 10.2 Tampilan Dashboard Anggota KEPK
- **My Assigned Items:** Daftar kriteria yang ditugaskan beserta status kelengkapan bukti.
- **Asesor Feedback Stream:** Notifikasi catatan/temuan baru dari Asesor pada item terkait.
- **Revisi & Tindakan Perbaikan:** Action items yang membutuhkan upload dokumen pendukung baru.

---

## 11. Roadmap Pengembangan & Acceptance Criteria

### Fase 1: Fondasi & Modul Evaluasi Internal
- Setup Laravel Breeze + Role Authorization.
- Seeding 164 butir kriteria evaluasi (Bagian A–E).
- Form Evaluasi Interaktif (Livewire) + Multi-File Evidence Upload.

### Fase 2: Compliance Engine & Scoring
- Penghitungan skor otomatis A/B/C/D & Prediksi Tipe Akreditasi.
- Rule Critical Non-Compliance & Dependency Parent-Child.

### Fase 3: Workspace Asesor & Dual-Layer Scoring
- Interface Asesor mandiri untuk review dokumen & input nilai.
- Real-time Gap Analysis (Sinkronisasi nilai ke dashboard Ketua & Anggota).

### Fase 4: Corrective Action & Pelaporan
- Alur tiket perbaikan (Open $ightarrow$ Closed) + Verifikasi Asesor.
- Export Laporan Evaluasi Mandiri, Berita Acara Asesor, dan Rekapitulasi PDF.
