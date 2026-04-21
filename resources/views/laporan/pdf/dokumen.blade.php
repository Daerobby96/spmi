<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dokumen Mutu</title>
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
            border: 3px solid #28a745;
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
            color: #28a745;
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
            border-left: 4px solid #28a745;
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
            color: #28a745;
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
        .badge-dark      { background: #d6d8d9; color: #1b1e21; }

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
            border: 2px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            background: #f0fff4;
        }

        .kesimpulan-title {
            font-weight: bold;
            color: #28a745;
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
            <div class="cover-title">DOKUMEN MUTU</div>
            <div class="cover-subtitle">Sistem Penjaminan Mutu Internal (SPMI)</div>
        </div>

        <div class="cover-info">
            <table>
                <tr>
                    <td>Periode</td>
                    <td>: {{ $periode->nama ?? '-' }} ({{ $periode->tahun ?? '-' }})</td>
                </tr>
                <tr>
                    <td>Total Dokumen</td>
                    <td>: {{ $dokumens->count() }} Dokumen</td>
                </tr>
                <tr>
                    <td>Tanggal Penyusunan</td>
                    <td>: {{ now()->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="cover-date">
            <p>{{ $setting['kota_institusi'] ?? 'Kota' }}, {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    {{-- ==================== HALAMAN PENGESAHAN ==================== --}}
    <div class="pengesahan">
        <div class="pengesahan-title">LEMBAR PENGESAHAN</div>
        
        <div class="pengesahan-content">
            <table class="pengesahan-table">
                <tr>
                    <td>Jenis Laporan</td>
                    <td>Laporan Dokumen Mutu</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>{{ $periode->nama ?? '-' }} ({{ $periode->tahun ?? '-' }})</td>
                </tr>
                <tr>
                    <td>Total Dokumen</td>
                    <td>{{ $dokumens->count() }} Dokumen</td>
                </tr>
                <tr>
                    <td>Dokumen Approved</td>
                    <td>{{ $dokumens->where('status', 'approved')->count() }} Dokumen</td>
                </tr>
                <tr>
                    <td>Dokumen Review</td>
                    <td>{{ $dokumens->where('status', 'review')->count() }} Dokumen</td>
                </tr>
                <tr>
                    <td>Dokumen Draft</td>
                    <td>{{ $dokumens->where('status', 'draft')->count() }} Dokumen</td>
                </tr>
                <tr>
                    <td>Tanggal Penyusunan</td>
                    <td>{{ now()->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
            </table>

            <div class="ttd-section">
                <div class="ttd-row">
                    <div class="ttd-col-left">
                        <div class="ttd-label">Disusun oleh,<br>{{ $koordinator_dokumen->jabatan ?? 'Koordinator Dokumen Mutu' }}</div>
                        <div class="ttd-nama">{{ $koordinator_dokumen->name ?? '.........................' }}</div>
                        <div class="ttd-jabatan">NIP/NIK: {{ $koordinator_dokumen->nip ?? '.........................' }}</div>
                    </div>
                    
                    <div class="ttd-col-right">
                        <div class="ttd-label">Diperiksa oleh,<br>{{ $ketua_spmi->jabatan ?? 'Ketua SPMI' }}</div>
                        <div class="ttd-nama">{{ $ketua_spmi->name ?? '.........................' }}</div>
                        <div class="ttd-jabatan">NIP/NIK: {{ $ketua_spmi->nip ?? '.........................' }}</div>
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
                Dokumen mutu merupakan komponen penting dalam Sistem Penjaminan Mutu Internal (SPMI) Perguruan 
                Tinggi yang berfungsi sebagai bukti pelaksanaan dan pemenuhan standar yang telah ditetapkan. 
                Pengelolaan dokumen mutu yang baik menjadi prasyarat terselenggaranya penjaminan mutu 
                secara efektif dan berkelanjutan.
            </div>
            <div class="section-content">
                Laporan ini disusun untuk melakukan evaluasi terhadap kelengkapan dan status dokumen mutu pada 
                periode {{ $periode->nama ?? '-' }} tahun {{ $periode->tahun ?? '-' }}. Laporan ini mencakup 
                inventarisasi dan analisis status {{ $dokumens->count() }} dokumen mutu yang dikelola dalam sistem.
            </div>
        </div>

        <div class="section">
            <div class="section-title">1.2 Tujuan</div>
            <div class="section-content">
                Tujuan dari penyusunan laporan dokumen mutu ini adalah:
                <ul class="numbered">
                    <li>Menginventarisir seluruh dokumen mutu yang ada dalam sistem SPMI;</li>
                    <li>Mengevaluasi status dan kelayakan dokumen mutu periode berjalan;</li>
                    <li>Mengidentifikasi dokumen yang perlu ditinjau, diperbarui, atau ditambahkan;</li>
                    <li>Memberikan gambaran umum ketersediaan dokumen mutu untuk audit dan evaluasi.</li>
                </ul>
            </div>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">1.3 Ruang Lingkup</div>
            <div class="section-content">
                Ruang lingkup laporan dokumen mutu ini mencakup seluruh dokumen yang dikelola dalam 
                Sistem Penjaminan Mutu Internal {{ $setting['nama_institusi'] ?? 'Perguruan Tinggi' }}, meliputi:
                <ul class="numbered">
                    <li>Dokumen kebijakan mutu dan manual mutu;</li>
                    <li>Dokumen standar operasional prosedur (SOP);</li>
                    <li>Dokumen instruksi kerja dan formulir;</li>
                    <li>Dokumen pedoman teknis dan panduan.</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <div class="section-title">1.4 Informasi Umum</div>
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
                <tr>
                    <td>Total Dokumen</td>
                    <td>{{ $dokumens->count() }} Dokumen</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ==================== BAB II - HASIL DOKUMEN MUTU ==================== --}}
    <div class="content" style="page-break-before: always;">
        <div class="bab-header">
            <div class="bab-number">BAB II</div>
            <div class="bab-title">HASIL DOKUMEN MUTU</div>
        </div>

        <div class="section">
            <div class="section-title">2.1 Ringkasan Dokumen Mutu</div>
            <div class="section-content">
                Berikut adalah ringkasan hasil inventarisasi dokumen mutu pada periode ini:
            </div>
            
            <table class="stats-grid">
                <tr>
                    <td>
                        <div class="stat-value">{{ $dokumens->count() }}</div>
                        <div class="stat-label">Total Dokumen</div>
                    </td>
                    <td>
                        <div class="stat-value" style="color: #28a745;">{{ $dokumens->where('status', 'approved')->count() }}</div>
                        <div class="stat-label">Approved</div>
                    </td>
                    <td>
                        <div class="stat-value" style="color: #ffc107;">{{ $dokumens->where('status', 'review')->count() }}</div>
                        <div class="stat-label">Review</div>
                    </td>
                    <td>
                        <div class="stat-value" style="color: #6c757d;">{{ $dokumens->where('status', 'draft')->count() }}</div>
                        <div class="stat-label">Draft</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">2.2 Distribusi Dokumen per Kategori</div>
            <div class="section-content">
                Distribusi dokumen mutu berdasarkan kategori dokumen:
            </div>
            
            @php
                $totalKategori = $perKategori->sum();
            @endphp

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 45%;">Kategori Dokumen</th>
                        <th class="text-center" style="width: 15%;">Jumlah</th>
                        <th class="text-center" style="width: 15%;">Persentase</th>
                        <th style="width: 20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($perKategori as $kategori => $jumlah)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $kategori ?? 'Tanpa Kategori' }}</td>
                            <td class="text-center">{{ $jumlah }}</td>
                            <td class="text-center">{{ $totalKategori > 0 ? round(($jumlah / $totalKategori) * 100, 1) : 0 }}%</td>
                            <td>{{ $jumlah > 0 ? 'Tersedia' : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data kategori</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="2" class="text-right"><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $totalKategori }}</strong></td>
                        <td class="text-center"><strong>100%</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">2.3 Status Dokumen Mutu</div>
            <div class="section-content">
                Status dokumen mutu berdasarkan tahapan pengelolaan:
            </div>
            
            @php
                $totalStatus = $perStatus->sum();
            @endphp

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Status</th>
                        <th class="text-center" style="width: 15%;">Jumlah</th>
                        <th class="text-center" style="width: 15%;">Persentase</th>
                        <th style="width: 40%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($perStatus as $status => $jumlah)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>
                                @if($status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($status === 'review')
                                    <span class="badge badge-warning">Review</span>
                                @elseif($status === 'obsolete')
                                    <span class="badge badge-dark">Obsolete</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $jumlah }}</td>
                            <td class="text-center">{{ $totalStatus > 0 ? round(($jumlah / $totalStatus) * 100, 1) : 0 }}%</td>
                            <td>
                                @if($status === 'approved')
                                    Dokumen sudah disetujui dan berlaku
                                @elseif($status === 'review')
                                    Dokumen dalam proses review
                                @elseif($status === 'obsolete')
                                    Dokumen sudah tidak berlaku
                                @else
                                    Dokumen masih dalam penyusunan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data status</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="2" class="text-right"><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $totalStatus }}</strong></td>
                        <td class="text-center"><strong>100%</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">2.4 Daftar Dokumen Mutu</div>
            <div class="section-content">
                Rincian lengkap seluruh dokumen mutu yang terdaftar dalam sistem:
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 12%;">Kode</th>
                        <th style="width: 25%;">Judul Dokumen</th>
                        <th style="width: 15%;">Kategori</th>
                        <th style="width: 15%;">Unit Pemilik</th>
                        <th class="text-center" style="width: 8%;">Versi</th>
                        <th class="text-center" style="width: 12%;">Tgl Terbit</th>
                        <th class="text-center" style="width: 8%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumens as $i => $dok)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $dok->kode_dokumen }}</td>
                            <td>{{ Str::limit($dok->judul, 40) }}</td>
                            <td>{{ $dok->kategori->nama ?? '-' }}</td>
                            <td>{{ Str::limit($dok->unit_pemilik, 20) }}</td>
                            <td class="text-center">{{ $dok->versi }}</td>
                            <td class="text-center">{{ $dok->tanggal_terbit->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if($dok->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($dok->status === 'review')
                                    <span class="badge badge-warning">Review</span>
                                @elseif($dok->status === 'obsolete')
                                    <span class="badge badge-dark">Obsolete</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data dokumen</td>
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
            <div class="section-title">3.1 Analisis Kelengkapan Dokumen</div>
            <div class="section-content">
                Berdasarkan hasil inventarisasi yang telah dilaksanakan, diperoleh gambaran umum 
                tentang kelengkapan dokumen mutu pada periode {{ $periode->nama ?? '-' }} tahun {{ $periode->tahun ?? '-' }}. 
                Berikut adalah analisis terhadap hasil inventarisasi:
            </div>

            @php
                $approvedCount = $dokumens->where('status', 'approved')->count();
                $reviewCount = $dokumens->where('status', 'review')->count();
                $draftCount = $dokumens->where('status', 'draft')->count();
                $totalCount = $dokumens->count();
                $persenApproved = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100, 1) : 0;
                $persenReview = $totalCount > 0 ? round(($reviewCount / $totalCount) * 100, 1) : 0;
                $persenDraft = $totalCount > 0 ? round(($draftCount / $totalCount) * 100, 1) : 0;
            @endphp

            @if($approvedCount > 0)
            <div class="section-subtitle">3.1.1 Dokumen Approved</div>
            <div class="section-content">
                Terdapat <strong>{{ $approvedCount }} dokumen ({{ $persenApproved }}%)</strong> yang sudah 
                mendapatkan persetujuan dan berstatus "Approved". Dokumen-dokumen ini sudah dapat 
                digunakan sebagai acuan dalam pelaksanaan kegiatan.
            </div>
            @endif

            @if($reviewCount > 0)
            <div class="section-subtitle">3.1.2 Dokumen dalam Review</div>
            <div class="section-content">
                Terdapat <strong>{{ $reviewCount }} dokumen ({{ $persenReview }}%)</strong> yang masih 
                dalam proses review. Dokumen-dokumen ini perlu segera ditindaklanjuti agar dapat 
                segera disetujui dan diterapkan.
            </div>
            @endif

            @if($draftCount > 0)
            <div class="section-subtitle">3.1.3 Dokumen Draft</div>
            <div class="section-content">
                Terdapat <strong>{{ $draftCount }} dokumen ({{ $persenDraft }}%)</strong> yang masih 
                dalam tahap draft/penyusunan. Dokumen-dokumen ini memerlukan perhatian untuk 
                diselesaikan dan diproses lebih lanjut.
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">3.2 Distribusi Dokumen per Unit</div>
            <div class="section-content">
                Distribusi dokumen mutu berdasarkan unit pemilik menunjukkan pembagian tanggung jawab 
                pengelolaan dokumen di setiap unit kerja.
            </div>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">3.3 Evaluasi Proses Pengelolaan</div>
            <div class="section-content">
                Proses pengelolaan dokumen mutu yang dilaksanakan menunjukkan tingkat efektivitas 
                sistem dokumentasi. Berdasarkan data yang ada:
            </div>
            <table class="info-table">
                <tr>
                    <td>Dokumen Terverifikasi (Approved)</td>
                    <td>{{ $approvedCount }} dokumen ({{ $persenApproved }}%)</td>
                </tr>
                <tr>
                    <td>Dalam Proses Review</td>
                    <td>{{ $reviewCount }} dokumen ({{ $persenReview }}%)</td>
                </tr>
                <tr>
                    <td>Belum Selesai (Draft)</td>
                    <td>{{ $draftCount }} dokumen ({{ $persenDraft }}%)</td>
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
                    Inventarisasi dokumen mutu telah dilaksanakan untuk periode 
                    <strong>{{ $periode->nama ?? '-' }} tahun {{ $periode->tahun ?? '-' }}</strong>. 
                    Dari total <strong>{{ $totalCount }} dokumen</strong> yang terdaftar dalam sistem, 
                    diperoleh hasil sebagai berikut:
                    <ul class="numbered" style="margin-top: 10px;">
                        <li>{{ $approvedCount }} dokumen approved ({{ $persenApproved }}%)</li>
                        <li>{{ $reviewCount }} dokumen dalam review ({{ $persenReview }}%)</li>
                        <li>{{ $draftCount }} dokumen dalam draft ({{ $persenDraft }}%)</li>
                    </ul>
                </div>
            </div>
            
            <div class="section-content">
                Secara umum, pengelolaan dokumen mutu {{ $setting['nama_institusi'] ?? 'Perguruan Tinggi' }} 
                pada periode ini {{ $persenApproved >= 70 ? 'sudah cukup baik dengan tingkat approved di atas 70%.' : 'memerlukan perhatian dan perbaikan lebih lanjut.' }}
            </div>
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">4.2 Rekomendasi</div>
            <div class="section-content">
                Berdasarkan hasil inventarisasi dan analisis yang telah dilakukan, direkomendasikan hal-hal berikut:
            </div>
            <ul class="numbered">
                @if($reviewCount > 0)
                <li><strong>Prioritas Tinggi:</strong> Segera menyelesaikan proses review untuk 
                {{ $reviewCount }} dokumen yang masih dalam tahap review;</li>
                @endif
                @if($draftCount > 0)
                <li><strong>Prioritas Menengah:</strong> Menyelesaikan penyusunan {{ $draftCount }} 
                dokumen yang masih dalam status draft;</li>
                @endif
                <li><strong>Pemutakhiran Dokumen:</strong> Melakukan review berkala terhadap 
                {{ $approvedCount }} dokumen yang sudah approved untuk memastikan kesesuaiannya;</li>
                <li><strong>Standarisasi:</strong> Meningkatkan konsistensi format dan kode dokumen 
                antar unit kerja;</li>
                <li><strong>Sosialisasi:</strong> Meningkatkan pemahaman pengelola dokumen tentang 
                tata cara penyusunan dan pengajuan dokumen mutu;</li>
                <li><strong>Digitasi:</strong> Mengoptimalkan penggunaan sistem elektronik untuk 
                pengelolaan dokumen mutu.</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title">4.3 Penutup</div>
            <div class="section-content">
                Laporan dokumen mutu ini disusun sebagai bentuk pertanggungjawaban pengelolaan 
                Sistem Penjaminan Mutu Internal di {{ $setting['nama_institusi'] ?? 'Perguruan Tinggi' }}. 
                Dengan adanya laporan ini, diharapkan pihak manajemen dapat mengambil langkah-langkah 
                strategis untuk meningkatkan kualitas dan kelengkapan dokumen mutu.
            </div>
            <div class="section-content">
                Segala kekurangan dalam pelaksanaan inventarisasi ini akan diperbaiki pada periode berikutnya. 
                Kritik dan saran yang membangun sangat diharapkan untuk perbaikan sistem pengelolaan dokumen di masa yang akan datang.
            </div>
        </div>
    </div>

</body>
</html>
