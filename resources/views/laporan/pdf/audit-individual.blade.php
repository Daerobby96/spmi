<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Audit - {{ $audit->kode_audit }}</title>
    <style>
        @page {
            margin: 75pt !important;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        /* ===== COVER PAGE ===== */
        .cover {
            text-align: center;
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
            margin-top: 60px;
            text-align: left;
            padding: 5px 60px;
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
            margin-top: 80px;
            font-size: 10pt;
        }

        /* ===== HALAMAN PENGESAHAN ===== */
        .pengesahan {
            width: 100%;
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
            width: 100%;
        }

        .content:last-of-type {
            page-break-after: avoid;
        }

        /* ===== BAB HEADER ===== */
        .bab-header {
            text-align: center;
            margin-bottom: 50px;
            padding-top: 30px;
        }

        .bab-header:first-of-type {
            page-break-before: auto;
            margin-top: 0;
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
            border-left: 4px solid #4e73df;
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
            table-layout: fixed;
            word-wrap: break-word;
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

        /* ===== INFO TABLE (for audit details) ===== */
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

        /* ===== TEMUAN BOX ===== */
        .temuan-box {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .temuan-header {
            background: #f0f2f5;
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .temuan-kode {
            font-weight: bold;
            font-size: 10pt;
            color: #2c3e50;
        }

        .temuan-kategori {
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }

        .kategori-major { background: #f8d7da; color: #721c24; }
        .kategori-minor { background: #fff3cd; color: #856404; }
        .kategori-observasi { background: #d1ecf1; color: #0c5460; }
        .kategori-rekomendasi { background: #d4edda; color: #155724; }

        .temuan-body {
            padding: 15px;
        }

        .temuan-field {
            margin-bottom: 12px;
        }

        .temuan-field:last-child {
            margin-bottom: 0;
        }

        .temuan-label {
            font-weight: bold;
            font-size: 9pt;
            color: #555;
            margin-bottom: 3px;
        }

        .temuan-value {
            font-size: 9pt;
            text-align: justify;
        }

        /* ===== TINDAK LANJUT BOX ===== */
        .tl-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 12px 15px;
            margin-top: 10px;
        }

        .tl-title {
            font-weight: bold;
            font-size: 9pt;
            color: #28a745;
            margin-bottom: 8px;
        }

        /* ===== TIM AUDITOR ===== */
        .auditor-list {
            list-style: none;
            padding: 0;
        }

        .auditor-item {
            padding: 6px 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .auditor-item:last-child {
            border-bottom: none;
        }

        .auditor-name {
            font-weight: bold;
        }

        .auditor-role {
            font-size: 8pt;
            color: #666;
            background: #f0f2f5;
            padding: 2px 8px;
            border-radius: 3px;
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }

        .status-open { background: #f8d7da; color: #721c24; }
        .status-progress { background: #fff3cd; color: #856404; }
        .status-closed { background: #d4edda; color: #155724; }
        .status-verified { background: #d1ecf1; color: #0c5460; }

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

        /* ===== KESIMPULAN BOX ===== */
        .kesimpulan-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .kesimpulan-title {
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 12px;
            border-bottom: 2px solid #3b82f6;
            display: inline-block;
            padding-bottom: 4px;
        }

        .ai-summary-text {
            color: #334155;
            line-height: 1.7;
            text-align: justify;
        }

        .ai-summary-text ul {
            margin-top: 10px;
            padding-left: 20px;
        }

        .ai-summary-text li {
            margin-bottom: 8px;
            text-align: justify;
        }

        .ai-summary-text strong {
            color: #0f172a;
        }
    </style>
</head>
<body>

    {{-- ==================== COVER PAGE ==================== --}}
    <div class="cover" style="page-break-after: always;">
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
            <div class="cover-title">HASIL AUDIT MUTU INTERNAL</div>
            <div class="cover-subtitle">(Audit Internal SPMI)</div>
        </div>

        <div class="cover-date">
            <p>Dokumen ini adalah laporan hasil audit mutu internal yang dilakukan sesuai dengan<br>
            Sistem Penjaminan Mutu Internal (SPMI) Perguruan Tinggi</p>
            <p style="margin-top: 20px;">{{ $setting['kota_institusi'] ?? 'Kota' }}, {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    {{-- ==================== HALAMAN PENGESAHAN ==================== --}}
    <div class="pengesahan" style="page-break-after: always;">
        <div class="pengesahan-title">LEMBAR PENGESAHAN</div>
        
        <div class="pengesahan-content">
            <table class="pengesahan-table">
                <tr>
                    <td>Kode Audit</td>
                    <td>{{ $audit->kode_audit }}</td>
                </tr>
                <tr>
                    <td>Nama Audit</td>
                    <td>{{ $audit->nama_audit }}</td>
                </tr>
                <tr>
                    <td>Unit yang Diaudit</td>
                    <td>{{ $audit->unit_yang_diaudit }}</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>{{ $audit->periode->nama ?? '-' }} ({{ $audit->periode->tahun ?? '-' }})</td>
                </tr>
                <tr>
                    <td>Tanggal Pelaksanaan</td>
                    <td>{{ $audit->tanggal_audit->locale('id')->translatedFormat('d F Y') }} s/d {{ $audit->tanggal_selesai?->locale('id')->translatedFormat('d F Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Ketua Tim Auditor</td>
                    <td>{{ $audit->ketuaAuditor->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tim Auditor</td>
                    <td>
                        @foreach($audit->auditors as $auditor)
                            {{ $auditor->name }} ({{ $auditor->pivot->peran === 'ketua' ? 'Ketua' : 'Anggota' }}){{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </td>
                </tr>
            </table>

            <div class="ttd-section">
                <div class="ttd-row">
                    <div class="ttd-col-left">
                        <div class="ttd-label">{{ $audit->ketuaAuditor->jabatan ?? 'Ketua Tim Auditor' }},</div>
                        <div class="ttd-nama">{{ $audit->ketuaAuditor->name ?? '.........................' }}</div>
                        <div class="ttd-jabatan">NIP/NIK: {{ $audit->ketuaAuditor->nip ?? '.........................' }}</div>
                    </div>
                    
                    <div class="ttd-col-right">
                        <div class="ttd-label">{{ $ketua_spmi->jabatan ?? 'Ketua SPMI' }}</div>
                        <div class="ttd-nama">{{ $ketua_spmi->name ?? '.........................' }}</div>
                        <div class="ttd-jabatan">NIP/NIK: {{ $ketua_spmi->nip ?? '.........................' }}</div>
                    </div>
                </div>
            </div>

            <div class="ttd-section">
                <div class="ttd-col-center">
                    <div class="ttd-label">Mengetahui,<br>{{ $kepala_institusi->jabatan ?? 'Kepala Perguruan Tinggi' }},</div>
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
                Audit Mutu Internal (AMI) merupakan bagian dari Sistem Penjaminan Mutu Internal (SPMI) Perguruan Tinggi 
                yang bertujuan untuk menilai kelayakan, kecukupan, dan efektivitas penyelenggaraan pendidikan tinggi. 
                Audit ini dilakukan secara objektif dan sistematis untuk memastikan bahwa seluruh kegiatan dalam 
                unit {{ $audit->unit_yang_diaudit }} telah sesuai dengan standar yang ditetapkan.
            </div>
            <div class="section-content">
                Laporan ini disusun sebagai hasil dari pelaksanaan audit internal yang dilakukan pada 
                {{ $audit->tanggal_audit->locale('id')->translatedFormat('d F Y') }} sampai dengan {{ $audit->tanggal_selesai?->locale('id')->translatedFormat('d F Y') ?? $audit->tanggal_audit->locale('id')->translatedFormat('d F Y') }} 
                terhadap {{ $audit->unit_yang_diaudit }}.
            </div>
        </div>

        <div class="section">
            <div class="section-title">1.2 Tujuan Audit</div>
            <div class="section-content">
                @if($audit->tujuan_audit)
                    {!! nl2br(e($audit->tujuan_audit)) !!}
                @else
                Tujuan dari audit ini adalah:
                <ul class="numbered">
                    <li>Menilai kesesuaian pelaksanaan kegiatan dengan standar yang telah ditetapkan;</li>
                    <li>Mengidentifikasi ketidaksesuaian (jika ada) dan area yang memerlukan perbaikan;</li>
                    <li>Memberikan rekomendasi untuk peningkatan kualitas penyelenggaraan pendidikan;</li>
                    <li>Memastikan sistem penjaminan mutu berjalan efektif di unit yang diaudit.</li>
                </ul>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">1.3 Ruang Lingkup Audit</div>
            <div class="section-content">
                @if($audit->lingkup_audit)
                    {!! nl2br(e($audit->lingkup_audit)) !!}
                @else
                Ruang lingkup audit ini mencakup seluruh aspek penyelenggaraan pendidikan di {{ $audit->unit_yang_diaudit }}, 
                meliputi:
                <ul class="numbered">
                    <li>Pelaksanaan Tri Dharma Perguruan Tinggi (Pendidikan, Penelitian, dan Pengabdian kepada Masyarakat);</li>
                    <li>Tata kelola dan manajemen mutu pendidikan;</li>
                    <li>Sumber daya manusia, sarana, dan prasarana pendukung;</li>
                    <li>Dokumen dan tata kelola administrasi.</li>
                </ul>
                @endif
            </div>
        </div>
        
        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">1.4 Tim Auditor</div>
            <div class="section-content">
                Tim auditor yang melaksanakan audit ini terdiri dari:
            </div>
            <ul class="auditor-list">
                @foreach($audit->auditors as $auditor)
                <li class="auditor-item">
                    <span class="auditor-name">{{ $auditor->name }}</span>
                    <span class="auditor-role">{{ $auditor->pivot->peran === 'ketua' ? 'Ketua Auditor' : 'Anggota Auditor' }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="section">
            <div class="section-title">1.5 Jadwal Audit</div>
            <table class="info-table">
                <tr>
                    <td>Tanggal Mulai Audit</td>
                    <td>{{ $audit->tanggal_audit->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Opening Meeting</td>
                    <td>{{ $audit->opening_meeting ? $audit->opening_meeting->locale('id')->translatedFormat('d F Y, H:i') : '-' }}</td>
                </tr>
                <tr>
                    <td>Closing Meeting</td>
                    <td>{{ $audit->closing_meeting ? $audit->closing_meeting->locale('id')->translatedFormat('d F Y, H:i') : '-' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Selesai Audit</td>
                    <td>{{ $audit->tanggal_selesai?->locale('id')->translatedFormat('d F Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Periode Audit</td>
                    <td>{{ $audit->periode->nama ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ==================== BAB II - HASIL AUDIT ==================== --}}
    <div class="content" style="page-break-before: always;">
        <div class="bab-header">
            <div class="bab-number">BAB II</div>
            <div class="bab-title">HASIL AUDIT</div>
        </div>

        <div class="section">
            <div class="section-title">2.1 Ringkasan Eksekutif (Analisa Strategis AI)</div>
            <div class="kesimpulan-box">
                <div class="kesimpulan-title"><i class="bi bi-robot"></i> Analisa Strategis Auditor:</div>
                <div class="ai-summary-text">
                    @if($audit->ai_summary)
                        {!! $audit->ai_summary !!}
                    @else
                        <em>Analisa strategis belum digenerate oleh sistem AI.</em>
                    @endif
                </div>
            </div>
            <p style="font-size: 8pt; color: #888; text-align: center;">*Analisa ini dihasilkan secara otomatis oleh sistem kecerdasan buatan berbasis data temuan audit.</p>
        </div>

        <div class="section">
            <div class="section-title">2.2 Statistik Kepatuhan Instrumen</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Status Checklist</th>
                        <th class="text-center">Jumlah Item</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalChecklist = $audit->checklists->count();
                        $sesuai = $audit->checklists->where('status', 'sesuai')->count();
                        $tidakSesuai = $audit->checklists->where('status', 'tidak_sesuai')->count();
                        $observasi = $audit->checklists->where('status', 'observasi')->count();
                        $na = $audit->checklists->where('status', 'tidak_terkait')->count();
                        $belum = $audit->checklists->where('status', 'belum_diisi')->count();
                    @endphp
                    <tr>
                        <td>Sesuai (Compliant)</td>
                        <td class="text-center">{{ $sesuai }}</td>
                        <td class="text-center">{{ $totalChecklist > 0 ? round(($sesuai/$totalChecklist)*100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td>Tidak Sesuai (KTS)</td>
                        <td class="text-center">{{ $tidakSesuai }}</td>
                        <td class="text-center">{{ $totalChecklist > 0 ? round(($tidakSesuai/$totalChecklist)*100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td>Observasi (OB)</td>
                        <td class="text-center">{{ $observasi }}</td>
                        <td class="text-center">{{ $totalChecklist > 0 ? round(($observasi/$totalChecklist)*100, 1) : 0 }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section" style="page-break-before: always;">
            <div class="section-title">2.3 Ringkasan Temuan Audit</div>
            <div class="section-content">
                Berikut adalah ringkasan temuan audit yang ditemukan selama pelaksanaan audit di {{ $audit->unit_yang_diaudit }}:
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Kategori Temuan</th>
                        <th style="width: 15%;" class="text-center">Jumlah</th>
                        <th style="width: 55%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>KTS Mayor (Ketidaksesuaian Mayor)</td>
                        <td class="text-center fw-bold">{{ $stats['kts_mayor'] }}</td>
                        <td>Temuan kritis yang berdampak signifikan terhadap mutu</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>KTS Minor (Ketidaksesuaian Minor)</td>
                        <td class="text-center fw-bold">{{ $stats['kts_minor'] }}</td>
                        <td>Ketidaksesuaian yang tidak kritis namun perlu diperbaiki</td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>Observasi</td>
                        <td class="text-center fw-bold">{{ $stats['observasi'] }}</td>
                        <td>Temuan yang perlu menjadi perhatian</td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>Rekomendasi</td>
                        <td class="text-center fw-bold">{{ $stats['rekomendasi'] }}</td>
                        <td>Saran untuk peningkatan berkelanjutan</td>
                    </tr>
                    <tr style="background: #f0f2f5; font-weight: bold;">
                        <td colspan="2" class="text-right">TOTAL TEMUAN</td>
                        <td class="text-center">{{ $stats['total'] }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">2.4 Detail Temuan Audit</div>
            <div class="section-content">
                Berikut adalah rincian lengkap temuan audit yang ditemukan:
            </div>

            @forelse($audit->temuans as $index => $temuan)
            <div class="temuan-box">
                <div class="temuan-header">
                    <span class="temuan-kode">Temuan #{{ $index + 1 }} - {{ $temuan->kode_temuan }}</span>
                    <span class="temuan-kategori kategori-{{ strtolower(str_replace('_', '-', $temuan->kategori)) }}">
                        @switch($temuan->kategori)
                            @case('KTS_Mayor') KTS Mayor @break
                            @case('KTS_Minor') KTS Minor @break
                            @case('OB') Observasi @break
                            @case('Rekomendasi') Rekomendasi @break
                        @endswitch
                    </span>
                </div>
                <div class="temuan-body">
                    <div class="temuan-field">
                        <div class="temuan-label">Klausul Standar:</div>
                        <div class="temuan-value">{{ $temuan->klausul_standar ?? '-' }}</div>
                    </div>
                    <div class="temuan-field">
                        <div class="temuan-label">Uraian Temuan:</div>
                        <div class="temuan-value">{!! nl2br(e($temuan->uraian_temuan)) !!}</div>
                    </div>
                    @if($temuan->bukti_objektif)
                    <div class="temuan-field">
                        <div class="temuan-label">Bukti Objektif:</div>
                        <div class="temuan-value">{!! nl2br(e($temuan->bukti_objektif)) !!}</div>
                    </div>
                    @endif
                    <div class="temuan-field">
                        <div class="temuan-label">Status Temuan:</div>
                        <div class="temuan-value">
                            @if($temuan->status === 'open')
                                <span class="status-badge status-open">Open (Belum Ditindaklanjuti)</span>
                            @elseif($temuan->status === 'in_progress')
                                <span class="status-badge status-progress">In Progress (Dalam Proses)</span>
                            @elseif($temuan->status === 'closed')
                                <span class="status-badge status-closed">Closed (Selesai)</span>
                            @elseif($temuan->status === 'verified')
                                <span class="status-badge status-verified">Verified (Terverifikasi)</span>
                            @endif
                        </div>
                    </div>
                    <div class="temuan-field">
                        <div class="temuan-label">Batas Waktu Tindak Lanjut:</div>
                        <div class="temuan-value">{{ $temuan->batas_tindak_lanjut?->locale('id')->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</div>
                    </div>

                    {{-- Tindak Lanjut --}}
                    @if($temuan->tindakLanjuts->count() > 0)
                    @php $tl = $temuan->tindakLanjuts->first(); @endphp
                    <div class="tl-box">
                        <div class="tl-title">TINDAK LANJUT:</div>
                        <div class="temuan-field">
                            <div class="temuan-label">Analisa Penyebab:</div>
                            <div class="temuan-value">{!! nl2br(e($tl->analisa_penyebab)) !!}</div>
                        </div>
                        <div class="temuan-field">
                            <div class="temuan-label">Rencana Tindakan:</div>
                            <div class="temuan-value">{!! nl2br(e($tl->rencana_tindakan)) !!}</div>
                        </div>
                        <div class="temuan-field">
                            <div class="temuan-label">Target Selesai:</div>
                            <div class="temuan-value">{{ $tl->target_selesai?->locale('id')->translatedFormat('d F Y') ?? '-' }}</div>
                        </div>
                        @if($tl->tanggal_realisasi)
                        <div class="temuan-field">
                            <div class="temuan-label">Tanggal Realisasi:</div>
                            <div class="temuan-value">{{ $tl->tanggal_realisasi->locale('id')->translatedFormat('d F Y') }}</div>
                        </div>
                        @endif
                        @if($tl->hasil_verifikasi)
                        <div class="temuan-field">
                            <div class="temuan-label">Hasil Verifikasi Auditor:</div>
                            <div class="temuan-value">
                                <span class="status-badge {{ $tl->hasil_verifikasi === 'diterima' ? 'status-closed' : 'status-open' }}">
                                    {{ strtoupper($tl->hasil_verifikasi) }}
                                </span>
                                @if($tl->verifikasi_auditor)
                                <br><br>{!! nl2br(e($tl->verifikasi_auditor)) !!}
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            
            @endforelse
        </div>
    </div>

    {{-- ==================== BAB III - ANALISIS DAN PEMBAHASAN ==================== --}}
    <div class="content" style="page-break-before: always;">
        <div class="bab-header">
            <div class="bab-number">BAB III</div>
            <div class="bab-title">ANALISIS DAN PEMBAHASAN</div>
        </div>

        <div class="section">
            <div class="section-title">3.1 Analisis Temuan</div>
            <div class="section-content">
                Berdasarkan hasil audit yang telah dilaksanakan, tim auditor melakukan analisis terhadap temuan-temuan 
                yang diidentifikasi di {{ $audit->unit_yang_diaudit }}. Analisis ini mencakup identifikasi akar masalah 
                (root cause) dan faktor-faktor yang berkontribusi terhadap ketidaksesuaian.
            </div>
            
            @if($stats['kts_mayor'] > 0)
            <div class="section-subtitle">3.1.1 Analisis KTS Mayor</div>
            <div class="section-content">
                Terdapat <strong>{{ $stats['kts_mayor'] }} temuan KTS Mayor</strong> yang menunjukkan adanya ketidaksesuaian 
                kritis terhadap standar yang berpotensi berdampak signifikan terhadap mutu layanan pendidikan. 
                Temuan-temuan ini memerlukan perhatian serius dan penanganan segera dari pihak manajemen.
            </div>
            @endif

            @if($stats['kts_minor'] > 0)
            <div class="section-subtitle">3.1.2 Analisis KTS Minor</div>
            <div class="section-content">
                Terdapat <strong>{{ $stats['kts_minor'] }} temuan KTS Minor</strong> yang merupakan ketidaksesuaian 
                non-kritis namun tetap perlu ditindaklanjuti untuk mencegah berkembangnya menjadi masalah yang lebih besar.
            </div>
            @endif

            @if($stats['observasi'] > 0)
            <div class="section-subtitle">3.1.3 Observasi</div>
            <div class="section-content">
                Terdapat <strong>{{ $stats['observasi'] }} temuan Observasi</strong> yang perlu menjadi perhatian 
                unit terkait untuk perbaikan berkelanjutan.
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">3.2 Pembahasan Tindak Lanjut</div>
            <div class="section-content">
                Berdasarkan status tindak lanjut yang telah dilakukan oleh {{ $audit->unit_yang_diaudit }}, 
                diperoleh informasi sebagai berikut:
            </div>
            <table class="info-table">
                <tr>
                    <td>Total Temuan</td>
                    <td>{{ $stats['total'] }} temuan</td>
                </tr>
                <tr>
                    <td>Sudah Ditindaklanjuti</td>
                    <td>{{ $stats['closed'] + $stats['verified'] }} temuan ({{ $stats['total'] > 0 ? round((($stats['closed'] + $stats['verified']) / $stats['total']) * 100, 1) : 0 }}%)</td>
                </tr>
                <tr>
                    <td>Dalam Proses</td>
                    <td>{{ $stats['in_progress'] }} temuan ({{ $stats['total'] > 0 ? round(($stats['in_progress'] / $stats['total']) * 100, 1) : 0 }}%)</td>
                </tr>
                <tr>
                    <td>Belum Ditindaklanjuti</td>
                    <td>{{ $stats['open'] }} temuan ({{ $stats['total'] > 0 ? round(($stats['open'] / $stats['total']) * 100, 1) : 0 }}%)</td>
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

        <div class="section" >
            <div class="section-title">4.1 Kesimpulan</div>
            <div class="kesimpulan-box">
                <div class="kesimpulan-title">KESIMPULAN UMUM</div>
                <div class="section-content">
                    Audit Mutu Internal telah dilaksanakan terhadap <strong>{{ $audit->unit_yang_diaudit }}</strong> 
                    pada periode <strong>{{ $audit->periode->nama ?? '-' }} ({{ $audit->periode->tahun ?? '-' }})</strong>. 
                    Berdasarkan hasil audit, ditemukan <strong>{{ $stats['total'] }} temuan</strong> yang terdiri dari:
                    <ul class="numbered" style="margin-top: 10px;">
                        <li>{{ $stats['kts_mayor'] }} KTS Mayor</li>
                        <li>{{ $stats['kts_minor'] }} KTS Minor</li>
                        <li>{{ $stats['observasi'] }} Observasi</li>
                        <li>{{ $stats['rekomendasi'] }} Rekomendasi</li>
                    </ul>
                </div>
            </div>
            
            <div class="section-content">
                Secara umum, implementasi Sistem Penjaminan Mutu Internal di {{ $audit->unit_yang_diaudit }} 
                {{ $stats['total'] == 0 ? 'sudah berjalan dengan baik dan tidak ditemukan temuan audit.' : 'memerlukan perbaikan dalam beberapa aspek yang telah diidentifikasi dalam laporan ini.' }}
            </div>

            @if($audit->catatan)
            <div class="section-subtitle">Catatan Khusus Auditor:</div>
            <div class="section-content" style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;">
                {!! nl2br(e($audit->catatan)) !!}
            </div>
            @endif
        </div>

        <div class="section" style="page-break-before: always; padding-top: 90px;">
            <div class="section-title">4.2 Rekomendasi</div>
            <div class="section-content">
                Berdasarkan hasil audit yang telah dilakukan, tim auditor memberikan rekomendasi sebagai berikut:
            </div>
            <ul class="numbered">
                @if($stats['kts_mayor'] > 0)
                <li><strong>Prioritas Tinggi:</strong> Segera melakukan perbaikan terhadap {{ $stats['kts_mayor'] }} temuan KTS Mayor 
                untuk menghindari dampak negatif terhadap mutu layanan pendidikan;</li>
                @endif
                @if($stats['kts_minor'] > 0)
                <li><strong>Prioritas Menengah:</strong> Melakukan tindak lanjut terhadap {{ $stats['kts_minor'] }} temuan KTS Minor 
                sesuai dengan batas waktu yang telah ditentukan;</li>
                @endif
                <li><strong>Perbaikan Berkelanjutan:</strong> Mengimplementasikan siklus PDCA (Plan-Do-Check-Act) 
                untuk peningkatan mutu yang berkelanjutan;</li>
                <li><strong>Dokumentasi:</strong> Memastikan seluruh dokumen mutu selalu diperbarui dan sesuai dengan 
                    standar yang berlaku;</li>
                <li><strong>Sosialisasi:</strong> Meningkatkan pemahaman seluruh civitas akademika tentang 
                    Sistem Penjaminan Mutu Internal.</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title">4.3 Penutup</div>
            <div class="section-content">
                Laporan ini disusun sebagai bukti pelaksanaan Audit Mutu Internal di {{ $audit->unit_yang_diaudit }}. 
                Diharapkan dengan adanya laporan ini, pihak manajemen dapat melakukan tindak lanjut terhadap seluruh 
                temuan audit untuk meningkatkan kualitas penyelenggaraan pendidikan.
            </div>
            <div class="section-content">
                Tim auditor mengucapkan terima kasih atas kerja sama dan keterbukaan yang diberikan oleh 
                {{ $audit->unit_yang_diaudit }} selama pelaksanaan audit. Apabila terdapat hal-hal yang perlu 
                diklarifikasi lebih lanjut, tim auditor siap memberikan penjelasan.
            </div>
        </div>
    </div>
</body>
</html>

