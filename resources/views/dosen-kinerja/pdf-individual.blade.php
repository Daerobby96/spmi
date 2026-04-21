<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor Kinerja Dosen - {{ $kinerja->dosen_name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo { height: 70px; margin-bottom: 10px; }
        .title { text-transform: uppercase; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
        .subtitle { font-size: 12px; margin-bottom: 5px; }
        
        .section-title { background: #f2f2f2; padding: 5px 10px; font-weight: bold; text-transform: uppercase; margin: 15px 0 10px 0; border-left: 4px solid #000; }
        
        .biodata-table { width: 100%; margin-bottom: 15px; }
        .biodata-table td { padding: 3px 0; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data-table th { background-color: #f9f9f9; border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold; font-size: 9px; }
        table.data-table td { border: 1px solid #ddd; padding: 8px; vertical-align: middle; }
        
        .summary-box { border: 1px solid #000; padding: 15px; text-align: center; margin-bottom: 20px; }
        .score-big { font-size: 24px; font-weight: bold; color: #000; }
        
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        .footer-sign { width: 100%; margin-top: 30px; }
        .footer-sign td { width: 45%; text-align: center; vertical-align: top; }
        .sign-space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        @if($setting['logo_institusi'])
            <img src="{{ public_path('storage/' . $setting['logo_institusi']) }}" class="logo">
        @endif
        <div class="title">{{ $setting['nama_institusi'] }}</div>
        <div class="subtitle">RAPOR EVALUASI DOSEN OLEH MAHASISWA (EDOM)</div>
        <div style="font-size: 10px;">{{ $setting['alamat_institusi'] }}</div>
    </div>

    <div class="section-title">Informasi Dosen</div>
    <table class="biodata-table">
        <tr>
            <td width="120">Nama Dosen</td>
            <td width="200">: <strong>{{ $kinerja->dosen_name }}</strong></td>
            <td width="100">Periode</td>
            <td>: {{ $kinerja->periode->nama }}</td>
        </tr>
        <tr>
            <td>NIP / NIDN</td>
            <td>: {{ $kinerja->dosen_nip }}</td>
            <td>Status</td>
            <td>: Dosen Tetap</td>
        </tr>
        <tr>
            <td>Homebase</td>
            <td>: {{ $kinerja->homebase }}</td>
            <td>Email</td>
            <td>: -</td>
        </tr>
    </table>

    <div class="summary-box">
        <div style="text-transform: uppercase; font-size: 10px; margin-bottom: 5px;">Skor Indeks Kinerja Dosen</div>
        <div class="score-big">{{ $kinerja->total_rerata }}</div>
        @php
            $score = $kinerja->total_rerata;
            $label = 'CUKUP';
            if($score >= 4.5) $label = 'SANGAT BAIK';
            elseif($score >= 3.75) $label = 'BAIK';
            elseif($score < 3) $label = 'KURANG';
        @endphp
        <div style="font-weight: bold; margin-top: 5px;">PREDIKAT: {{ $label }}</div>
    </div>

    <div class="section-title">Nilai Per Aspek Penilaian</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Kategori Aspek</th>
                <th width="100">Rerata Skor</th>
                <th width="150">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kinerja->kategori_scores as $index => $s)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $s['kategori'] }}</td>
                <td class="text-center fw-bold">{{ number_format($s['skor'], 2) }}</td>
                <td class="text-center">
                    @if($s['skor'] >= 4.5) Sangat Baik
                    @elseif($s['skor'] >= 3.75) Baik
                    @elseif($s['skor'] >= 3) Cukup
                    @else Kurang @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Nilai Per Mata Kuliah & Kelas</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Mata Kuliah</th>
                <th>Kelas</th>
                <th width="80">Responden</th>
                <th width="80">Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kinerja->mata_kuliah_scores as $index => $m)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $m['mk'] }} ({{ $m['kode'] }})</td>
                <td class="text-center">{{ $m['kelas'] }}</td>
                <td class="text-center">{{ $m['responden'] }}</td>
                <td class="text-center fw-bold">{{ number_format($m['skor'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="page-break-inside: avoid;">
        <table class="footer-sign">
            <tr>
                <td>
                    Mengetahui,<br>
                    {{ $kepalaInstitusi->jabatan ?? 'Rektor/Direktur' }}
                    <div class="sign-space"></div>
                    <strong>{{ $kepalaInstitusi->name ?? '(..........................)' }}</strong><br>
                    NIP. {{ $kepalaInstitusi->nip ?? '-' }}
                </td>
                <td>
                    {{ $setting['kota_institusi'] }}, {{ date('d F Y') }}<br>
                    Ketua SPMI
                    <div class="sign-space"></div>
                    <strong>{{ $ketuaSpmi->name ?? '(..........................)' }}</strong><br>
                    NIP. {{ $ketuaSpmi->nip ?? '-' }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
