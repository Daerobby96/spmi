<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Laporan AMI</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            text-align: justify;
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
            font-size: 20pt;
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
            font-size: 16pt;
            font-weight: bold;
        }

        h1 { font-size: 16pt; margin-top: 20px; text-align: center; font-weight: bold; text-transform: uppercase; }
        h2 { font-size: 12pt; margin-top: 15px; font-weight: bold; }
        
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
            PELAKSANAAN SIKLUS PPEPP
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
        <h1>LEMBAR PENGESAHAN</h1>
        <p class="mt-4 text-center">
            Buku Laporan Audit Mutu Internal (AMI) Periode {{ $periode ? $periode->nama : '-' }} ini disusun sebagai bentuk pertanggungjawaban pelaksanaan siklus Penjaminan Mutu Internal (PPEPP) di lingkungan {{ $setting['nama_institusi'] }}.
        </p>
        <p class="text-center">Laporan ini merangkum seluruh kegiatan mulai dari Penetapan Standar, Pelaksanaan, Evaluasi (Audit), Pengendalian (Tindak Lanjut), hingga Peningkatan (Rapat Tinjauan Manajemen).</p>
        
        <table class="pengesahan-table mt-8">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Ketua Penjaminan Mutu</strong>
                    <div class="signature-space"></div>
                    <u>{{ $ketua_spmi ? $ketua_spmi->name : '.......................................' }}</u>
                    @if($ketua_spmi && $ketua_spmi->nidn)<br>NIDN. {{ $ketua_spmi->nidn }}@endif
                </td>
                <td>
                    Mengesahkan,<br>
                    <strong>Pimpinan Institusi</strong>
                    <div class="signature-space"></div>
                    <u>{{ $kepala_institusi ? $kepala_institusi->name : '.......................................' }}</u>
                    @if($kepala_institusi && $kepala_institusi->nidn)<br>NIDN. {{ $kepala_institusi->nidn }}@endif
                </td>
            </tr>
        </table>
    </div>

    {{-- KATA PENGANTAR --}}
    <div class="page-break">
        <h1>KATA PENGANTAR</h1>
        <p>Puji syukur kehadirat Tuhan Yang Maha Esa atas rahmat dan karunia-Nya, sehingga Buku Laporan Pelaksanaan Audit Mutu Internal (AMI) siklus Penjaminan Mutu Internal (PPEPP) Periode {{ $periode ? $periode->nama : '-' }} di lingkungan {{ $setting['nama_institusi'] }} ini dapat diselesaikan dengan baik.</p>
        <p>Laporan ini merupakan manifestasi dari komitmen {{ $setting['nama_institusi'] }} dalam membangun budaya mutu berkelanjutan secara sistematis dan terstruktur. Penyusunan laporan ini mengikuti kaidah dan standar yang ditetapkan oleh instrumen akreditasi nasional, di mana implementasi SPMI diukur melalui efektivitas pelaksanaan siklus PPEPP.</p>
        <p>Kami mengucapkan terima kasih kepada pimpinan institusi, tim auditor internal, pimpinan unit kerja (auditee), serta seluruh pihak yang telah berpartisipasi aktif dalam menyukseskan implementasi SPMI tahun ini.</p>
        <p>Semoga laporan ini dapat menjadi acuan strategis dalam mengambil kebijakan peningkatan mutu pendidikan di masa yang akan datang.</p>
        <div class="mt-8 text-right">
            <p>{{ $setting['kota_institusi'] ?? 'Kota' }}, {{ date('d F Y') }}<br>
            Ketua Penjaminan Mutu</p>
            <div class="signature-space"></div>
            <p><u>{{ $ketua_spmi ? $ketua_spmi->name : '.......................................' }}</u></p>
        </div>
    </div>

    {{-- BAB I: PENDAHULUAN --}}
    <div class="page-break">
        <h1>BAB I<br>PENDAHULUAN</h1>
        <h2>1.1 Latar Belakang</h2>
        <p>Sistem Penjaminan Mutu Internal (SPMI) merupakan kegiatan sistemik penjaminan mutu pendidikan tinggi oleh perguruan tinggi secara otonom untuk mengendalikan dan meningkatkan penyelenggaraan pendidikan tinggi secara berencana dan berkelanjutan. Pelaksanaan SPMI di {{ $setting['nama_institusi'] }} diwujudkan melalui siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP).</p>
        <h2>1.2 Tujuan</h2>
        <p>Tujuan penyusunan laporan ini adalah:</p>
        <ol>
            <li>Mendokumentasikan secara komprehensif implementasi siklus PPEPP pada periode berjalan.</li>
            <li>Mengukur tingkat kepatuhan dan ketercapaian standar mutu institusi.</li>
            <li>Mengidentifikasi area kelemahan (root causes) dan menyusun rekomendasi perbaikan melalui Audit Mutu Internal.</li>
            <li>Menyediakan bukti fisik pendukung untuk keperluan Akreditasi (APT/APS) oleh BAN-PT maupun Lembaga Akreditasi Mandiri (LAM).</li>
        </ol>
        <h2>1.3 Ruang Lingkup</h2>
        <p>Ruang lingkup laporan ini mencakup seluruh unit kerja akademik dan non-akademik di lingkungan {{ $setting['nama_institusi'] }} yang menjadi subjek implementasi standar pendidikan tinggi.</p>
    </div>

    {{-- BAB II: PENETAPAN --}}
    <div class="page-break">
        <h1>BAB II<br>PENETAPAN STANDAR MUTU</h1>
        <p>Tahap penetapan merupakan fondasi utama dari siklus SPMI, di mana {{ $setting['nama_institusi'] }} merancang dan menyahkan dokumen standar mutu pendidikan tinggi. Berikut adalah rekapitulasi standar yang diberlakukan:</p>
        
        <h2>2.1 Rekapitulasi Standar Mutu</h2>
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
        
        <h2>2.2 Ketersediaan Dokumen Mutu</h2>
        <p>Berdasarkan validasi sistem, terdapat <strong>{{ $dokumens->count() }} dokumen mutu</strong> yang berstatus disahkan (*approved*) pada periode ini, meliputi Kebijakan Mutu, Manual Mutu, Standar Mutu, dan Formulir Mutu.</p>
    </div>

    {{-- BAB III: PELAKSANAAN --}}
    <div class="page-break">
        <h1>BAB III<br>PELAKSANAAN STANDAR MUTU</h1>
        <p>Tahap pelaksanaan (monitoring) mendokumentasikan sejauh mana Indikator Kinerja Utama (IKU) dan Indikator Kinerja Tambahan (IKT) dicapai oleh masing-masing unit kerja pelaksana (auditee).</p>
        
        <h2>3.1 Capaian Indikator Kinerja</h2>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Kode IKU/IKT</th>
                    <th width="45%">Nama Indikator</th>
                    <th width="10%">Target</th>
                    <th width="10%">Capaian</th>
                    <th width="10%">%</th>
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
                <tr><td colspan="6" class="text-center">Belum ada data capaian / monitoring yang diinput.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- BAB IV: EVALUASI --}}
    <div class="page-break">
        <h1>BAB IV<br>EVALUASI (AUDIT MUTU INTERNAL)</h1>
        <p>Evaluasi capaian mutu dilakukan secara objektif melalui kegiatan Audit Mutu Internal (AMI). Hal ini bertujuan untuk memastikan kesesuaian antara pelaksanaan dengan standar, serta mengidentifikasi Ketidaksesuaian (KTS).</p>
        
        <h2>4.1 Pelaksanaan AMI</h2>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Audit</th>
                    <th width="25%">Unit Auditee</th>
                    <th width="25%">Ketua Auditor</th>
                    <th width="15%">Temuan</th>
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
                <tr><td colspan="5" class="text-center">Belum ada kegiatan audit yang dicatat.</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <h2>4.2 Rekapitulasi Kategori Temuan</h2>
        <p>Selama periode AMI ini, tim auditor menemukan dan mengklasifikasikan temuan sebagai berikut:</p>
        <ul>
            <li><strong>Ketidaksesuaian Mayor (KTS Mayor):</strong> {{ $temuanPerKategori['KTS_Mayor'] ?? 0 }} temuan</li>
            <li><strong>Ketidaksesuaian Minor (KTS Minor):</strong> {{ $temuanPerKategori['KTS_Minor'] ?? 0 }} temuan</li>
            <li><strong>Observasi (OB):</strong> {{ $temuanPerKategori['OB'] ?? 0 }} temuan</li>
            <li><strong>Rekomendasi Peningkatan:</strong> {{ $temuanPerKategori['Rekomendasi'] ?? 0 }} rekomendasi</li>
        </ul>
    </div>

    {{-- BAB V: PENGENDALIAN --}}
    <div class="page-break">
        <h1>BAB V<br>PENGENDALIAN (TINDAK LANJUT)</h1>
        <p>Tindakan perbaikan (Koreksi) dan tindakan pencegahan dilakukan terhadap setiap temuan AMI. Pengendalian ini dikawal langsung oleh pimpinan unit kerja untuk memulihkan standar yang belum tercapai.</p>
        
        <h2>5.1 Rencana Tindak Lanjut Unit Kerja</h2>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Deskripsi Temuan (Akar Masalah)</th>
                    <th width="35%">Rencana Tindakan Perbaikan</th>
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
                <tr><td colspan="5" class="text-center">Belum ada data tindak lanjut yang dicatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- BAB VI: PENINGKATAN --}}
    <div class="page-break">
        <h1>BAB VI<br>PENINGKATAN (RAPAT TINJAUAN MANAJEMEN)</h1>
        <p>Berdasarkan hasil evaluasi dan pengendalian, Pimpinan Institusi menyelenggarakan Rapat Tinjauan Manajemen (RTM). Hasil RTM berfungsi sebagai rujukan bagi penyusunan/peningkatan standar mutu di siklus/periode berikutnya, yang biasa disebut dengan konsep *Kaizen* (Peningkatan Mutu Berkelanjutan).</p>
        
        @forelse($rtms as $rtm)
            <div style="border: 1px solid #000; padding: 15px; margin-top: 15px;">
                <h3 style="margin-top:0;">Topik: {{ $rtm->judul_rapat }}</h3>
                <p><strong>Tanggal Pelaksanaan:</strong> {{ $rtm->tanggal_rapat ? $rtm->tanggal_rapat->format('d F Y') : '-' }}</p>
                <p><strong>Agenda Pembahasan:</strong><br>{!! nl2br(e($rtm->agenda)) !!}</p>
                <p><strong>Keputusan dan Output Peningkatan:</strong><br>{!! nl2br(e($rtm->keputusan_manajemen ?? 'Belum ada keputusan.')) !!}</p>
            </div>
        @empty
            <p class="text-center" style="margin-top: 20px;">Belum ada dokumen Rapat Tinjauan Manajemen pada periode ini.</p>
        @endforelse
    </div>

    {{-- BAB VII: PENUTUP --}}
    <div>
        <h1>BAB VII<br>PENUTUP</h1>
        <p>Demikian Laporan Pelaksanaan Audit Mutu Internal (AMI) Siklus PPEPP ini disusun. Ketercapaian berbagai indikator mutu dan penuntasan temuan AMI diharapkan mampu mendorong peningkatan daya saing dan keunggulan institusi ke depan.</p>
        <p>Penyempurnaan pelaksanaan Sistem Penjaminan Mutu Internal akan terus diupayakan sebagai wujud akuntabilitas publik dan bentuk tanggung jawab moral penyelenggara pendidikan tinggi demi menghasilkan lulusan yang bermutu.</p>
    </div>

</body>
</html>
