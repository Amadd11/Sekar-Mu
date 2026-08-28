# Business Rules

## 1. Siklus & Status Permohonan (Application Lifecycle)
Aplikasi menggunakan siklus sederhana 3 status:
- **`in_progress`** (Proses Evaluasi - Default): Permohonan aktif, dapat diedit dan diunggah dokumennya secara kolaboratif oleh Ketua KEPK dan Anggota KEPK, serta dapat dinilai secara bersamaan oleh Asesor penilai.
- **`approved`** (Terakreditasi): Keputusan resmi penetapan kelulusan akreditasi oleh Administrator (ACC).
- **`rejected`** (Tidak Lolos): Keputusan penolakan oleh Administrator jika belum memenuhi standar mutu minimum.

### Transisi Status yang Sah:
```
in_progress → approved (ACC oleh Admin)
in_progress → rejected (Tolak oleh Admin)
approved/rejected → in_progress (Buka Kembali oleh Admin jika diperlukan)
```

## 2. Aturan Kelengkapan Dokumen Bukti (File Evidence Rule)
Kelengkapan dokumen bukti pendukung dihitung otomatis per butir evaluasi:
- **0 Berkas**: Belum ada lampiran.
- **1 Berkas**: *Belum Lengkap* (berkontribusi 50% pada progres kelengkapan).
- **$\ge$ 2 Berkas**: *Lengkap* (berkontribusi 100% pada progres kelengkapan).

## 3. Skala Penilaian Kepatuhan (Compliance Scoring)
Setiap butir dievaluasi dengan skala nilai baku:
- **A — Terpenuhi Penuh**: Bobot 100% (1.0 poin)
- **B — Terpenuhi Sebagian**: Bobot 50% (0.5 poin)
- **C — Tidak Terpenuhi**: Bobot 0% (0.0 poin)
- **D — Tidak Dapat Dinilai**: Dikeluarkan dari pembagi/denominator perhitungan persentase skor total.

## 4. Klasifikasi Hasil Akreditasi
- **Tipe A (Terakreditasi Penuh)**: Skor kepatuhan $\ge 80\%$ dan tanpa nilai C ($C = 0$).
- **Tipe B (Terakreditasi Bersyarat)**: Skor kepatuhan $\ge 65\%$ dan jumlah nilai C $\le 5$ butir.
- **Tipe C (Terakreditasi Minimal)**: Skor kepatuhan $\ge 50\%$.
- **Belum Memenuhi Syarat**: Skor kepatuhan $< 50\%$.

## 5. Otorisasi & Peran
- **Ketua & Anggota KEPK**: Mengelola profil KEPK, daftar protokol, evaluasi mandiri 164 butir, dan dokumen bukti.
- **Asesor Akreditasi**: Menelaah borang dan dokumen bukti secara independen, memberikan skor asesor, catatan telaah, dan rekomendasi kelulusan.
- **Administrator**: Menugaskan tim asesor, mengelola akun pengguna & instrumen 164 butir, serta mengesahkan keputusan akhir akreditasi (ACC/Tolak).
