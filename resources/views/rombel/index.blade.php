@extends('layouts.app')
@section('title', 'Rombongan Belajar')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold mb-4">Halaman Rombongan Belajar Berdasarkan Tahun Akademik</h5>
        {{-- 🔘 Tombol Tampilkan Panduan --}}
        <div class="mb-3">
            <button class="btn btn-info rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTutorialRombel">
                <i class="ti ti-help"></i> Lihat Tutorial Rombel
            </button>
        </div>

        {{-- Tombol Tahun Akademik --}}
        <div class="row mb-4">
            @foreach ($tahunList as $tahun)
                <div class="col-md-3">
                    <div class="card text-center shadow-sm border-0"
                        style="cursor: pointer; background-color: #13DEB9; color: #ffffff;"
                        onclick="selectTahun({{ $tahun->id_tahun_akademik }}, '{{ $tahun->tahun_akademik }}')">
                        <div class="card-body py-3">
                            <h5 class="fw-bold text-white">{{ $tahun->tahun_akademik }}</h5>
                            <span style="color: rgba(255,255,255,0.85); font-size: 14px;">Pilih Tahun</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Kontainer Rombel --}}
        <div id="rombel-container" class="card d-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold" id="rombel-title"></h5>
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalRombel">
                        Tambah Rombongan Belajar
                    </button>
                </div>

                <div class="row">
                    {{-- MTs --}}
                    <div class="col-md-6">
                        <h6 class="fw-semibold">MTs</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Jumlah Santri</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="rombel-body-mts" class="text-center"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- SMP --}}
                    <div class="col-md-6">
                        <h6 class="fw-semibold">SMP</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Jumlah Santri</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="rombel-body-smp" class="text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div class="modal fade" id="modalRombel" tabindex="-1" aria-labelledby="modalRombelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rombel.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun_akademik_id" value="{{ $tahun->id_tahun_akademik }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRombelLabel">Tambah Rombongan Belajar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="sekolah_id">Sekolah</label>
                            <select name="sekolah_id" id="sekolah_id" class="form-control" required>
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($sekolah as $s)
                                    <option value="{{ $s->id_sekolah }}">{{ $s->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="santri_id" class="form-label">Pilih Santri</label>

                            {{-- Checkbox Pilih Semua --}}
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="checkAllSantri">
                                <label class="form-check-label fw-bold" for="checkAllSantri">
                                    Pilih Semua
                                </label>
                            </div>

                            <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                                @foreach ($santri as $s)
                                    <div class="form-check">
                                        <input class="form-check-input santri-checkbox" type="checkbox" name="santri_id[]"
                                            value="{{ $s->nis }}" id="santri_{{ $s->nis }}">
                                        <label class="form-check-label" for="santri_{{ $s->nis }}">
                                            {{ $s->nama_santri }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <small class="text-muted">Centang beberapa santri yang ingin dimasukkan ke rombel.</small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Naik Kelas -->
    <div class="modal fade" id="modalNaikKelas" tabindex="-1" aria-labelledby="modalNaikKelasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rombel.naikKelas') }}" method="POST">
                    @csrf
                    <input type="hidden" name="detail_id" id="detail_id_naikkelas">
                    <input type="hidden" name="tahun_akademik_id_lama" value="{{ $tahun->id_tahun_akademik }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNaikKelasLabel">Naikkan Siswa ke Kelas Berikutnya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kelas_tujuan" class="form-label">Pilih Kelas Tujuan</label>
                            <select name="kelas_tujuan_id" id="kelas_tujuan" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Santri yang Akan Dinaikkan</label>
                            <div class="border rounded p-2" id="list-santri-naik"
                                style="max-height: 300px; overflow-y: auto;">
                                <p class="text-muted">Memuat data santri...</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Naikkan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Hapus Santri -->
    <div class="modal fade" id="modalHapusSantri" tabindex="-1" aria-labelledby="modalHapusSantriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rombel.hapusSantri') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="detail_id" id="detail_id_hapussantri">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusSantriLabel">Hapus Santri dari Rombel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">Pilih santri yang ingin dihapus dari rombel ini:</p>
                        <div class="border rounded p-2" id="list-santri-hapus"
                            style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted">Memuat data santri...</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Hapus Terpilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Tutorial Rombel -->
    <div class="modal fade" id="modalTutorialRombel" tabindex="-1" aria-labelledby="tutorialRombelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="tutorialRombelLabel">
                        <i class="ti ti-info-circle"></i> Panduan Penggunaan Menu Rombongan Belajar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <ol class="lh-lg">
                        <li><strong>Pilih Tahun Akademik</strong> dari daftar tombol yang tersedia.</li>
                        <li><strong>Lihat data Rombongan Belajar</strong> berdasarkan sekolah MTs atau SMP.</li>
                        <li>Klik <strong>Tambah Rombongan Belajar</strong> untuk menambahkan:
                            <ul class="mt-1">
                                <li>Pilih sekolah</li>
                                <li>Pilih kelas</li>
                                <li>Centang santri yang ingin dimasukkan</li>
                            </ul>
                        </li>
                        <li>Gunakan tombol <span class="badge bg-danger"><i class="ti ti-trash"></i></span> untuk
                            menghapus santri dari rombel tertentu.</li>
                        <li>Gunakan tombol <span class="badge bg-primary"><i class="ti ti-arrow-up-circle"></i></span>
                            untuk <strong>menaikkan kelas santri</strong> ke tahun ajaran berikutnya.</li>
                    </ol>
                    <p class="text-muted mt-3">
                        Pastikan data tahun akademik dan daftar kelas sudah tersedia sebelum memulai pembagian rombel.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Script --}}
    <script>
        let selectedTahunId = null;
        let selectedTahunText = '';

        function selectTahun(tahunId, tahunText) {
            selectedTahunId = tahunId;
            selectedTahunText = tahunText;

            // Reset tampilan
            document.getElementById('rombel-container').classList.add('d-none');
            document.getElementById('rombel-body-mts').innerHTML = '';
            document.getElementById('rombel-body-smp').innerHTML = '';
            document.getElementById('rombel-title').textContent = '';

            fetch(`/rombongan-belajar?tahun=${tahunId}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('rombel-container');
                    const title = document.getElementById('rombel-title');
                    // const btn = document.getElementById('btnTambahRombel');

                    title.textContent = `Data Rombongan Belajar Tahun Akademik ${selectedTahunText}`;
                    // btn.href = `/rombongan-belajar/create?tahun=${tahunId}`;
                    container.classList.remove('d-none');

                    const rombelMts = document.getElementById('rombel-body-mts');
                    const rombelSmp = document.getElementById('rombel-body-smp');

                    let noMts = 1;
                    let noSmp = 1;

                    let hasMts = false;
                    let hasSmp = false;

                    data.forEach(item => {
                        let row = '';

                        if (item.nama_sekolah === 'MTs') {
                            row = `
            <tr>
                <td>${noMts++}</td>
                <td>${item.nama_kelas}</td>
                <td>${item.jumlah_siswa}</td>
                <td class="text-center">
                   <button type="button" class="btn btn-sm btn-danger" title="Hapus Santri"
                        onclick="bukaHapusSantri(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}')">
                        <i class="ti ti-trash"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" title="Naik Kelas"
                        onclick="bukaNaikKelas(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}')">
                        <i class="ti ti-arrow-up-circle"></i>
                    </button>
                </td>
            </tr>
        `;
                            rombelMts.innerHTML += row;
                            hasMts = true;
                        } else if (item.nama_sekolah === 'SMP') {
                            row = `
            <tr>
                <td>${noSmp++}</td>
                <td>${item.nama_kelas}</td>
                <td>${item.jumlah_siswa}</td>
               <td class="text-center">
                     <button type="button" class="btn btn-sm btn-danger" title="Hapus Santri"
                        onclick="bukaHapusSantri(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}')">
                        <i class="ti ti-trash"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" title="Naik Kelas"
                        onclick="bukaNaikKelas(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}')">
                        <i class="ti ti-arrow-up-circle"></i>
                    </button>
                </td>
            </tr>
        `;
                            rombelSmp.innerHTML += row;
                            hasSmp = true;
                        }
                    });


                    if (!hasMts) {
                        rombelMts.innerHTML = `<tr><td colspan="4" class="text-muted">Belum ada data MTs</td></tr>`;
                    }
                    if (!hasSmp) {
                        rombelSmp.innerHTML = `<tr><td colspan="4" class="text-muted">Belum ada data SMP</td></tr>`;
                    }
                });
        }

        function bukaNaikKelas(detailId, kelasNama, sekolahNama) {
            document.getElementById('detail_id_naikkelas').value = detailId;
            document.getElementById('list-santri-naik').innerHTML = '<p class="text-muted">Memuat data santri...</p>';

            // Ambil santri berdasarkan rombel detail
            fetch(`/rombongan-belajar/santri/${detailId}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<p class="text-muted">Tidak ada santri dalam rombel ini.</p>';
                    } else {
                        data.forEach(s => {
                            html += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="santri_id[]" value="${s.nis}" id="naik_${s.nis}">
                            <label class="form-check-label" for="naik_${s.nis}">${s.nama_santri}</label>
                        </div>
                    `;
                        });
                    }
                    document.getElementById('list-santri-naik').innerHTML = html;
                });

            // Tampilkan modal
            var modal = new bootstrap.Modal(document.getElementById('modalNaikKelas'));
            modal.show();
        }

        function bukaHapusSantri(detailId, namaKelas, namaSekolah) {
            document.getElementById('detail_id_hapussantri').value = detailId;
            document.getElementById('list-santri-hapus').innerHTML = '<p class="text-muted">Memuat data santri...</p>';

            fetch(`/rombongan-belajar/santri/${detailId}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<p class="text-muted">Tidak ada santri dalam rombel ini.</p>';
                    } else {
                        data.forEach(s => {
                            html += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="santri_id[]" value="${s.nis}" id="hapus_${s.nis}">
                        <label class="form-check-label" for="hapus_${s.nis}">${s.nama_santri}</label>
                    </div>
                    `;
                        });
                    }
                    document.getElementById('list-santri-hapus').innerHTML = html;
                });

            var modal = new bootstrap.Modal(document.getElementById('modalHapusSantri'));
            modal.show();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAllSantri');
            const checkboxes = document.querySelectorAll('.santri-checkbox');

            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', function() {
                        checkAll.checked = document.querySelectorAll('.santri-checkbox:checked')
                            .length === checkboxes.length;
                    });
                });
            }
        });

        function togglePanduan() {
            const panduan = document.getElementById('panduanRombel');
            panduan.classList.toggle('d-none');
        }
    </script>
@endsection
