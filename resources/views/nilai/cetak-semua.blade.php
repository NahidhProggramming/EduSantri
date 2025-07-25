<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Semua Rapor</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            margin: 0;
            padding: 30px;
        }

        .page-break {
            page-break-after: always;
        }

        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 20pt;
            margin: 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14pt;
            margin: 5px 0;
        }

        .header p {
            font-size: 10pt;
            margin: 2px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 5px;
        }

        .info-table td {
            vertical-align: top;
            padding: 4px;
        }

        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.nilai th,
        table.nilai td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        table.nilai th {
            background-color: #eaeaea;
        }

        .deskripsi {
            margin-top: 10px;
            border: 1px solid #000;
            padding: 10px;
            text-align: justify;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
        }

        .footer td {
            text-align: center;
            padding-top: 50px;
        }

        .signature {
            height: 80px;
        }
    </style>
</head>

<body>

    @foreach ($details as $detail)
        @php
            $santri = $detail->santri;
            $kelas = $detail->kelas;
            $sekolah = $detail->sekolah;
            $tahun = $detail->tahunAkademik;
            $waliKelas = $kelas->waliKelas->nama_guru ?? '-';
            $nip = $kelas->waliKelas->nip ?? '-';

            $nilaiList = \App\Models\Nilai::where('detail_id', $detail->id_detail)
                ->with('jadwal.mataPelajaran')
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'mapel' => $item->jadwal->mataPelajaran->nama_mapel ?? '-',
                        'nilai_sumatif' => $item->nilai_sumatif,
                        'nilai_pas' => $item->nilai_pas,
                        'nilai_pat' => $item->nilai_pat,
                    ];
                });
        @endphp

        <div class="container">
            <div class="header">
                <h1>RAPOR SANTRI</h1>
                <h2>Pondok Pesantren Darul Lughah Wal Karomah</h2>
                <p>Jl. Madyen Panjaitan No.12 Sidomukti, Kraksaan, Probolinggo</p>
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
                        <th>Nilai Sumatif</th>
                        <th>Nilai PAS</th>
                        <th>Nilai PAT</th>
                        <th>Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $count = 0;
                    @endphp
                    @foreach ($nilaiList as $index => $n)
                        @php
                            $sumatif = floatval($n->nilai_sumatif);
                            $pas = floatval($n->nilai_pas);
                            $pat = floatval($n->nilai_pat);
                            $isValid = is_numeric($sumatif) && is_numeric($pas) && is_numeric($pat);
                            $rata = $isValid ? round(($sumatif + $pas + $pat) / 3, 2) : '-';
                            if ($isValid) {
                                $total += $rata;
                                $count++;
                            }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left;">{{ $n->mapel }}</td>
                            <td>{{ $n->nilai_sumatif ?? '-' }}</td>
                            <td>{{ $n->nilai_pas ?? '-' }}</td>
                            <td>{{ $n->nilai_pat ?? '-' }}</td>
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
                    <td>{{ $tahun->tempat }},
                        {{ \Carbon\Carbon::parse($tahun->tanggal)->translatedFormat('d F Y') }}<br>Wali Kelas</td>
                </tr>
                <tr class="signature">
                    <td><strong>{{ $santri->ayah ?? '-' }}</strong></td>
                    <td><strong>{{ $waliKelas }}</strong><br>NIP: {{ $nip }}</td>
                </tr>
            </table>
        </div>

        <div class="page-break"></div>
    @endforeach

</body>

</html>
