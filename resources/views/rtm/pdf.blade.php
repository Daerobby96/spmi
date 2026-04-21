<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan RTM - {{ $rtm->judul_rapat }}</title>
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

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 30px;
            position: relative;
        }

        .header-logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
        }

        .header-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header-address {
            font-size: 9pt;
            color: #666;
        }

        .report-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .info-table td:nth-child(2) {
            width: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #f0f2f5;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11pt;
            border-left: 4px solid #0d6efd;
            margin-bottom: 10px;
        }

        .section-content {
            padding: 0 5px;
            text-align: justify;
            white-space: pre-wrap;
        }

        /* ===== SIGNATURE SECTION ===== */
        .ttd-section {
            margin-top: 50px;
        }

        .ttd-row {
            width: 100%;
            margin-bottom: 30px;
        }

        .ttd-col {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }

        .ttd-label {
            margin-bottom: 60px;
            height: 45px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-jabatan {
            font-size: 9pt;
        }

        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 8pt;
            color: #888;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(!empty($setting['logo_institusi']) && file_exists(storage_path('app/public/' . $setting['logo_institusi'])))
            <img src="{{ storage_path('app/public/' . $setting['logo_institusi']) }}" class="header-logo" style="max-height: 80px;">
        @endif
        <div class="header-title">{{ $setting['nama_institusi'] }}</div>
        <div class="header-address">
            {{ $setting['alamat_institusi'] }}, {{ $setting['kota_institusi'] }}
        </div>
    </div>

    <div class="report-title">NOTULENSI RAPAT TINJAUAN MANAJEMEN (RTM)</div>

    <table class="info-table">
        <tr>
            <td>Judul Rapat</td>
            <td>:</td>
            <td>{{ $rtm->judul_rapat }}</td>
        </tr>
        <tr>
            <td>Tanggal Rapat</td>
            <td>:</td>
            <td>{{ $rtm->tanggal_rapat->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Periode SPMI</td>
            <td>:</td>
            <td>{{ $rtm->periode->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Status Dokumen</td>
            <td>:</td>
            <td>{{ strtoupper($rtm->status) }}</td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">I. STATUS TEMUAN AUDIT (PERIODE INI)</div>
        <div class="section-content">
            <table class="info-table" style="width: 50%; margin-bottom: 0;">
                <tr>
                    <td style="font-weight: normal; width: 200px;">Belum Selesai (Open)</td>
                    <td>:</td>
                    <td><strong>{{ $findingStats['open'] }} Temuan</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: normal;">Dalam Proses Tindak Lanjut</td>
                    <td>:</td>
                    <td><strong>{{ $findingStats['in_progress'] }} Temuan</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: normal;">Sudah Selesai (Closed)</td>
                    <td>:</td>
                    <td><strong>{{ $findingStats['closed'] }} Temuan</strong></td>
                </tr>
            </table>
            <p style="font-size: 8pt; color: #666; margin-top: 5px;">*Data ini diambil secara otomatis dari modul Pelaksanaan Audit & Tindak Lanjut.</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">II. AGENDA RAPAT</div>
        <div class="section-content">
            {!! nl2br(e($rtm->agenda ?? 'Tidak ada agenda khusus.')) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">III. NOTULENSI / PEMBAHASAN</div>
        <div class="section-content">
            {!! nl2br(e($rtm->notulensi ?? 'Data notulensi belum tersedia.')) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">IV. KEPUTUSAN MANAJEMEN / RENCANA TINDAK LANJUT</div>
        <div class="section-content">
            {!! nl2br(e($rtm->keputusan_manajemen ?? 'Belum ada keputusan manajemen yang dicatat.')) !!}
        </div>
    </div>

    <div class="ttd-section">
        <div class="ttd-row">
            <div class="ttd-col" style="float: left;">
                <div class="ttd-label">Dibuat Oleh,<br>Sekretaris Rapat / Auditor,</div>
                <div class="ttd-nama" style="margin-top: 60px;">{{ auth()->user()->name }}</div>
                <div class="ttd-jabatan">NIP: {{ auth()->user()->nip ?? '-' }}</div>
            </div>
            <div class="ttd-col" style="float: right;">
                <div class="ttd-label">Menyetujui,<br>{{ $ketua_spmi->jabatan ?? 'Ketua SPMI' }},</div>
                <div class="ttd-nama" style="margin-top: 60px;">{{ $ketua_spmi->name ?? '.........................' }}</div>
                <div class="ttd-jabatan">NIP: {{ $ketua_spmi->nip ?? '-' }}</div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <div class="ttd-label">Mengetahui,<br>{{ $kepala_institusi->jabatan ?? 'Pimpinan Institusi' }},</div>
            <div class="ttd-nama" style="margin-top: 60px;">{{ $kepala_institusi->name ?? '.........................' }}</div>
            <div class="ttd-jabatan">NIP: {{ $kepala_institusi->nip ?? '.........................' }}</div>
        </div>
    </div>

    <div class="footer">
        Dicetak otomatis oleh Sistem Informasi Penjaminan Mutu Internal (SPMI) - {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
