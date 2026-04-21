<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring IKU/IKT</title>
    <style>
        @page {
            margin-top: 3cm;
            margin-bottom: 3cm;
            margin-left: 3cm;
            margin-right: 3cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #333;
            margin-left: 30mm;
            margin-right: 30mm;
            margin-bottom: 30mm;
        }

        /* ===== COVER PAGE ===== */
        .cover {
            text-align: center;
            padding: 20mm 0 30mm 0;
            page-break-after: always;
        }

        .cover-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px auto;
            border: none;
            display: block;
            text-align: center;
        }
        .cover-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .cover-logo-placeholder {
            width: 100px;
            height: 100px;
            border: 2px solid #2c3e50;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12pt;
            color: #666;
        }

        .cover-institution {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .cover-address {
            font-size: 9pt;
            color: #666;
            margin-bottom: 60px;
        }

        .cover-title-box {
            border: 3px solid #2c3e50;
            padding: 30px 40px;
            margin: 40px 60px;
        }

        .cover-label {
            font-size: 11pt;
            margin-bottom: 15px;
            color: #555;
        }

        .cover-title {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .cover-subtitle {
            font-size: 12pt;
            color: #555;
        }

        .cover-info {
            margin-top: 40px;
            text-align: left;
            padding: 5px 40px;
        }

        .cover-info table {
            width: 100%;
            border-collapse: collapse;
            padding: 5px 10px;
        }

        .cover-info td {
            padding: 5px 0;
            font-size: 10pt;
        }

        .cover-info td:first-child {
            width: 35%;
            font-weight: bold;
        }

        .cover-info td:last-child {
            width: 65%;
        }

        .cover-date {
            margin-top: 60px;
            font-size: 10pt;
        }

        /* ===== HALAMAN PENGESAHAN ===== */
        .pengesahan {
            padding: 20mm 0 30mm 0;
            page-break-after: always;
        }

        .pengesahan-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .pengesahan-content {
            margin: 0 20px;
        }

        .pengesahan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .pengesahan-table td {
            padding: 5px 10px;
            vertical-align: top;
            border-bottom: 1px solid #ddd;
        }

        .pengesahan-table td:first-child {
            width: 40%;
            font-weight: bold;
        }

        .ttd-section {
            margin-top: 60px;
            width: 100%;
        }

        .ttd-row {
            width: 100%;
            margin-bottom: 40px;
        }

        .ttd-row::after {
            content: "";
            clear: both;
            display: table;
        }

        .ttd-col-left {
            float: left;
            width: 45%;
            text-align: center;
        }

        .ttd-col-right {
            float: right;
            width: 45%;
            text-align: center;
        }

        .ttd-col-center {
            width: 60%;
            margin: 0 auto;
            text-align: center;
        }

        .ttd-label {
            font-size: 9pt;
            margin-bottom: 60px;
            min-height: 40px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .ttd-jabatan {
            font-size: 9pt;
            color: #666;
        }

        .ttd-line {
            border-bottom: 1px solid #333;
            width: 80%;
            margin: 0 auto 5px auto;
            min-height: 18px;
        }

        /* ===== CONTENT PAGES ===== */
        .content {
            padding: 10mm 0 30mm 0;
            page-break-before: auto;
        }

        .content:last-of-type {
            page-break-after: avoid;
        }

        /* ===== BAB HEADER ===== */
        .bab-header {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 10mm;
            page-break-before: always;
        }

        .bab-header:first-of-type {
            page-break-before: auto;
            margin-top: 10mm;
        }

        .bab-number {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .bab-title {
            font-size: 12pt;
            font-weight: bold;
            color: #444;
        }

        /* ===== SECTIONS ===== */
        .section {
            margin-bottom: 25px;
            margin-top: 15px;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            background: #f0f2f5;
            padding: 8px 12px;
            margin-bottom: 12px;
            border-left: 4px solid #17a2b8;
            color: #2c3e50;
        }

        .section-subtitle {
            font-size: 10pt;
            font-weight: bold;
            margin: 15px 0 10px 0;
            color: #444;
        }

        .section-content {
            text-align: justify;
            margin-bottom: 15px;
        }

        /* ===== TABLES ===== */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9pt;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #cdd2d8;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        table.data-table th {
            background: #f0f2f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.5px;
            color: #444;
        }

        table.data-table tr:nth-child(even) td {
            background: #fafbfc;
        }

        table.data-table tr.total-row td {
            background: #f0f2f5;
            font-weight: bold;
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .stats-grid td {
            width: 25%;
            text-align: center;
            padding: 15px 10px;
            border: 1px solid #dde1e7;
            background: #f8f9fc;
        }

        .stat-value {
            font-size: 20pt;
            font-weight: bold;
            color: #17a2b8;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 8pt;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 5px;
        }

        /* ===== INFO TABLE ===== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 30%;
            background: #f8f9fa;
            font-weight: bold;
            font-size: 9pt;
        }

        .info-table td:last-child {
            width: 70%;
            font-size: 9pt;
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }

        .badge-success   { background: #d4edda; color: #155724; }
        .badge-warning   { background: #fff3cd; color: #856404; }
        .badge-danger    { background: #f8d7da; color: #721c24; }
        .badge-info      { background: #d1ecf1; color: #0c5460; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }

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

        /* ===== KESIMPULAN BOX ===== */
        .kesimpulan-box {
            border: 2px solid #17a2b8;
            padding: 20px;
            margin: 20px 0;
            background: #f0f9fb;
        }

        .kesimpulan-title {
            font-weight: bold;
            color: #17a2b8;
            margin-bottom: 10px;
        }

        /* ===== UTILITIES ===== */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-muted { color: #666; }
        .fw-bold { font-weight: bold; }
        .mb-0 { margin-bottom: 0; }
        .mt-3 { margin-top: 15px; }

        /* ===== PAGE BREAK ===== */
        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* ===== LIST STYLES ===== */
        ul.numbered {
            list-style: decimal;
            padding-left: 25px;
            margin-bottom: 15px;
        }

        ul.numbered li {
            margin-bottom: 5px;
            text-align: justify;
        }

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

    {{-- ==================== COVER PAGE ==================== --}}
    <div class="cover">
        <div class="cover-logo">
            @if(!empty($setting['logo_institusi']) && file_exists(storage_path('app/public/' . $setting['logo_institusi'])))
                <img src="{{ storage_path('app/public/' . $setting['logo_institusi']) }}" alt="Logo" style="max-width: 100px; max-height: 100px;">
            @else
                <div class="cover-logo-placeholder">LOGO PT</div>
            @endif
        </div>
        
        <div class="cover-institution">
            {{ $setting['nama_institusi'] ?? 'NAMA PERGURUAN TINGGI' }}
        </div>
        <div class="cover-address">
            {{ $setting['alamat_institusi'] ?? 'Alamat Institusi' }}
        </div>

        <div class="cover-title-box">
            <div class="cover-label">LAPORAN</div>
            <div class="cover-title">MONITORING IKU/IKT</div>
            <div class="cover-subtitle">(Indikator Kinerja Utama / Indikator Kinerja Tambahan)</div>
        </div>

              <div class="cover-date">
            <p>Dokumen ini adalah laporan hasil monitoring capaian indikator kinerja<br>
            Sistem Penjaminan Mutu Internal (SPMI) Perguruan Tinggi</p>
            <p style="margin-top: 20px;">{{ $setting['kota_institusi'] ?? 'Kota' }}, {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    {{-- ==================== HALAMAN PENGESAHAN ==================== --}}
    <div class="pengesahan">
        <div class="pengesahan-title">LEMBAR PENGESAHAN</div>
        
        <div class="pengesahan-content">
            <table class="pengesahan-table">
                <tr>
                    <td>Jenis Laporan</td>
                    <td>Laporan Monitoring IKU/IKT</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>{{ $periode->nama ?? '-' }} ({{ $periode->tahun ?? '-' }})</td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>{{ ucfirst($periode->semester ?? '-') }}</td>
                </tr>
                <tr>
                    <td>Total Indikator Dimonitoring</td>
                    <td>{{ $stats['total'] }} Indikator</td>
                </tr>
                <tr>
                    <td>Indikator Tercapai</td>
                    <td>{{ $stats['tercapai'] }} Indikator</td>
                </tr>
                <tr>
                    <td>Indikator Tidak Tercapai</td>
                    <td>{{ $stats['tidak_tercapai'] }} Indikator</td>
                </tr>
                <tr>
                    <td>Tanggal Penyusunan</td>
                    <td>{{ now()->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
            </table>

            <div class="ttd-section">
                <div class="ttd-row">
                    <div class="ttd-col-left">
                        <div class="ttd-label">{{ $ketua_spmi->jabatan ?? 'Ketua SPMI' }}</div>
                        <div class="ttd-nama">{{ $ketua_spmi->name ?? '.........................' }}</div>
                        <div class="ttd-jabatan">NIP/NIK: {{ $ketua_spmi->nip ?? '.........................' }}</div>
                    </div>
                    
                    <div class="ttd-col-right">
                        <div class="ttd-label">{{ $koordinator_monitoring->jabatan ?? 'Koordinator Monitoring' }}</div>
                        <div class="ttd-nama">{{ $koordinator_monitoring->name ?? '.........................' }}</div>
                        <div class="ttd-jabatan">NIP/NIK: {{ $koordinator_monitoring->nip ?? '.........................' }}</div>
                    </div>
                </div>
            </div>

            <div class="ttd-section">
                <div class="ttd-col-center">
                    <div class="ttd-label">Mengetahui,<br>{{ $kepala_institusi->jabatan ?? 'Kepala Perguruan Tinggi' }}</div>
                    <div class="ttd-nama">{{ $kepala_institusi->name ?? '.........................' }}</div>
                    <div class="ttd-jabatan">NIP/NIK: {{ $kepala_institusi->nip ?? '.........................' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== BAB I - PENDAHULUAN ==================== --}}
    <div class="content">
        <div class="bab-header">
            <div class="bab-number">BAB I</div>
            <div class="bab-title">PENDAHULUAN</div>
        </div>

        <div class="section">
            <div class="section-title">1.1 Latar Belakang</div>
            <div class="section-content">
                Sistem Penjaminan Mutu Internal (SPMI) merupakan sistem yang dikembangkan oleh Perguruan Tinggi 
                untuk menjamin penyelenggaraan pendidikan tinggi secara berkala dan berkelanjutan sesuai dengan 
                standar yang ditetapkan. Monitoring Indikator Kinerja Utama (IKU) dan Indikator Kinerja Tambahan (IKT) 
                merupakan bagian integral dari SPMI untuk mengukur capaian kinerja perguruan tinggi.
            </div>
            <div class="section-content">
                Laporan monitoring ini disusun untuk melakukan evaluasi terhadap capaian indikator kinerja pada 
                periode {{ $periode->nama ?? '-' }} tahun {{ $periode->tahun ?? '-' }}. Laporan ini mencakup 
                pengukuran, analisis, dan evaluasi terhadap {{ $stats['total'] }} indikator kinerja yang telah ditetapkan.
            </div>
        </div>

        <div class="section">
            <div class="section-title">1.2 Tujuan</div>
            <div class="section-content">
                Tujuan dari penyusunan laporan monitoring ini adalah:
                <ul class="numbered">
                    <li>Mengukur dan mengevaluasi capaian indikator kinerja (IKU/IKT) periode berjalan;</li>
                    <li>Mengidentifikasi indikator yang tercapai, tidak tercapai, dan perlu perhatian khusus;</li>
                    <li>Memberikan rekomendasi untuk peningkatan capaian indikator pada periode berikutnya;</li>
                    <li>Menyediakan informasi untuk pengambilan keputusan dan perencanaan strategis.</li>
                </ul>
            </div>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">1.3 Ruang Lingkup</div>
            <div class="section-content">
                Ruang lingkup monitoring ini mencakup seluruh indikator kinerja yang telah ditetapkan dalam 
                Sistem Penjaminan Mutu Internal {{ $setting['nama_institusi'] ?? 'Perguruan Tinggi' }}, meliputi:
                <ul class="numbered">
                    <li>Indikator Kinerja Utama (IKU) bidang pendidikan, penelitian, dan pengabdian;</li>
                    <li>Indikator Kinerja Tambahan (IKT) sesuai kebutuhan institusi;</li>
                    <li>Capaian target dan realisasi kinerja per unit kerja.</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <div class="section-title">1.4 Periode Monitoring</div>
            <table class="info-table">
                <tr>
                    <td>Periode</td>
                    <td>{{ $periode->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tahun Akademik</td>
                    <td>{{ $periode->tahun ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>{{ ucfirst($periode->semester ?? '-') }}</td>
                </tr>
                <tr>
                    <td>Tanggal Penyusunan</td>
                    <td>{{ now()->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ==================== BAB II - HASIL MONITORING ==================== --}}
    <div class="content" style="page-break-before: always;">
        <div class="bab-header">
            <div class="bab-number">BAB II</div>
            <div class="bab-title">HASIL MONITORING</div>
        </div>

        <div class="section">
            <div class="section-title">2.1 Ringkasan Capaian Indikator</div>
            <div class="section-content">
                Berikut adalah ringkasan hasil monitoring capaian indikator kinerja pada periode ini:
            </div>
            
            <table class="stats-grid">
                <tr>
                    <td>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Indikator</div>
                    </td>
                    <td>
                        <div class="stat-value" style="color: #28a745;">{{ $stats['tercapai'] }}</div>
                        <div class="stat-label">Tercapai</div>
                    </td>
                    <td>
                        <div class="stat-value" style="color: #dc3545;">{{ $stats['tidak_tercapai'] }}</div>
                        <div class="stat-label">Tidak Tercapai</div>
                    </td>
                    <td>
                        <div class="stat-value" style="color: #ffc107;">{{ $stats['perlu_perhatian'] }}</div>
                        <div class="stat-label">Perlu Perhatian</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">2.2 Distribusi Hasil Evaluasi</div>
            <div class="section-content">
                Distribusi hasil evaluasi indikator kinerja berdasarkan kategori capaian:
            </div>
            
            @php
                $totalEvaluasi = $hasilEvaluasi->sum();
                $persenTercapai = $stats['total'] > 0 ? round(($stats['tercapai'] / $stats['total']) * 100, 1) : 0;
                $persenTidakTercapai = $stats['total'] > 0 ? round(($stats['tidak_tercapai'] / $stats['total']) * 100, 1) : 0;
                $persenPerhatian = $stats['total'] > 0 ? round(($stats['perlu_perhatian'] / $stats['total']) * 100, 1) : 0;
                $belumDievaluasi = $stats['total'] - $totalEvaluasi;
                $persenBelum = $stats['total'] > 0 ? round(($belumDievaluasi / $stats['total']) * 100, 1) : 0;
            @endphp

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Persentase</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge badge-success">Tercapai</span></td>
                        <td class="text-center">{{ $stats['tercapai'] }}</td>
                        <td class="text-center">{{ $persenTercapai }}%</td>
                        <td>Indikator yang mencapai atau melampaui target</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-danger">Tidak Tercapai</span></td>
                        <td class="text-center">{{ $stats['tidak_tercapai'] }}</td>
                        <td class="text-center">{{ $persenTidakTercapai }}%</td>
                        <td>Indikator yang tidak mencapai target</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-warning">Perlu Perhatian</span></td>
                        <td class="text-center">{{ $stats['perlu_perhatian'] }}</td>
                        <td class="text-center">{{ $persenPerhatian }}%</td>
                        <td>Indikator yang memerlukan perhatian khusus</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-secondary">Belum Dievaluasi</span></td>
                        <td class="text-center">{{ $belumDievaluasi }}</td>
                        <td class="text-center">{{ $persenBelum }}%</td>
                        <td>Indikator yang belum dilakukan evaluasi</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $stats['total'] }}</strong></td>
                        <td class="text-center"><strong>100%</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">2.3 Status Monitoring</div>
            <div class="section-content">
                Status pengumpulan data monitoring berdasarkan tahapan:
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Persentase</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge badge-secondary">Draft</span></td>
                        <td class="text-center">{{ $stats['draft'] }}</td>
                        <td class="text-center">{{ $stats['total'] > 0 ? round(($stats['draft'] / $stats['total']) * 100, 1) : 0 }}%</td>
                        <td>Data masih dalam tahap penyusunan</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-info">Submitted</span></td>
                        <td class="text-center">{{ $stats['submitted'] }}</td>
                        <td class="text-center">{{ $stats['total'] > 0 ? round(($stats['submitted'] / $stats['total']) * 100, 1) : 0 }}%</td>
                        <td>Data sudah disubmit untuk evaluasi</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-success">Verified</span></td>
                        <td class="text-center">{{ $stats['verified'] }}</td>
                        <td class="text-center">{{ $stats['total'] > 0 ? round(($stats['verified'] / $stats['total']) * 100, 1) : 0 }}%</td>
                        <td>Data sudah terverifikasi</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">2.4 Detail Capaian Indikator</div>
            <div class="section-content">
                Rincian lengkap capaian setiap indikator kinerja adalah sebagai berikut:
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 25%;">Indikator</th>
                        <th style="width: 15%;">Unit Kerja</th>
                        <th class="text-center" style="width: 12%;">Target</th>
                        <th class="text-center" style="width: 12%;">Capaian</th>
                        <th class="text-center" style="width: 10%;">%</th>
                        <th class="text-center" style="width: 15%;">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monitorings as $i => $m)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>
                                <div>{{ Str::limit($m->indikator->nama ?? '-', 40) }}</div>
                                <span style="font-size: 7pt; color: #666;">{{ $m->indikator->kode ?? '-' }}</span>
                            </td>
                            <td>{{ $m->indikator->unit_kerja ?? '-' }}</td>
                            <td class="text-center">
                                {{ $m->indikator->target_nilai ?? '-' }} {{ $m->indikator->unit_pengukuran ?? '' }}
                            </td>
                            <td class="text-center">{{ $m->nilai_capaian ?? '-' }}</td>
                            <td class="text-center">
                                @php $persen = $m->persentase_capaian; @endphp
                                @if($persen >= 100)
                                    <span class="badge badge-success">{{ number_format($persen, 0) }}%</span>
                                @elseif($persen >= 80)
                                    <span class="badge badge-warning">{{ number_format($persen, 0) }}%</span>
                                @else
                                    <span class="badge badge-danger">{{ number_format($persen, 0) }}%</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($m->evaluasi)
                                    @if($m->evaluasi->hasil === 'tercapai')
                                        <span class="badge badge-success">Tercapai</span>
                                    @elseif($m->evaluasi->hasil === 'tidak_tercapai')
                                        <span class="badge badge-danger">Tidak Tercapai</span>
                                    @else
                                        <span class="badge badge-warning">Perlu Perhatian</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">Belum</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data monitoring</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ==================== BAB III - ANALISIS DAN PEMBAHASAN ==================== --}}
    <div class="content" style="page-break-before: always;">
        <div class="bab-header">
            <div class="bab-number">BAB III</div>
            <div class="bab-title">ANALISIS DAN PEMBAHASAN</div>
        </div>

        <div class="section">
            <div class="section-title">3.1 Analisis Capaian Indikator</div>
            <div class="section-content">
                Berdasarkan hasil monitoring yang telah dilaksanakan, diperoleh gambaran capaian indikator 
                kinerja pada periode {{ $periode->nama ?? '-' }} tahun {{ $periode->tahun ?? '-' }}. 
                Berikut adalah analisis terhadap hasil monitoring:
            </div>

            @if($stats['tercapai'] > 0)
            <div class="section-subtitle">3.1.1 Indikator Tercapai</div>
            <div class="section-content">
                Terdapat <strong>{{ $stats['tercapai'] }} indikator ({{ $persenTercapai }}%)</strong> yang berhasil 
                mencapai atau melampaui target yang telah ditetapkan. Capaian ini menunjukkan bahwa sebagian 
                besar kegiatan prioritas telah berjalan dengan baik dan sesuai rencana.
            </div>
            @endif

            @if($stats['tidak_tercapai'] > 0)
            <div class="section-subtitle">3.1.2 Indikator Tidak Tercapai</div>
            <div class="section-content">
                Terdapat <strong>{{ $stats['tidak_tercapai'] }} indikator ({{ $persenTidakTercapai }}%)</strong> 
                yang tidak mencapai target. Indikator-indikator ini memerlukan perhatian serius dan evaluasi 
                mendalam untuk mengidentifikasi faktor-faktor penyebab ketidakcapaian.
            </div>
            @endif

            @if($stats['perlu_perhatian'] > 0)
            <div class="section-subtitle">3.1.3 Indikator Perlu Perhatian</div>
            <div class="section-content">
                Terdapat <strong>{{ $stats['perlu_perhatian'] }} indikator ({{ $persenPerhatian }}%)</strong> 
                yang memerlukan perhatian khusus. Meskipun belum dikategorikan sebagai tidak tercapai, 
                indikator-indikator ini menunjukkan tren yang perlu diwaspadai.
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">3.2 Faktor Pendorong dan Penghambat</div>
            <div class="section-content">
                Berdasarkan evaluasi yang dilakukan, teridentifikasi beberapa faktor yang mempengaruhi 
                capaian indikator kinerja:
            </div>
            
            <div class="section-subtitle">A. Faktor Pendorong</div>
            <ul class="numbered">
                <li>Komitmen manajemen dalam mendukung pencapaian target kinerja;</li>
                <li>Koordinasi yang baik antar unit kerja terkait;</li>
                <li>Ketersediaan sumber daya yang memadai;</li>
                <li>Sosialisasi dan pemahaman yang baik terhadap indikator kinerja.</li>
            </ul>

            <div class="section-subtitle">B. Faktor Penghambat</div>
            <ul class="numbered">
                <li>Keterbatasan anggaran untuk beberapa program prioritas;</li>
                <li>Keterlambatan dalam pelaksanaan kegiatan;</li>
                <li>Kurangnya koordinasi antar unit dalam pengumpulan data;</li>
                <li>Perubahan kebijakan yang mempengaruhi target indikator.</li>
            </ul>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">3.3 Evaluasi Proses Monitoring</div>
            <div class="section-content">
                Proses monitoring yang dilaksanakan menunjukkan tingkat partisipasi unit kerja 
                dalam pengumpulan data capaian indikator. Dari total {{ $stats['total'] }} indikator:
            </div>
            <table class="info-table">
                <tr>
                    <td>Sudah Terverifikasi</td>
                    <td>{{ $stats['verified'] }} indikator ({{ $stats['total'] > 0 ? round(($stats['verified'] / $stats['total']) * 100, 1) : 0 }}%)</td>
                </tr>
                <tr>
                    <td>Dalam Proses Evaluasi</td>
                    <td>{{ $stats['submitted'] }} indikator ({{ $stats['total'] > 0 ? round(($stats['submitted'] / $stats['total']) * 100, 1) : 0 }}%)</td>
                </tr>
                <tr>
                    <td>Belum Submit</td>
                    <td>{{ $stats['draft'] }} indikator ({{ $stats['total'] > 0 ? round(($stats['draft'] / $stats['total']) * 100, 1) : 0 }}%)</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ==================== BAB IV - KESIMPULAN DAN REKOMENDASI ==================== --}}
    <div class="content" style="page-break-before: always;">
        <div class="bab-header">
            <div class="bab-number">BAB IV</div>
            <div class="bab-title">KESIMPULAN DAN REKOMENDASI</div>
        </div>

        <div class="section">
            <div class="section-title">4.1 Kesimpulan</div>
            <div class="kesimpulan-box">
                <div class="kesimpulan-title">KESIMPULAN UMUM</div>
                <div class="section-content">
                    Monitoring IKU/IKT telah dilaksanakan untuk periode 
                    <strong>{{ $periode->nama ?? '-' }} tahun {{ $periode->tahun ?? '-' }}</strong>. 
                    Dari total <strong>{{ $stats['total'] }} indikator</strong> yang dimonitoring, 
                    diperoleh hasil sebagai berikut:
                    <ul class="numbered" style="margin-top: 10px;">
                        <li>{{ $stats['tercapai'] }} indikator tercapai ({{ $persenTercapai }}%)</li>
                        <li>{{ $stats['tidak_tercapai'] }} indikator tidak tercapai ({{ $persenTidakTercapai }}%)</li>
                        <li>{{ $stats['perlu_perhatian'] }} indikator perlu perhatian ({{ $persenPerhatian }}%)</li>
                        <li>{{ $belumDievaluasi }} indikator belum dievaluasi ({{ $persenBelum }}%)</li>
                    </ul>
                </div>
            </div>
            
            <div class="section-content">
                Secara umum, capaian kinerja {{ $setting['nama_institusi'] ?? 'Perguruan Tinggi' }} pada periode ini 
                {{ $persenTercapai >= 70 ? 'sudah cukup baik dengan tingkat pencapaian target di atas 70%.' : 'memerlukan perhatian dan perbaikan lebih lanjut.' }}
            </div>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">4.2 Rekomendasi</div>
            <div class="section-content">
                Berdasarkan hasil monitoring dan analisis yang telah dilakukan, direkomendasikan hal-hal berikut:
            </div>
            <ul class="numbered">
                @if($stats['tidak_tercapai'] > 0)
                <li><strong>Prioritas Tinggi:</strong> Segera melakukan evaluasi mendalam terhadap 
                {{ $stats['tidak_tercapai'] }} indikator yang tidak tercapai dan menyusun program perbaikan;</li>
                @endif
                @if($stats['perlu_perhatian'] > 0)
                <li><strong>Prioritas Menengah:</strong> Memantau secara intensif {{ $stats['perlu_perhatian'] }} 
                indikator yang perlu perhatian untuk mencegah penurunan kinerja;</li>
                @endif
                <li><strong>Perbaikan Berkelanjutan:</strong> Mengoptimalkan capaian {{ $stats['tercapai'] }} 
                indikator yang sudah tercapai dan menjaga konsistensinya;</li>
                <li><strong>Penguatan Sistem:</strong> Meningkatkan koordinasi dan komunikasi antar unit 
                untuk mempercepat proses monitoring dan evaluasi;</li>
                <li><strong>Pengembangan Sumber Daya:</strong> Mengalokasikan sumber daya yang memadai 
                untuk program-program prioritas yang belum tercapai.</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title">4.3 Penutup</div>
            <div class="section-content">
                Laporan monitoring ini disusun sebagai bentuk pertanggungjawaban pelaksanaan Sistem 
                Penjaminan Mutu Internal di {{ $setting['nama_institusi'] ?? 'Perguruan Tinggi' }}. 
                Dengan adanya laporan ini, diharapkan pihak manajemen dapat mengambil langkah-langkah 
                strategis untuk meningkatkan kualitas dan capaian kinerja perguruan tinggi.
            </div>
            <div class="section-content">
                Segala kekurangan dalam pelaksanaan monitoring ini akan diperbaiki pada periode berikutnya. 
                Kritik dan saran yang membangun sangat diharapkan untuk perbaikan sistem monitoring di masa yang akan datang.
            </div>
        </div>
    </div>

</body>
</html>