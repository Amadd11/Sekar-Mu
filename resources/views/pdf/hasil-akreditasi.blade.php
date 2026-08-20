<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Akreditasi KEPK #APP-{{ str_pad($surat->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #174668;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-logo {
            font-size: 28px;
            text-align: center;
            width: 50px;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #174668;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-subtitle {
            font-size: 10px;
            color: #64748b;
        }
        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin: 15px 0 10px 0;
            padding: 6px;
            background-color: #f1f5f9;
            border-radius: 4px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #174668;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        table.meta-table {
            width: 100%;
            margin-bottom: 10px;
        }
        table.meta-table td {
            padding: 3px 4px;
            font-size: 10.5px;
        }
        table.meta-table td.label {
            width: 25%;
            color: #64748b;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 10px;
        }
        table.data-table th {
            background-color: #174668;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #174668;
            text-align: left;
        }
        table.data-table td {
            padding: 4.5px 6px;
            border: 1px solid #cbd5e1;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9.5px;
        }
        .badge-tipe-a { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-tipe-b { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-tipe-c { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-belum { background-color: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }

        .score-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        .signature-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            font-size: 10.5px;
        }
        .footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">🌸</td>
            <td>
                <div class="header-title">SEKAR-MU — Sistem Evaluasi dan Akreditasi KEPK</div>
                <div class="header-subtitle">Universitas Muhammadiyah Yogyakarta • Berbasis Standar KNEPK & WHO-CIOMS</div>
            </td>
            <td style="text-align: right; font-size: 9px; color: #64748b;">
                <strong>No. Berkas:</strong> #APP-{{ str_pad($surat->id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Tgl Cetak:</strong> {{ $printedAt }}
            </td>
        </tr>
    </table>

    <div class="doc-title">
        Laporan Hasil Evaluasi & Rekomendasi Akreditasi KEPK
    </div>

    <!-- 1. Identitas KEPK & Pemohon -->
    <div class="section-title">1. Identitas Institusi & Komite Etik</div>
    <table class="meta-table">
        <tr>
            <td class="label">Nama Institusi / Lembaga:</td>
            <td>{{ $surat->formulirAplikasi->nama_institusi ?? $surat->kepk->institusi->name ?? '-' }}</td>
            <td class="label">Status Pengajuan:</td>
            <td><strong>{{ \App\Models\SuratPengajuan::statusLabel($surat->status) }}</strong></td>
        </tr>
        <tr>
            <td class="label">Singkatan / Akronim:</td>
            <td>{{ $surat->formulirAplikasi->singkatan ?? '-' }}</td>
            <td class="label">Tanggal Pengajuan:</td>
            <td>{{ $surat->diajukan_pada?->format('d F Y') ?? $surat->created_at->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Kota / Kabupaten:</td>
            <td>{{ $surat->formulirAplikasi->kota ?? '-' }}</td>
            <td class="label">Pemohon:</td>
            <td>{{ $surat->user->name ?? '-' }}</td>
        </tr>
    </table>

    <!-- 2. Ringkasan Prediksi Akreditasi & Kepatuhan -->
    <div class="section-title">2. Hasil Penilaian Kepatuhan 164 Butir Standar</div>
    <div class="score-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 35%; text-align: center; border-right: 1px solid #cbd5e1; padding-right: 10px;">
                    <div style="font-size: 9px; color: #64748b; font-weight: bold; text-transform: uppercase;">Prediksi Akreditasi:</div>
                    <div style="font-size: 16px; font-weight: bold; margin: 4px 0;">
                        <span class="badge {{ $metrics['overall_compliance'] >= 80 && $metrics['counts']['C'] == 0 ? 'badge-tipe-a' : ($metrics['overall_compliance'] >= 65 && $metrics['counts']['C'] <= 5 ? 'badge-tipe-b' : ($metrics['overall_compliance'] >= 50 ? 'badge-tipe-c' : 'badge-belum')) }}">
                            {{ $metrics['prediction']['type'] }}
                        </span>
                    </div>
                    <div style="font-size: 9px; color: #64748b;">{{ $metrics['prediction']['description'] }}</div>
                </td>
                <td style="padding-left: 15px;">
                    <table style="width: 100%; font-size: 10.5px;">
                        <tr>
                            <td><strong>Skor Kepatuhan Total:</strong></td>
                            <td style="font-size: 13px; font-weight: bold; color: #174668;">{{ $metrics['overall_compliance'] }}%</td>
                            <td><strong>Butir Terpenuhi Penuh (A):</strong></td>
                            <td style="font-weight: bold; color: #059669;">{{ $metrics['counts']['A'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Butir Terisi:</strong></td>
                            <td>{{ $metrics['total_answered'] }} / {{ $metrics['total_items'] }}</td>
                            <td><strong>Butir Terpenuhi Sebagian (B):</strong></td>
                            <td style="font-weight: bold; color: #d97706;">{{ $metrics['counts']['B'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Poin Skor:</strong></td>
                            <td>{{ $metrics['total_score_points'] }} poin</td>
                            <td><strong>Butir Tidak Terpenuhi (C):</strong></td>
                            <td style="font-weight: bold; color: {{ $metrics['counts']['C'] > 0 ? '#e11d48' : '#64748b' }};">{{ $metrics['counts']['C'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- 3. Breakdown Nilai per Komponen A-E -->
    <div class="section-title">3. Pencapaian Kepatuhan Berdasarkan 5 Komponen Standar</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">Kode</th>
                <th>Komponen Standar Akreditasi KNEPK</th>
                <th style="width: 12%; text-align: center;">Total Butir</th>
                <th style="width: 12%; text-align: center;">Terisi</th>
                <th style="width: 25%; text-align: center;">Rincian (A / B / C / D)</th>
                <th style="width: 15%; text-align: center;">Kepatuhan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($metrics['sections'] as $sec)
                <tr>
                    <td style="font-weight: bold; text-align: center;">{{ $sec['kode'] }}</td>
                    <td>{{ $sec['nama'] }}</td>
                    <td style="text-align: center;">{{ $sec['total_items'] }}</td>
                    <td style="text-align: center;">{{ $sec['answered_items'] }}</td>
                    <td style="text-align: center; font-size: 9.5px;">
                        <span style="color: #059669; font-weight: bold;">{{ $sec['counts']['A'] }}</span> /
                        <span style="color: #d97706; font-weight: bold;">{{ $sec['counts']['B'] }}</span> /
                        <span style="color: #e11d48; font-weight: bold;">{{ $sec['counts']['C'] }}</span> /
                        <span style="color: #64748b;">{{ $sec['counts']['D'] }}</span>
                    </td>
                    <td style="text-align: center; font-weight: bold; color: #174668;">
                        {{ $sec['compliance_percentage'] }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 4. Ulasan & Rekomendasi Asesor -->
    <div class="section-title">4. Rekomendasi & Catatan Asesor Penilai</div>
    @if ($surat->penilaianEtik->isNotEmpty())
        @foreach ($surat->penilaianEtik as $pe)
            <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 8px 10px; border-radius: 4px; margin-bottom: 8px; font-size: 10.5px;">
                <div style="font-weight: bold; color: #0f172a; margin-bottom: 3px;">
                    👤 Asesor: {{ $pe->penilai->name }}
                    <span style="font-weight: normal; color: #64748b; font-size: 9.5px;">({{ $pe->created_at->format('d M Y, H:i') }})</span>
                    — <strong>Rekomendasi: {{ \App\Models\PenilaianEtik::labelRekomendasi($pe->rekomendasi) }}</strong>
                </div>
                @if ($pe->catatan)
                    <div style="color: #334155; font-style: italic; margin-top: 4px;">"{{ $pe->catatan }}"</div>
                @endif
            </div>
        @endforeach
    @else
        <div style="color: #94a3b8; font-style: italic; font-size: 10px; margin-bottom: 10px;">Belum ada ulasan resmi dari Asesor Penilai.</div>
    @endif

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div style="font-weight: bold; margin-bottom: 45px;">Ketua KEPK Pemohon</div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $surat->user->name }}</div>
                <div style="color: #64748b; font-size: 9.5px;">NIDN / NIP: ........................................</div>
            </td>
            <td>
                <div>Yogyakarta, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-bottom: 45px;">Tim Asesor Akreditasi</div>
                <div style="font-weight: bold; text-decoration: underline;">
                    {{ $surat->penilai->first()?->name ?? '( ...................................................... )' }}
                </div>
                <div style="color: #64748b; font-size: 9.5px;">Asesor Akreditasi KEPK</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Evaluasi dan Akreditasi KEPK (SEKAR-MU UMY) • Halaman 1
    </div>

</body>
</html>
