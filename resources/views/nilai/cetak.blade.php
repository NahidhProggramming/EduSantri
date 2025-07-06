<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Santri</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            margin: auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 14px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.nilai th,
        table.nilai td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .nilai th {
            background-color: #f2f2f2;
        }

        .deskripsi {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 15px;
            font-style: italic;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
        }

        .footer td {
            text-align: center;
            vertical-align: top;
            padding-top: 40px;
        }

        .footer .signature {
            height: 60px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>RAPOR SANTRI</h2>
            <p>Pondok Pesantren Darul Lughah Wal Karomah</p>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Nama</strong></td>
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
                        <td style="text-align: left;">{{ $nilai->mapel }}</td>
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
                        'A'
                            => 'Siswa menunjukkan pemahaman yang sangat baik dalam materi pelajaran. Kemampuan analisis dan pemecahan masalahnya sangat baik. Terus pertahankan dan kembangkan potensi yang dimiliki.',
                        'B'
                            => 'Siswa telah mencapai pemahaman yang baik dalam materi pelajaran. Perlu ditingkatkan lagi dalam pemecahan masalah atau analisis untuk hasil lebih optimal.',
                        'C'
                            => 'Siswa perlu lebih berusaha memahami materi. Perlu bimbingan lebih lanjut dalam penguasaan konsep.',
                        'D'
                            => 'Siswa memerlukan bimbingan intensif. Perlu perhatian khusus agar dapat mengikuti pembelajaran.',
                        'E' => 'Siswa sangat membutuhkan pendampingan karena belum mampu memahami materi dengan baik.',
                        default => '-',
                    };
                @endphp
                <tr>
                    <td colspan="5"><strong>Rata-rata Keseluruhan</strong></td>
                    <td><strong>{{ $avg }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5"><strong>Predikat</strong></td>
                    <td><strong>{{ $predikat }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="deskripsi">
            <strong>Deskripsi:</strong> {{ $deskripsi }}
        </div>

        <table class="footer">
            <tr>
                <td></td>
                <td>{{ $tahun->tempat }}, {{ \Carbon\Carbon::parse($tahun->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Orang Tua/Wali</strong></td>
                <td><strong>Wali Kelas</strong></td>
            </tr>
            <tr class="signature">
                <td>......................................</td>
                <td>......................................</td>
            </tr>
        </table>
    </div>
</body>

</html>
