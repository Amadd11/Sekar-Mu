<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Instrumen Evaluasi Diri 164 Butir — #APP-{{ str_pad($surat->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 15mm 12mm 15mm 12mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.3;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #174668;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .header-logo {
            font-size: 24px;
            text-align: center;
            width: 40px;
        }
        .header-title {
            font-size: 13px;
            font-weight: bold;
            color: #174668;
            text-transform: uppercase;
        }
        .doc-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin: 10px 0;
            padding: 5px;
            background-color: #f1f5f9;
            border-radius: 4px;
        }
        .section-header {
            background-color: #174668;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            padding: 5px 8px;
            margin-top: 15px;
            margin-bottom: 5px;
            border-radius: 3px;
            page-break-after: avoid;
        }
        .group-header {
            background-color: #e2e8f0;
            color: #1e293b;
            font-weight: bold;
            font-size: 9px;
            padding: 4px 6px;
            margin-top: 8px;
            margin-bottom: 4px;
            border-radius: 2px;
            page-break-after: avoid;
        }
        table.item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            page-break-inside: auto;
        }
        table.item-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        table.item-table th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 5px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        table.item-table td {
            padding: 4px 5px;
            border: 1px solid #cbd5e1;
            font-size: 8.5px;
            vertical-align: top;
        }
        .badge-score {
            display: inline-block;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8.5px;
            text-align: center;
        }
        .score-a { background-color: #d1fae5; color: #065f46; }
        .score-b { background-color: #fef3c7; color: #92400e; }
        .score-c { background-color: #ffe4e6; color: #9f1239; }
        .score-d { background-color: #e2e8f0; color: #475569; }
        .badge-critical {
            background-color: #ffe4e6;
            color: #be123c;
            font-weight: bold;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7.5px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">🌸</td>
            <td>
                <div class="header-title">SEKAR-MU — Instrumen Evaluasi Diri 164 Butir KNEPK</div>
                <div style="font-size: 9px; color: #64748b;">{{ $surat->formulirAplikasi->nama_institusi ?? $surat->kepk->name }} • Dicetak: {{ $printedAt }}</div>
            </td>
            <td style="text-align: right; font-size: 8.5px; color: #64748b;">
                <strong>Kepatuhan:</strong> {{ $metrics['overall_compliance'] }}%<br>
                <strong>Prediksi:</strong> {{ $metrics['prediction']['type'] }}
            </td>
        </tr>
    </table>

    <div class="doc-title">
        Borang Evaluasi Mandiri 164 Butir Akreditasi KEPK (B01-03)
    </div>

    @foreach ($bagianList as $bagian)
        <div class="section-header">
            BAGIAN {{ $bagian->kode }}: {{ strtoupper($bagian->nama) }}
        </div>

        @foreach ($bagian->kelompok as $kelompok)
            <div class="group-header">
                {{ $kelompok->nama }}
            </div>

            <table class="item-table">
                <thead>
                    <tr>
                        <th style="width: 7%; text-align: center;">Kode</th>
                        <th style="width: 43%;">Kriteria & Acuan Standar</th>
                        <th style="width: 25%;">Bukti Dokumen / File Terlampir</th>
                        <th style="width: 17%;">Uraian Implementasi KEPK</th>
                        <th style="width: 8%; text-align: center;">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelompok->butir as $b)
                        @php
                            $ans = $selfAnswers->get($b->id);
                            $ass = $assessorScores->get($b->id);
                            $effectiveScore = $ass?->skor ?? $ans?->skor ?? '-';
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: bold; color: #174668;">
                                {{ $bagian->kode }}.{{ $b->urutan }}
                            </td>
                            <td>
                                @if($b->is_critical)
                                    <span class="badge-critical">⚠️ KRITIS</span>
                                @endif
                                <div style="font-weight: 600; margin-top: 1px;">{{ $b->pertanyaan }}</div>
                                @if($b->evidence_required)
                                    <div style="color: #64748b; font-style: italic; font-size: 7.5px; margin-top: 2px;">
                                        📌 Acuan: {{ $b->evidence_required }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($ans?->bukti)
                                    <div><strong>Bukti:</strong> {{ $ans->bukti }}</div>
                                @endif
                                @if($ans?->file_name)
                                    <div style="color: #059669; font-weight: bold; margin-top: 2px;">
                                        📄 {{ $ans->file_name }} ({{ $ans->formatUkuran() }})
                                    </div>
                                @endif
                                @if(!$ans?->bukti && !$ans?->file_name)
                                    <span style="color: #94a3b8; font-style: italic;">Belum dilampirkan</span>
                                @endif
                            </td>
                            <td>
                                {{ $ans?->catatan ?? '-' }}
                            </td>
                            <td style="text-align: center;">
                                @if($effectiveScore === 'A')
                                    <span class="badge-score score-a">A (100)</span>
                                @elseif($effectiveScore === 'B')
                                    <span class="badge-score score-b">B (50)</span>
                                @elseif($effectiveScore === 'C')
                                    <span class="badge-score score-c">C (0)</span>
                                @elseif($effectiveScore === 'D')
                                    <span class="badge-score score-d">D (N/A)</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach

</body>
</html>
