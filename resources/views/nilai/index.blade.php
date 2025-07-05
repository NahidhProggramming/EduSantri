@extends('layouts.app')
@section('title', 'Lihat Nilai Santri')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold mb-4">Lihat Nilai Santri per Kelas</h5>

        <div class="row">
            {{-- Sidebar Filter --}}
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold mb-3">Filter Data</h6>

                    <div class="mb-3">
                        <label class="form-label">Tahun Akademik</label>
                        <select id="tahunSelect" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach ($tahunList as $t)
                                <option value="{{ $t->id_tahun }}">{{ $t->tahun_akademik }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sekolah</label>
                        <select id="sekolahSelect" class="form-select" onchange="loadKelas()">
                            <option value="">-- Pilih --</option>
                            @foreach ($sekolahList as $s)
                                <option value="{{ $s->id_sekolah }}">{{ $s->nama_sekolah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select id="kelasSelect" class="form-select">
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>

                    <button class="btn btn-primary w-100" onclick="loadSantri()">Tampilkan</button>
                </div>
            </div>

            {{-- Tabel Santri --}}
            <div class="col-md-8">
                <div id="tableContainer" class="card shadow-sm p-3 d-none">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Santri</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="santriTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Detail --}}
        <div class="modal fade" id="modalDetailNilai" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Nilai Santri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Sumatif</th>
                                    <th>PAS</th>
                                    <th>PAT</th>
                                </tr>
                            </thead>
                            <tbody id="nilaiDetailBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        function loadKelas() {
            let tahun = document.getElementById('tahunSelect').value;
            let sekolah = document.getElementById('sekolahSelect').value;

            if (!tahun || !sekolah) {
                console.warn('Tahun atau Sekolah belum dipilih');
                return;
            }

            console.log(`MENGAMBIL KELAS: tahun=${tahun}, sekolah=${sekolah}`);

            fetch(`/nilai/kelas?tahun=${tahun}&sekolah=${sekolah}`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('RESPON DARI SERVER:', data);

                    let options = '<option value="">-- Pilih --</option>';
                    data.forEach(k => {
                        options += `<option value="${k.id_kelas}">${k.nama_kelas}</option>`;
                    });
                    document.getElementById('kelasSelect').innerHTML = options;
                })
                .catch(err => {
                    console.error('GAGAL MENGAMBIL KELAS:', err);
                });
        }

        function loadSantri() {
            const tahun = document.getElementById('tahunSelect').value;
            const sekolah = document.getElementById('sekolahSelect').value;
            const kelas = document.getElementById('kelasSelect').value;

            if (!tahun || !sekolah || !kelas) return;

            fetch(`/nilai/santri?tahun=${tahun}&sekolah=${sekolah}&kelas=${kelas}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('santriTableBody');
                    tbody.innerHTML = '';

                    data.forEach((s, i) => {
                        tbody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${s.nama_santri}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="lihatNilai(${s.detail_id})">Detail</button>
                        </td>
                    </tr>`;
                    });

                    document.getElementById('tableContainer').classList.remove('d-none');
                });
        }

        function lihatNilai(detailId) {
            fetch(`/nilai/detail/${detailId}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('nilaiDetailBody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="text-center">Belum ada nilai</td></tr>`;
                    } else {
                        data.forEach(n => {
                            tbody.innerHTML += `
                        <tr>
                            <td>${n.nama_mapel}</td>
                            <td>${n.nama_guru}</td>
                            <td>${n.nilai_sumatif ?? '-'}</td>
                            <td>${n.nilai_pas ?? '-'}</td>
                            <td>${n.nilai_pat ?? '-'}</td>
                        </tr>`;
                        });
                    }

                    new bootstrap.Modal(document.getElementById('modalDetailNilai')).show();
                });
        }
    </script>
@endsection
