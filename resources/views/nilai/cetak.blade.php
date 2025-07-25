<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rapor Santri</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            font-size: 12pt;
            color: #000;
            margin: 40px;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 22pt;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 14pt;
            margin: 0;
            font-weight: normal;
        }

        .header p {
            font-size: 10pt;
            margin-top: 4px;
            font-style: italic;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 150px;
        }

        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        table.nilai th,
        table.nilai td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }

        table.nilai th {
            background-color: #f5f5f5;
        }

        table.nilai td:first-child {
            width: 40px;
        }

        table.nilai td:nth-child(2) {
            text-align: left;
        }

        .deskripsi {
            border: 1px solid #000;
            padding: 12px;
            margin-top: 25px;
            font-style: italic;
            background-color: #fafafa;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
        }

        .footer td {
            text-align: center;
            vertical-align: top;
            padding-top: 40px;
            font-size: 11pt;
        }

        .footer .signature {
            height: 70px;
        }

        .footer strong {
            display: inline-block;
            margin-top: 8px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>RAPOR SANTRI</h1>
            <h2>Pondok Pesantren Darul Lughah Wal Karomah</h2>
            <p>Jl. Madyen Panjaitan No.12 Sidomukti, Kraksaan, Probolinggo, Jawa Timur</p>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Nama Santri</strong></td>
                <td>: {{ $santri->nama_santri }}</td>
                <td><strong>NIS</strong></td>
                <td>: {{ $santri->nis }}</td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>: {{ $kelas->nama_kelas }}</td>
                <td><strong>Sekolah</strong></td>
                <td>: {{ $sekolah->nama_sekolah }}</td>
            </tr>
            <tr>
                <td><strong>Tahun Akademik</strong></td>
                <td colspan="3">: {{ $tahun->tahun_akademik }} - Semester {{ $tahun->semester }}</td>
            </tr>
        </table>

        <table class="nilai">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Sumatif</th>
                    <th>PAS</th>
                    <th>PAT</th>
                    <th>Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $count = 0;
                @endphp
                @foreach ($nilaiList as $index => $nilai)
                    @php
                        $sumatif = floatval($nilai->nilai_sumatif);
                        $pas = floatval($nilai->nilai_pas);
                        $pat = floatval($nilai->nilai_pat);
                        $isValid = is_numeric($sumatif) && is_numeric($pas) && is_numeric($pat);
                        $rata = $isValid ? round(($sumatif + $pas + $pat) / 3, 2) : '-';
                        if ($isValid) {
                            $total += $rata;
                            $count++;
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $nilai->mapel }}</td>
                        <td>{{ $nilai->nilai_sumatif ?? '-' }}</td>
                        <td>{{ $nilai->nilai_pas ?? '-' }}</td>
                        <td>{{ $nilai->nilai_pat ?? '-' }}</td>
                        <td>{{ $rata }}</td>
                    </tr>
                @endforeach

                @php
                    $avg = $count > 0 ? round($total / $count, 2) : 0;
                    $predikat = match (true) {
                        $avg >= 90 => 'A',
                        $avg >= 80 => 'B',
                        $avg >= 70 => 'C',
                        $avg >= 60 => 'D',
                        default => 'E',
                    };

                    $deskripsi = match ($predikat) {
                        'A' => 'Santri menunjukkan pemahaman yang sangat baik dalam materi pelajaran. Kemampuan analisis dan pemecahan masalahnya sangat baik. Terus pertahankan dan kembangkan potensi yang dimiliki.',
                            'B' => 'Santri telah mencapai pemahaman yang baik dalam materi pelajaran. Perlu ditingkatkan lagi dalam pemecahan masalah atau analisis untuk hasil lebih optimal. Secara keseluruhan menunjukkan perkembangan yang baik dalam pembelajaran dan memiliki sikap yang positif terhadap proses belajar.',
                            'C' => 'Santri perlu lebih berusaha memahami materi. Perlu bimbingan lebih lanjut dalam penguasaan konsep. Secara umum menunjukkan usaha yang cukup, namun masih perlu peningkatan dalam beberapa aspek.',
                            'D' => 'Santri memerlukan bimbingan intensif. Perlu perhatian khusus agar dapat mengikuti pembelajaran. Disarankan untuk lebih serius dalam belajar dan memanfaatkan waktu dengan baik.',
                            'E' => 'Santri sangat membutuhkan pendampingan karena belum mampu memahami materi dengan baik. Perlu adanya kerja sama antara orang tua dan pihak sekolah untuk meningkatkan motivasi belajar.',
                        default => '-',
                    };
                @endphp

                <tr>
                    <td colspan="5" style="text-align: right;"><strong>Rata-rata Keseluruhan</strong></td>
                    <td><strong>{{ $avg }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>Predikat</strong></td>
                    <td><strong>{{ $predikat }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="deskripsi">
            <strong>Deskripsi Umum:</strong><br>
            {{ $deskripsi }}
        </div>

        <table class="footer">
            <tr>
                <td>Mengetahui,<br>Orang Tua/Wali</td>
                <td>{{ $tahun->tempat }}, {{ \Carbon\Carbon::parse($tahun->tanggal)->translatedFormat('d F Y') }}<br>Wali Kelas</td>
            </tr>
            <tr class="signature">
                <td><strong>{{ $santri->ayah ?? '-' }}</strong></td>
                <td>
                    <strong>{{ $kelas->waliKelas->nama_guru ?? '-' }}</strong><br>
                    NIP: {{ $kelas->waliKelas->nip ?? '-' }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
