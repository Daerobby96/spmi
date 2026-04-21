<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Dosen (EDOM)</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo { height: 70px; margin-bottom: 10px; }
        .title { text-transform: uppercase; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
        .subtitle { font-size: 12px; margin-bottom: 5px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 2px 0; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.data-table th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        table.data-table td { border: 1px solid #ddd; padding: 8px; vertical-align: middle; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .footer-sign { width: 100%; margin-top: 40px; }
        .footer-sign td { width: 33%; text-align: center; vertical-align: top; }
        .sign-space { height: 70px; }
        
        .badge { padding: 3px 8px; border-radius: 10px; color: #fff; font-size: 9px; font-weight: bold; display: inline-block; }
        .bg-success { background-color: #28a745; }
        .bg-primary { background-color: #007bff; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        @if($setting['logo_institusi'])
            <img src="{{ public_path('storage/' . $setting['logo_institusi']) }}" class="logo">
        @endif
        <div class="title">{{ $setting['nama_institusi'] }}</div>
        <div class="subtitle">LAPORAN REKAPITULASI KINERJA DOSEN (EDOM)</div>
        <div class="subtitle">Periode: {{ $periode->nama }}</div>
        <div style="font-size: 10px;">{{ $setting['alamat_institusi'] }}, {{ $setting['kota_institusi'] }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="120">Tanggal Cetak</td>
            <td>: {{ date('d F Y') }}</td>
        </tr>
        <tr>
            <td>Dicetak Oleh</td>
            <td>: {{ auth()->user()->name }} ({{ auth()->user()->role->display_name }})</td>
        </tr>
        <tr>
            <td>Total Dosen Dinilai</td>
            <td>: {{ $kinerjas->count() }} orang</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="30">Rank</th>
                <th>Nama Dosen</th>
                <th width="100">NIP</th>
                <th>Homebase</th>
                <th width="60">Skor Rerata</th>
                <th width="80">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kinerjas as $index => $k)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $k->dosen_name }}</td>
                <td class="text-center">{{ $k->dosen_nip }}</td>
                <td>{{ $k->homebase }}</td>
                <td class="text-center fw-bold">{{ $k->total_rerata }}</td>
                <td class="text-center">
                    @php
                        $score = $k->total_rerata;
                        $label = 'Cukup';
                        if($score >= 4.5) $label = 'Sangat Baik';
                        elseif($score >= 3.75) $label = 'Baik';
                        elseif($score < 3) $label = 'Kurang';
                    @endphp
                    {{ $label }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 10px; color: #666; font-style: italic;">
        * Nilai di atas merupakan hasil agregasi dari seluruh kuesioner EDOM yang diisi oleh mahasiswa pada periode terkait.
    </div>

    <table class="footer-sign">
        <tr>
            <td>
                Menyetujui,<br>
                {{ $kepala_institusi->jabatan ?? 'Rektor/Direktur' }}
                <div class="sign-space"></div>
                <strong>{{ $kepala_institusi->name ?? '(..........................)' }}</strong><br>
                NIP. {{ $kepala_institusi->nip ?? '-' }}
            </td>
            <td></td>
            <td>
                {{ $setting['kota_institusi'] }}, {{ date('d F Y') }}<br>
                Ketua SPMI
                <div class="sign-space"></div>
                <strong>{{ $ketua_spmi->name ?? '(..........................)' }}</strong><br>
                NIP. {{ $ketua_spmi->nip ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
