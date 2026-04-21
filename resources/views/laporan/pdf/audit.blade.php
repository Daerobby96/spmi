<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Audit</title>
    <style>
        @page {
            margin: 30mm 30mm 30mm 40mm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
            line-height: 1.5;
            color: #333;
        }

        /* ===== WRAPPER UTAMA ===== */
        .wrapper {
            padding: 0;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .header .subtitle {
            font-size: 9.5pt;
            color: #555;
            margin-bottom: 6px;
        }

        .header .meta {
            font-size: 8pt;
            color: #888;
        }

        /* ===== SECTION ===== */
        .section {
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            background: #f0f2f5;
            padding: 6px 10px;
            margin-bottom: 8px;
            border-left: 4px solid #4e73df;
            color: #2c3e50;
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .stats-grid td {
            width: 25%;
            text-align: center;
            padding: 10px 6px;
            border: 1px solid #dde1e7;
            background: #f8f9fc;
        }

        .stat-value {
            font-size: 18pt;
            font-weight: bold;
            color: #4e73df;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 7pt;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 3px;
        }

        /* ===== TWO COLUMN ===== */
        .two-column {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .two-column td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .two-column td:first-child {
            padding-right: 8px;
        }

        .two-column td:last-child {
            padding-left: 8px;
        }

        /* ===== TABLE ===== */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #cdd2d8;
            padding: 5px 7px;
            text-align: left;
            font-size: 8pt;
        }

        table.data-table th {
            background: #f0f2f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7pt;
            letter-spacing: 0.4px;
            color: #444;
        }

        table.data-table tr:nth-child(even) td {
            background: #fafbfc;
        }

        table.data-table tr.total-row td {
            background: #f0f2f5;
            font-weight: bold;
        }

        /* ===== COLGROUP DAFTAR AUDIT ===== */
        col.col-no     { width: 4%; }
        col.col-kode   { width: 10%; }
        col.col-nama   { width: 22%; }
        col.col-unit   { width: 18%; }
        col.col-auditor{ width: 16%; }
        col.col-tgl    { width: 12%; }
        col.col-temuan { width: 8%; }
        col.col-status { width: 10%; }

        /* ===== BADGE ===== */
        .badge {
            padding: 1px 5px;
            border-radius: 2px;
            font-size: 7pt;
            font-weight: bold;
        }

        .badge-success   { background: #d4edda; color: #155724; }
        .badge-warning   { background: #fff3cd; color: #856404; }
        .badge-danger    { background: #f8d7da; color: #721c24; }
        .badge-info      { background: #d1ecf1; color: #0c5460; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }

        /* ===== UTILITY ===== */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-muted  { color: #999; }
        .fw-bold     { font-weight: bold; }

        /* ===== PAGE BREAK ===== */
        tr { page-break-inside: avoid; }
        .section { page-break-inside: avoid; }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 7pt;
            color: #aaa;
            border-top: 1px solid #dde1e7;
            padding-top: 8px;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="header">
        <h1>Laporan Audit Mutu Internal</h1>
        <div class="subtitle">Sistem Penjaminan Mutu Internal (SPMI)</div>
        @if($periode)
            <div class="meta">Periode: {{ $periode->nama }} ({{ $periode->tahun }} &mdash; {{ ucfirst($periode->semester) }})</div>
        @endif
        <div class="meta">Dicetak: {{ now()->format('d F Y H:i') }}</div>
    </div>

    {{-- ===== RINGKASAN AUDIT ===== --}}
    <div class="section">
        <div class="section-title">Ringkasan Audit</div>
        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stat-value">{{ $audits->count() }}</div>
                    <div class="stat-label">Total Audit</div>
                </td>
                <td>
                    <div class="stat-value">{{ $audits->where('status', 'selesai')->count() }}</div>
                    <div class="stat-label">Selesai</div>
                </td>
                <td>
                    <div class="stat-value">{{ $audits->where('status', 'aktif')->count() }}</div>
                    <div class="stat-label">Berjalan</div>
                </td>
                <td>
                    <div class="stat-value">{{ $audits->sum(fn($a) => $a->temuans->count()) }}</div>
                    <div class="stat-label">Total Temuan</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== TWO COLUMN: TEMUAN PER KATEGORI & STATUS AUDIT ===== --}}
    <table class="two-column">
        <tr>
            {{-- Temuan per Kategori --}}
            <td>
                <div class="section-title">Temuan per Kategori</div>
                @php
                    $totalTemuan = $temuanPerKategori->sum();
                    $kategoriLabels = [
                        'KTS_Mayor'    => 'KTS Mayor',
                        'KTS_Minor'    => 'KTS Minor',
                        'OB'           => 'Observasi',
                        'Rekomendasi'  => 'Rekomendasi',
                    ];
                @endphp
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($temuanPerKategori as $kategori => $jumlah)
                            <tr>
                                <td>{{ $kategoriLabels[$kategori] ?? $kategori }}</td>
                                <td class="text-center">{{ $jumlah }}</td>
                                <td class="text-center">
                                    {{ $totalTemuan > 0 ? round(($jumlah / $totalTemuan) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td>Total</td>
                            <td class="text-center">{{ $totalTemuan }}</td>
                            <td class="text-center">100%</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            {{-- Status Audit --}}
            <td>
                <div class="section-title">Status Audit</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge badge-secondary">Draft</span></td>
                            <td class="text-center">{{ $audits->where('status', 'draft')->count() }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-warning">Aktif</span></td>
                            <td class="text-center">{{ $audits->where('status', 'aktif')->count() }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-success">Selesai</span></td>
                            <td class="text-center">{{ $audits->where('status', 'selesai')->count() }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-info">Ditutup</span></td>
                            <td class="text-center">{{ $audits->where('status', 'ditutup')->count() }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== RINCIAN TEMUAN PER KATEGORI ===== --}}
    <div class="section">
        <div class="section-title">Rincian Temuan per Kategori</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Kategori</th>
                    <th style="width: 15%;" class="text-center">Jumlah</th>
                    <th style="width: 55%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $kategoriLabels = [
                        'KTS_Mayor'   => ['label' => 'KTS Mayor', 'desc' => 'Ketidaksesuaian kritis yang berdampak signifikan'],
                        'KTS_Minor'   => ['label' => 'KTS Minor', 'desc' => 'Ketidaksesuaian non-kritis'],
                        'OB'          => ['label' => 'Observasi', 'desc' => 'Temuan yang perlu menjadi perhatian'],
                        'Rekomendasi' => ['label' => 'Rekomendasi', 'desc' => 'Saran untuk peningkatan berkelanjutan'],
                    ];
                    $totalTemuan = $temuanPerKategori->sum();
                    $no = 1;
                @endphp
                @foreach($kategoriLabels as $key => $info)
                    @php $jumlah = $temuanPerKategori[$key] ?? 0; @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $info['label'] }}</td>
                        <td class="text-center fw-bold">{{ $jumlah }}</td>
                        <td>{{ $info['desc'] }}</td>
                    </tr>
                @endforeach
                <tr style="background: #f0f2f5; font-weight: bold;">
                    <td colspan="2" class="text-right">TOTAL TEMUAN</td>
                    <td class="text-center">{{ $totalTemuan }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ===== DAFTAR AUDIT ===== --}}
    <div class="section">
        <div class="section-title">Daftar Audit Detail</div>
        <table class="data-table">
            <colgroup>
                <col class="col-no">
                <col class="col-kode">
                <col class="col-nama">
                <col class="col-unit">
                <col class="col-auditor">
                <col class="col-tgl">
                <col class="col-temuan">
                <col class="col-status">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Kode</th>
                    <th>Nama Audit</th>
                    <th>Unit Diaudit</th>
                    <th>Ketua Auditor</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Temuan</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $i => $audit)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $audit->kode_audit }}</td>
                        <td>{{ $audit->nama_audit }}</td>
                        <td>{{ $audit->unit_yang_diaudit }}</td>
                        <td>{{ $audit->ketuaAuditor->name ?? '-' }}</td>
                        <td class="text-center">{{ $audit->tanggal_audit->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @php
                                $major = $audit->temuans->where('kategori', 'KTS_Mayor')->count();
                                $minor = $audit->temuans->where('kategori', 'KTS_Minor')->count();
                                $obs = $audit->temuans->where('kategori', 'OB')->count();
                                $rec = $audit->temuans->where('kategori', 'Rekomendasi')->count();
                            @endphp
                            @if($major > 0 || $minor > 0 || $obs > 0 || $rec > 0)
                                {{ $audit->temuans->count() }}
                                @if($major > 0)<br><small style="color: #721c24;">({{ $major }} Mayor)</small>@endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($audit->status === 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($audit->status === 'aktif')
                                <span class="badge badge-warning">Aktif</span>
                            @elseif($audit->status === 'ditutup')
                                <span class="badge badge-info">Ditutup</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data audit</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== INFORMASI TAMBAHAN ===== --}}
    <div class="section">
        <div class="section-title">Keterangan</div>
        <div style="font-size: 8pt; line-height: 1.8;">
            <p><strong>KTS Mayor:</strong> Ketidaksesuaian Mayor adalah temuan kritis yang berdampak signifikan terhadap mutu penyelenggaraan pendidikan dan memerlukan perhatian serius serta penanganan segera.</p>
            <p><strong>KTS Minor:</strong> Ketidaksesuaian Minor adalah ketidaksesuaian yang tidak bersifat kritis namun tetap perlu ditindaklanjuti untuk mencegah berkembang menjadi masalah yang lebih besar.</p>
            <p><strong>Observasi:</strong> Temuan yang menunjukkan adanya potensi ketidaksesuaian atau area yang perlu menjadi perhatian untuk perbaikan berkelanjutan.</p>
            <p><strong>Rekomendasi:</strong> Saran dari auditor untuk peningkatan kualitas yang tidak bersifat wajib namun direkomendasikan untuk diterapkan.</p>
        </div>
    </div>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        <p>Dokumen digenerate oleh Sistem Penjaminan Mutu Internal (SPMI)</p>
        <p>{{ now()->format('d F Y') }} &nbsp;|&nbsp; {{ now()->format('H:i:s') }}</p>
    </div>

</div>
</body>
</html>