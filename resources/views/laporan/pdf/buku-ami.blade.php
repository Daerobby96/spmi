<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Laporan AMI</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }
        .mt-8 { margin-top: 5rem; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            text-align: center;
        }
        
        /* Cover Styles */
        .cover-page {
            text-align: center;
            padding-top: 100px;
        }
        .cover-title {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .cover-subtitle {
            font-size: 16pt;
            margin-bottom: 50px;
        }
        .cover-logo {
            width: 200px;
            height: 200px;
            margin: 0 auto 50px auto;
        }
        .cover-bottom {
            margin-top: 150px;
            font-size: 14pt;
            font-weight: bold;
        }

        h1 { font-size: 18pt; margin-top: 20px; border-bottom: 2px solid #000; padding-bottom: 5px; }
        h2 { font-size: 14pt; margin-top: 15px; }
        
        .pengesahan-table { border: none; margin-top: 50px; }
        .pengesahan-table td { border: none; text-align: center; width: 50%; padding-top: 10px; }
        .signature-space { height: 100px; }
    </style>
</head>
<body>

    {{-- COVER PAGE --}}
    <div class="cover-page page-break">
        <div class="cover-title">
            BUKU LAPORAN<br>
            AUDIT MUTU INTERNAL (AMI)<br>
            SIKLUS PPEPP
        </div>
        
        <div class="cover-subtitle">
            Periode: {{ $periode ? $periode->nama : 'Semua Periode' }}
        </div>

        <div class="cover-logo">
            @if(isset($setting['logo_institusi']) && $setting['logo_institusi'])
                @php
                    $logoPath = storage_path('app/public/' . $setting['logo_institusi']);
                    $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
                @endphp
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" style="max-width: 200px; max-height: 200px;">
                @else
                    <div style="width: 150px; height: 150px; border: 2px dashed #999; margin: 0 auto; line-height: 150px; color: #999;">LOGO</div>
                @endif
            @else
                <div style="width: 150px; height: 150px; border: 2px dashed #999; margin: 0 auto; line-height: 150px; color: #999;">LOGO</div>
            @endif
        </div>

        <div class="cover-bottom">
            <div class="text-uppercase">{{ $setting['nama_institusi'] }}</div>
            <div>Tahun {{ $periode ? $periode->tahun : date('Y') }}</div>
        </div>
    </div>

    {{-- LEMBAR PENGESAHAN --}}
    <div class="page-break">
        <h1 class="text-center" style="border:none;">LEMBAR PENGESAHAN</h1>
        <p class="mt-4">
            Buku Laporan Audit Mutu Internal (AMI) Periode {{ $periode ? $periode->nama : '-' }} ini disusun sebagai bentuk pertanggungjawaban pelaksanaan siklus Penjaminan Mutu Internal (PPEPP) di lingkungan {{ $setting['nama_institusi'] }}.
        </p>
        <p>Laporan ini merangkum seluruh kegiatan mulai dari Penetapan Standar, Pelaksanaan, Evaluasi (Audit), Pengendalian (Tindak Lanjut), hingga Peningkatan (Rapat Tinjauan Manajemen).</p>
        
        <table class="pengesahan-table mt-8">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Ketua Penjaminan Mutu</strong>
                    <div class="signature-space">
                        {{-- QR Code placeholder or signature --}}
                    </div>
                    <u>{{ $ketua_spmi ? $ketua_spmi->name : '.......................................' }}</u>
                    @if($ketua_spmi && $ketua_spmi->nidn)<br>NIDN. {{ $ketua_spmi->nidn }}@endif
                </td>
                <td>
                    Mengesahkan,<br>
                    <strong>Pimpinan Institusi</strong>
                    <div class="signature-space">
                        {{-- QR Code placeholder or signature --}}
                    </div>
                    <u>{{ $kepala_institusi ? $kepala_institusi->name : '.......................................' }}</u>
                    @if($kepala_institusi && $kepala_institusi->nidn)<br>NIDN. {{ $kepala_institusi->nidn }}@endif
                </td>
            </tr>
        </table>
    </div>

    {{-- BAB 1: PENETAPAN --}}
    <div class="page-break">
        <h1>BAB I. PENETAPAN (STANDAR MUTU)</h1>
        <p>Tahap penetapan merupakan tahap awal siklus PPEPP dimana standar mutu pendidikan tinggi ditetapkan. Berikut adalah ringkasan standar yang berlaku pada periode ini:</p>
        
        <h2>1.1 Rekapitulasi Standar Mutu</h2>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Kode</th>
                    <th width="60%">Nama Standar</th>
                    <th width="15%">Jml Indikator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($standars as $idx => $st)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $st->kode }}</td>
                    <td>{{ $st->nama }}</td>
                    <td class="text-center">{{ $st->indikators->count() }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Belum ada data standar</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <h2>1.2 Ketersediaan Dokumen Mutu</h2>
        <p>Total dokumen mutu yang disahkan: <strong>{{ $dokumens->count() }} dokumen</strong>.</p>
    </div>

    {{-- BAB 2: PELAKSANAAN --}}
    <div class="page-break">
        <h1>BAB II. PELAKSANAAN (MONITORING)</h1>
        <p>Pemantauan (monitoring) dilakukan untuk melihat sejauh mana indikator kinerja utama dan tambahan dapat dicapai oleh unit-unit kerja.</p>
        
        <h2>2.1 Capaian Indikator Kinerja</h2>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode IKU</th>
                    <th width="45%">Nama Indikator</th>
                    <th width="10%">Target</th>
                    <th width="10%">Capaian</th>
                    <th width="15%">% Capaian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monitorings as $idx => $mon)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $mon->indikator->kode ?? '-' }}</td>
                    <td>{{ $mon->indikator->nama ?? '-' }}</td>
                    <td class="text-center">{{ $mon->indikator->target_nilai ?? '-' }}</td>
                    <td class="text-center">{{ $mon->nilai_capaian }}</td>
                    <td class="text-center">{{ $mon->persentase_capaian }}%</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada data capaian / monitoring</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- BAB 3: EVALUASI --}}
    <div class="page-break">
        <h1>BAB III. EVALUASI (AUDIT MUTU INTERNAL)</h1>
        <p>Evaluasi capaian mutu dilakukan melalui kegiatan Audit Mutu Internal (AMI) untuk memastikan kepatuhan pelaksanaan terhadap standar yang ditetapkan.</p>
        
        <h2>3.1 Pelaksanaan AMI</h2>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Audit</th>
                    <th width="25%">Unit Diaudit</th>
                    <th width="25%">Ketua Auditor</th>
                    <th width="15%">Jml Temuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $idx => $audit)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $audit->nama_audit }}</td>
                    <td>{{ $audit->unit_yang_diaudit }}</td>
                    <td>{{ $audit->ketuaAuditor->name ?? '-' }}</td>
                    <td class="text-center">{{ $audit->temuans->count() }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Belum ada data audit</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <h2>3.2 Rekapitulasi Kategori Temuan</h2>
        <ul>
            <li>KTS Mayor: {{ $temuanPerKategori['KTS_Mayor'] ?? 0 }}</li>
            <li>KTS Minor: {{ $temuanPerKategori['KTS_Minor'] ?? 0 }}</li>
            <li>Observasi: {{ $temuanPerKategori['OB'] ?? 0 }}</li>
            <li>Rekomendasi: {{ $temuanPerKategori['Rekomendasi'] ?? 0 }}</li>
        </ul>
    </div>

    {{-- BAB 4: PENGENDALIAN --}}
    <div class="page-break">
        <h1>BAB IV. PENGENDALIAN (TINDAK LANJUT)</h1>
        <p>Tindakan perbaikan dan pencegahan (Pengendalian) dilakukan terhadap temuan AMI untuk memastikan perbaikan mutu secara berkesinambungan.</p>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Temuan</th>
                    <th width="35%">Rencana Tindakan</th>
                    <th width="15%">Target Selesai</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tindakLanjuts as $idx => $tl)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $tl->temuan->uraian_temuan ?? '-' }}</td>
                    <td>{{ $tl->rencana_tindakan }}</td>
                    <td class="text-center">{{ $tl->target_selesai ? $tl->target_selesai->format('d/m/Y') : '-' }}</td>
                    <td class="text-center text-uppercase">{{ $tl->status }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Belum ada data tindak lanjut</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- BAB 5: PENINGKATAN --}}
    <div>
        <h1>BAB V. PENINGKATAN (RAPAT TINJAUAN MANAJEMEN)</h1>
        <p>Hasil akhir siklus PPEPP dibawa ke dalam Rapat Tinjauan Manajemen (RTM) guna merumuskan peningkatan standar di periode berikutnya.</p>
        
        @forelse($rtms as $rtm)
            <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
                <h3>Judul RTM: {{ $rtm->judul_rapat }}</h3>
                <p><strong>Tanggal:</strong> {{ $rtm->tanggal_rapat ? $rtm->tanggal_rapat->format('d F Y') : '-' }}</p>
                <p><strong>Agenda:</strong><br>{{ $rtm->agenda }}</p>
                <p><strong>Keputusan / Output Peningkatan:</strong><br>{{ $rtm->keputusan_manajemen ?? 'Belum ada keputusan yang difinalisasi.' }}</p>
            </div>
        @empty
            <p class="text-center">Belum ada catatan Rapat Tinjauan Manajemen pada periode ini.</p>
        @endforelse
    </div>

</body>
</html>
