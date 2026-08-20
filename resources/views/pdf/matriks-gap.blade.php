<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Matriks Komparasi Gap — #APP-{{ str_pad($surat->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 12mm 10mm 12mm 10mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5px;
            color: #1e293b;
            line-height: 1.25;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #174668;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .header-logo {
            font-size: 20px;
            text-align: center;
            width: 35px;
        }
        .header-title {
            font-size: 12px;
            font-weight: bold;
            color: #174668;
            text-transform: uppercase;
        }
        .doc-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin: 6px 0;
            padding: 4px;
            background-color: #f1f5f9;
            border-radius: 3px;
        }
        .section-header {
            background-color: #174668;
            color: #ffffff;
            font-weight: bold;
            font-size: 9px;
            padding: 4px 6px;
            margin-top: 10px;
            margin-bottom: 4px;
            border-radius: 2px;
            page-break-after: avoid;
        }
        table.matrix-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        table.matrix-table th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 3.5px 4px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        table.matrix-table td {
            padding: 3px 4px;
            border: 1px solid #cbd5e1;
            font-size: 8px;
            vertical-align: top;
        }
        .score-a { color: #059669; font-weight: bold; }
        .score-b { color: #d97706; font-weight: bold; }
        .score-c { color: #e11d48; font-weight: bold; }
        .score-d { color: #64748b; font-weight: bold; }
        .gap-alert { color: #be123c; font-weight: bold; background-color: #fff1f2; padding: 1px 3px; border-radius: 2px; }
        .gap-ok { color: #059669; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">🌸</td>
            <td>
                <div class="header-title">SEKAR-MU — Matriks Komparasi Evaluasi Mandiri vs Asesor (Gap Analysis)</div>
                <div style="font-size: 8.5px; color: #64748b;">Institusi: {{ $surat->formulirAplikasi->nama_institusi ?? $surat->kepk->name }} • Dicetak: {{ $printedAt }}</div>
            </td>
            <td style="text-align: right; font-size: 8px; color: #64748b;">
                <strong>Kepatuhan:</strong> {{ $metrics['overall_compliance'] }}% |
                <strong>Prediksi:</strong> {{ $metrics['prediction']['type'] }} |
                <strong>Total Gap:</strong> {{ $matrix['total_gaps'] }} butir
            </td>
        </tr>
    </table>

    <div class="doc-title">
        Matriks Perbandingan Skor Evaluasi Internal KEPK vs Asesmen Independen Asesor (164 Butir)
    </div>

    @foreach ($matrix['sections'] as $secKode => $secData)
        <div class="section-header">
            BAGIAN {{ $secKode }}: {{ strtoupper($secData['section_name']) }}
        </div>

        <table class="matrix-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">Kode</th>
                    <th style="width: 32%;">Kriteria Standar</th>
                    <th style="width: 20%;">Bukti Dokumen KEPK</th>
                    <th style="width: 6%; text-align: center;">Skor KEPK</th>
                    <th style="width: 6%; text-align: center;">Skor Asesor</th>
                    <th style="width: 9%; text-align: center;">Status Selisih (Gap)</th>
                    <th style="width: 22%;">Temuan & Catatan Asesor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($secData['items'] as $item)
                    <tr>
                        <td style="text-align: center; font-weight: bold; color: #174668;">
                            {{ $item['kode_butir'] }}
                        </td>
                        <td>
                            @if($item['is_critical'])
                                <span style="color: #be123c; font-weight: bold; font-size: 7px;">[KRITIS]</span>
                            @endif
                            {{ $item['pertanyaan'] }}
                        </td>
                        <td>
                            {{ $item['self_bukti'] ?? '-' }}
                        </td>
                        <td style="text-align: center;">
                            <span class="score-{{ strtolower($item['self_score']) }}">{{ $item['self_score'] }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="score-{{ strtolower($item['assessor_score']) }}">{{ $item['assessor_score'] }}</span>
                        </td>
                        <td style="text-align: center;">
                            @if($item['has_gap'])
                                <span class="gap-alert">{{ $item['gap_label'] }}</span>
                            @else
                                <span class="gap-ok">✓ Sesuai</span>
                            @endif
                        </td>
                        <td>
                            @if($item['assessor_temuan'])
                                <div style="color: #be123c; font-weight: 600;">Temuan: {{ $item['assessor_temuan'] }}</div>
                            @endif
                            @if($item['assessor_catatan'])
                                <div style="color: #475569; font-style: italic;">Saran: {{ $item['assessor_catatan'] }}</div>
                            @endif
                            @if(!$item['assessor_temuan'] && !$item['assessor_catatan'])
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
