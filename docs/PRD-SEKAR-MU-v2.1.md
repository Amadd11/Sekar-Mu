# PRD — SEKAR-MU (Sistem Evaluasi dan Akreditasi Komite Etik Penelitian Kesehatan UMY)

**Version:** 2.2  
**Status:** Product Requirement Document (Up-to-date)  
**Target Stack:** Laravel 11/12 + TALL Stack (Tailwind CSS v4, Alpine.js, Laravel Breeze, Livewire 3) + Blade + MySQL  
**Architecture:** Form Request + Service Layer + Eloquent + Policy + Clean Architecture  

---

## 1. Ringkasan Eksekutif & Domain Overview

SEKAR-MU adalah platform web modern untuk digitalisasi dan otomasi akreditasi Komite Etik Penelitian Kesehatan (KEPK) berbasis standar Komite Nasional Etik Penelitian Kesehatan (KNEPK) 164 Butir.

Sistem mendukung:
1. **Evaluasi Diri Kolaboratif Real-Time**: Pengisian instrumen 164 butir dan unggah multi-berkas bukti dukung oleh Ketua & Anggota KEPK secara bersamaan.
2. **Multi-File Evidence Engine**: Pengunggahan berkas bukti dukung per butir dengan validasi kelengkapan (*1 file = Belum Lengkap/50%, $\ge 2$ file = Lengkap/100%*).
3. **Portal Telaah Asesor Independen**: Asesor menelaah dokumen bukti dan memberikan skor verifikasi (A/B/C/D), temuan, serta catatan rekomendasi.
4. **Compliance & Prediction Engine**: Perhitungan skor kepatuhan baku (%) dan penentuan otomatis klasifikasi prediktif (*Tipe A, B, C*).
5. **Pusat Kontrol Super Admin**: Penugasan asesor penilai dan pengesahan hasil keputusan akhir akreditasi (**ACC / Terakreditasi** atau **Tidak Lolos**).

---

## 2. Komposisi 164 Butir Kriteria Standar KNEPK

| Bagian | Komponen Standar Akreditasi | Jumlah Butir |
|---|---|---:|
| **A** | Regulasi, Kelembagaan, dan Tata Kelola | 29 |
| **B** | Keanggotaan dan Kompetensi | 35 |
| **C** | Operasional dan Prosedur | 74 |
| **D** | Fasilitas dan Sumber Daya | 12 |
| **E** | Penelitian Khusus | 14 |
| | **Total Butir Standar** | **164** |

---

## 3. Skala Penilaian & Klasifikasi Akreditasi

### 3.1 Skala Nilai
- **A — Terpenuhi Penuh** (Bobot: 100% / 1.0 poin)
- **B — Terpenuhi Sebagian** (Bobot: 50% / 0.5 poin)
- **C — Tidak Terpenuhi** (Bobot: 0% / 0.0 poin)
- **D — Tidak Dapat Dinilai** (Dikeluarkan dari pembagi perhitungan persentase skor)

### 3.2 Syarat Kategori Kelulusan
- **Tipe A (Terakreditasi Penuh)**: Skor Kepatuhan $\ge 80\%$ dan tanpa nilai C ($C = 0$).
- **Tipe B (Terakreditasi Bersyarat)**: Skor Kepatuhan $\ge 65\%$ dan nilai C maksimal 5 butir ($C \le 5$).
- **Tipe C (Terakreditasi Minimal)**: Skor Kepatuhan $\ge 50\%$.
- **Belum Memenuhi Syarat**: Skor Kepatuhan $< 50\%$.

---

## 4. Struktur 4 Role Pengguna

1. **Super Admin (`admin`)**:
   - Manajemen akun pengguna (*Ketua, Anggota, Asesor, Admin*).
   - Manajemen kriteria 164 butir evaluasi.
   - Penugasan tim asesor penilai ke permohonan KEPK.
   - Penetapan keputusan akhir akreditasi resmi (**ACC / Terakreditasi** atau **Tolak**).

2. **Ketua KEPK (`ketua_kepk`)**:
   - Mengisi identitas institusi, profil KEPK, dan daftar protokol riset.
   - Mengisi evaluasi mandiri 164 butir dan melampirkan berkas bukti dukung.
   - Memantau hasil telaah, ulasan rekomendasi asesor, dan status keputusan akreditasi.

3. **Anggota KEPK (`anggota`)**:
   - Berkolaborasi secara real-time mengisi evaluasi mandiri dan melampirkan dokumen bukti.

4. **Asesor Akreditasi (`asessor`)**:
   - Mengakses portal penilaian untuk permohonan yang ditugaskan.
   - Memeriksa kelengkapan dokumen bukti yang diunggah KEPK.
   - Memberikan skor independen (A/B/C/D), catatan telaah, dan rekomendasi akreditasi resmi.

---

## 5. Siklus Status Permohonan (Streamlined 3-State Model)

```
[ in_progress ]  ────────► [ approved ]  (ACC / Terakreditasi)
(Proses Evaluasi) ────────► [ rejected ]  (Tidak Lolos)
```

- **`in_progress`**: Status aktif pengisian dan penelaahan secara real-time.
- **`approved`**: Keputusan kelulusan resmi yang disahkan oleh Admin.
- **`rejected`**: Keputusan penolakan oleh Admin jika permohonan belum memenuhi standar mutu.
