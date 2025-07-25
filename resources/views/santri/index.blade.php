@extends('layouts.app')

@section('title', 'Halaman Data Santri')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Data Santri</h5>

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-success rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#modalTambahSantri">
                            <i class="ti ti-plus"></i> Tambah Santri
                        </button>

                          <a href="{{ route('pelanggaran.template') }}" class="btn btn-info rounded-pill">
                            <i class="ti ti-download"></i> Download Template
                        </a>
                        <button class="btn btn-secondary rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#modalUploadExcel">
                            <i class="ti ti-upload"></i> Upload Excel
                        </button>
                    </div>

                    <form action="{{ route('santri.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-pill"
                                placeholder="Cari Nama Santri..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                <!-- Tabel santri -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover shadow-sm">
                        <thead>
                            <tr class="text-center">
                                <th>Aksi</th>
                                {{-- <th>No</th> --}}
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody id="santri-table-body">
                            @include('santri.partials.table', ['santris' => $santris])
                        </tbody>
                    </table>

                    <div id="pagination-links">
                        @include('santri.partials.pagination', ['santris' => $santris])
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal Tambah Santri -->
        <div class="modal fade" id="modalTambahSantri" tabindex="-1" aria-labelledby="modalTambahSantriLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalTambahSantriLabel">Tambah Data Santri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">NISN</label>
                                    <input type="text" name="nisn" class="form-control" required
                                        placeholder="Isi NISN">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Nama Santri</label>
                                    <input type="text" name="nama_santri" class="form-control" required
                                        placeholder="Isi Nama Santri">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" required
                                        placeholder="Isi Tempat Lahir">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Foto Santri <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="file" name="foto" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required placeholder="Ex:Desa-Kecamatan-Kabupaten"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ayah</label>
                                    <input type="text" name="ayah" class="form-control" required
                                        placeholder="Isi Nama Ayah">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ibu</label>
                                    <input type="text" name="ibu" class="form-control" required
                                        placeholder="Isi Nama Ibu">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No WhatsApp</label>
                                    <input type="tel" name="no_hp" class="form-control" required
                                        placeholder="Isi No WhatsApp">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Santri -->
        <div class="modal fade" id="modalEditSantri" tabindex="-1" aria-labelledby="modalEditSantriLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalEditSantriLabel">Edit Data Santri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <form id="formEditSantri" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">NISN</label>
                                    <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Nama Santri</label>
                                    <input type="text" name="nama_santri" id="edit_nama_santri" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="edit_tempat_lahir"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="edit_jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Ganti Foto <small class="text-muted">(Opsional)</small>
                                    </label>
                                    <input type="file" name="foto" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" id="edit_alamat" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ayah</label>
                                    <input type="text" name="ayah" id="edit_ayah" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ibu</label>
                                    <input type="text" name="ibu" id="edit_ibu" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No WhatsApp</label>
                                    <input type="tel" name="no_hp" id="edit_no_hp" class="form-control" required>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const searchForm = searchInput.closest('form');
            const tableBody = document.getElementById('santri-table-body');
            const paginationLinks = document.getElementById('pagination-links');
            let debounceTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    fetch(`${searchForm.action}?search=${encodeURIComponent(searchInput.value)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            tableBody.innerHTML = data.table;
                            paginationLinks.innerHTML = data.pagination;
                        })
                        .catch(error => {
                            console.error('Error fetching search results:', error);
                        });
                }, 300);
            });

            $(document).on('click', '.btn-edit-santri', function() {
                const data = $(this).data();

                $('#edit_nisn').val(data.nisn);
                $('#edit_nama_santri').val(data.nama);
                $('#edit_tempat_lahir').val(data.tempat);
                $('#edit_tanggal_lahir').val(data.tanggal);
                $('#edit_jenis_kelamin').val(data.jk);
                $('#edit_alamat').val(data.alamat);
                $('#edit_ayah').val(data.ayah);
                $('#edit_ibu').val(data.ibu);
                $('#edit_no_hp').val(data.hp);

                const updateUrl = `/santri/${data.nis}`;
                $('#formEditSantri').attr('action', updateUrl);

                $('#modalEditSantri').modal('show');
            });


            document.addEventListener('click', function(event) {
                if (event.target.closest('#pagination-links a')) {
                    event.preventDefault();
                    const url = event.target.closest('a').href;
                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            tableBody.innerHTML = data.table;
                            paginationLinks.innerHTML = data.pagination;
                        })
                        .catch(error => {
                            console.error('Error fetching pagination results:', error);
                        });
                }
            });

        });
    </script>
@endsection
<!-- Modal Upload Excel -->
<div class="modal fade" id="modalUploadExcel" tabindex="-1" aria-labelledby="modalUploadExcelLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('santri.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadExcelLabel">Upload Data Santri dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Pilih File Excel (.xlsx)</label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <div class="text-muted">
                        Pastikan format file sesuai dengan <a href="{{ route('santri.template') }}">template ini</a>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill">
                        <i class="ti ti-upload me-1"></i> Upload
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
