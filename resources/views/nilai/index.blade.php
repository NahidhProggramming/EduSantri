@extends('layouts.app')
@section('title', 'Filter Nilai Santri')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold mb-4">Filter Nilai Santri</h5>

        <div class="row">
            {{-- Sidebar kiri --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <select id="tahunSelect" class="form-select">
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun->id_tahun_akademik }}">{{ $tahun->tahun_akademik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Sekolah</label>
                            <select id="sekolahSelect" class="form-select">
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($sekolahList as $sekolah)
                                    <option value="{{ $sekolah->id_sekolah }}">{{ $sekolah->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Daftar Kelas --}}
                <div id="kelasList"></div>
            </div>

            {{-- Konten kanan --}}
            <div class="col-md-8">
                <div class="mb-3 text-end">
                    <a href="#" id="btnCetakSemua" class="btn btn-outline-success d-none" target="_blank">
                        <i class="bi bi-printer"></i> Cetak Semua Rapor
                    </a>
                </div>
                <div id="santriTable"></div>
            </div>
        </div>

        {{-- Modal Detail Nilai --}}
        <div class="modal fade" id="modalNilai" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body" id="modalNilaiContent">
                        {{-- Konten detail nilai akan dimuat oleh JavaScript --}}
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div id="footerInfo" class="fw-bold"></div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Script --}}
        <script>
            function loadKelas() {
                const tahun = document.getElementById('tahunSelect').value;
                const sekolah = document.getElementById('sekolahSelect').value;

                if (!tahun || !sekolah) {
                    document.getElementById('kelasList').innerHTML = '';
                    document.getElementById('santriTable').innerHTML = '';
                    return;
                }

                fetch(`/nilai/kelas?tahun=${tahun}&sekolah=${sekolah}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            document.getElementById('kelasList').innerHTML =
                                '<div class="alert alert-warning">Tidak ada kelas ditemukan.</div>';
                            return;
                        }

                        let html =
                            `<div class="card"><div class="card-body"><h6>Kelas Tersedia</h6><ul class="list-group">`;
                        data.forEach(kelas => {
                            html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${kelas.nama_kelas}
                            <button class="btn btn-sm btn-primary" onclick="loadSantri(${kelas.id_kelas})">Lihat</button>
                        </li>`;
                        });
                        html += `</ul></div></div>`;

                        document.getElementById('kelasList').innerHTML = html;
                        document.getElementById('santriTable').innerHTML = '';
                    });
            }

            function loadSantri(kelasId) {
                const sekolah = document.getElementById('sekolahSelect').value;

                fetch(`/nilai/santri?kelas=${kelasId}&sekolah=${sekolah}`)
                    .then(res => res.json())
                    .then(data => {
                        const btnCetak = document.getElementById('btnCetakSemua');

                        if (data.length === 0) {
                            document.getElementById('santriTable').innerHTML =
                                '<div class="alert alert-warning">Tidak ada santri di kelas ini.</div>';
                            btnCetak.classList.add('d-none');
                            return;
                        }

                        // Tampilkan tombol cetak semua
                        btnCetak.classList.remove('d-none');
                        btnCetak.href = `/rapor/cetak-semua?kelas=${kelasId}&sekolah=${sekolah}`;

                        let html = `<div class="card"><div class="card-body"><h6>Daftar Santri</h6>
            <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Santri</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>`;

                        data.forEach((s, index) => {
                            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${s.nama_santri}</td>
                    <td><button class="btn btn-info btn-sm" onclick="showDetail(${s.detail_id})">Detail</button></td>
                </tr>`;
                        });

                        html += `</tbody></table></div></div>`;
                        document.getElementById('santriTable').innerHTML = html;
                    });
            }



            function showDetail(detailId) {
                fetch(`/nilai/detail/${detailId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            document.getElementById('modalNilaiContent').innerHTML =
                                '<p class="text-muted">Belum ada nilai untuk santri ini.</p>';
                        } else {
                            let html = `<table class="table table-bordered"><thead><tr>
                    <th>Mata Pelajaran</th><th>Sumatif</th><th>PAS</th><th>PAT</th><th>Tahun Akademik</th>
                </tr></thead><tbody>`;

                            let total = 0;
                            let jumlah = 0;

                            data.forEach(nilai => {
                                let sumatif = parseFloat(nilai.sumatif);
                                let pas = parseFloat(nilai.pas);
                                let pat = parseFloat(nilai.pat);

                                let nilaiValid = !isNaN(sumatif) && !isNaN(pas) && !isNaN(pat);
                                let rataMapel = nilaiValid ? (sumatif + pas + pat) / 3 : 0;

                                if (nilaiValid) {
                                    total += rataMapel;
                                    jumlah++;
                                }

                                html += `<tr>
                        <td>${nilai.mapel ?? '-'}</td>
                        <td class="text-center">${isNaN(sumatif) ? '-' : sumatif}</td>
                        <td class="text-center">${isNaN(pas) ? '-' : pas}</td>
                        <td class="text-center">${isNaN(pat) ? '-' : pat}</td>
                        <td>${nilai.tahun ?? '-'}</td>
                    </tr>`;
                            });

                            html += `</tbody></table>`;

                            let rata2 = jumlah > 0 ? (total / jumlah).toFixed(2) : '-';
                            let predikat = '-';
                            let deskripsi = '-';

                            if (rata2 !== '-') {
                                let r = parseFloat(rata2);
                                if (r >= 90) {
                                    predikat = 'A';
                                    deskripsi =
                                        'Siswa menunjukkan pemahaman yang sangat baik dalam materi pelajaran. Kemampuan analisis dan pemecahan masalahnya sangat baik. Terus pertahankan dan kembangkan potensi yang dimiliki.';
                                } else if (r >= 80) {
                                    predikat = 'B';
                                    deskripsi =
                                        'Siswa telah mencapai pemahaman yang baik dalam materi pelajaran. Perlu ditingkatkan lagi dalam pemecahan masalah atau analisis untuk mencapai hasil yang lebih optimal.';
                                } else if (r >= 70) {
                                    predikat = 'C';
                                    deskripsi =
                                        'Siswa perlu lebih berusaha dalam memahami materi pelajaran. Perlu bimbingan lebih lanjut dalam penguasaan konsep agar dapat mencapai hasil yang lebih baik.';
                                } else if (r >= 60) {
                                    predikat = 'D';
                                    deskripsi =
                                        'Siswa memerlukan bimbingan intensif untuk memahami materi pelajaran. Perlu perhatian khusus dalam penguasaan materi agar dapat mengikuti pembelajaran dengan baik.';
                                } else {
                                    predikat = 'E';
                                    deskripsi =
                                        'Siswa sangat membutuhkan pendampingan khusus karena belum mampu memahami materi secara memadai. Diperlukan strategi belajar yang lebih tepat.';
                                }
                            }

                            html += `
                <div class="mt-3">
                    <p><strong>Rata-rata:</strong> ${rata2}</p>
                    <p><strong>Predikat:</strong> ${predikat}</p>
                    <p><strong>Deskripsi:</strong> ${deskripsi}</p>
                    <a href="/rapor/cetak/${detailId}" target="_blank" class="btn btn-success mt-2">
                        <i class="bi bi-printer"></i> Cetak Rapor
                    </a>
                </div>`;

                            document.getElementById('modalNilaiContent').innerHTML = html;
                        }

                        new bootstrap.Modal(document.getElementById('modalNilai')).show();
                    });
            }




            document.getElementById('tahunSelect').addEventListener('change', loadKelas);
            document.getElementById('sekolahSelect').addEventListener('change', loadKelas);
        </script>
    @endsection
