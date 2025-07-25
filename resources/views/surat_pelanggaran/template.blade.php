<!DOCTYPE html>
<html>

<head>
    <title>Surat Pelanggaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .content {
            margin: 30px 0;
            line-height: 1.6;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .signature {
            margin-top: 80px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>SURAT PELANGGARAN</h2>
        <h3>PONDOK PESANTREN [NAMA PESANTREN]</h3>
        <p>Alamat: [ALAMAT PESANTREN] - Telp: [TELEPON PESANTREN]</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, menerangkan bahwa:</p>

        <table class="table">
            <tr>
                <th>Nama Santri</th>
                <td>{{ $santri->nama_santri }}</td>
            </tr>
            <tr>
                <th>NIS</th>
                <td>{{ $santri->nis }}</td>
            </tr>
            <tr>
                <th>Tanggal Pelanggaran</th>
                <td>{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Jenis Pelanggaran</th>
                <td>{{ $jenis->nama_jenis }}</td>
            </tr>
            <tr>
                <th>Tingkat Pelanggaran</th>
                <td>{{ $jenis->tingkat }}</td>
            </tr>
            <tr>
                <th>Poin</th>
                <td>{{ $jenis->poin }}</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $deskripsi ?? '-' }}</td>
            </tr>
        </table>

        <p>Surat ini dibuat sebagai bentuk laporan resmi dari pihak pondok pesantren atas pelanggaran yang dilakukan
            oleh santri tersebut.</p>
    </div>

    <div class="footer">
        <p>Hormat kami,</p>
        <div class="signature">
            <p>_________________________</p>
            <p>Nama: [NAMA PETUGAS]</p>
            <p>Jabatan: Kesiswaan</p>
        </div>
    </div>
</body>

</html>
