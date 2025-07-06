<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Semua Rapor</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
        }

        table.nilai {
            width: 100%;
            border-collapse: collapse;
        }

        table.nilai th,
        table.nilai td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .ttd {
            margin-top: 80px;
            text-align: right;
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

            $nilaiList = \App\Models\Nilai::where('detail_id', $detail->id_detail)
                ->with('jadwal.mataPelajaran')
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'mapel' => $item->jadwal->mataPelajaran->nama_mapel ?? '-',
                        'nilai_sumatif' => $item->nilai_sumatif,
                        'nilai_pas' => $item->nilai_pas,
                        'nilai_pat' => $item->nilai_pat,
                    ];
                });
        @endphp

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
                @php $total = 0; @endphp
                @foreach ($nilaiList as $i => $n)
                    @php
                        $rata = round(($n->nilai_sumatif + $n->nilai_pas + $n->nilai_pat) / 3, 2);
                        $total += $rata;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="text-align: left;">{{ $n->mapel }}</td>
                        <td>{{ $n->nilai_sumatif ?? '-' }}</td>
                        <td>{{ $n->nilai_pas ?? '-' }}</td>
                        <td>{{ $n->nilai_pat ?? '-' }}</td>
                        <td>{{ $rata }}</td>
                    </tr>
                @endforeach

                @php
                    $avg = count($nilaiList) > 0 ? round($total / count($nilaiList), 2) : 0;
                    $predikat = match (true) {
                        $avg >= 90 => 'A',
                        $avg >= 80 => 'B',
                        $avg >= 70 => 'C',
                        $avg >= 60 => 'D',
                        default => 'E',
                    };

                    $deskripsi = match ($predikat) {
                        'A' => 'Siswa menunjukkan pemahaman yang sangat baik dalam materi pelajaran. Kemampuan analisis dan pemecahan masalahnya sangat baik. Terus pertahankan dan kembangkan potensi yang dimiliki.',
                        'B' => 'Siswa telah mencapai pemahaman yang baik dalam materi pelajaran. Perlu ditingkatkan lagi dalam pemecahan masalah atau analisis untuk hasil yang lebih optimal.',
                        'C' => 'Siswa perlu lebih berusaha memahami materi. Bimbingan lebih lanjut dalam penguasaan konsep sangat dianjurkan.',
                        'D' => 'Siswa memerlukan bimbingan intensif dan perhatian khusus dalam penguasaan materi pelajaran.',
                        default => 'Siswa sangat membutuhkan pendampingan khusus. Strategi belajar yang lebih tepat perlu diterapkan.',
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
                <tr>
                    <td colspan="6" style="text-align: left;"><strong>Deskripsi:</strong> {{ $deskripsi }}</td>
                </tr>
            </tbody>
        </table>

        <div class="ttd">
            <p>{{ $tahun->tempat }}, {{ \Carbon\Carbon::parse($tahun->tanggal)->translatedFormat('d F Y') }}</p>
            <p>Wali Kelas</p>
            <br><br><br>
            <p><strong>..................................</strong></p>
        </div>

        <div class="page-break"></div>
    @endforeach

</body>
</html>
