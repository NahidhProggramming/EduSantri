@extends('layouts.app')

@section('title', 'Daftar Tahun Akademik')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Tahun Akademik</h5>

                <div class="d-flex justify-content-between mb-4">
                    <button type="button" class="btn btn-success rounded-pill px-4 py-2" data-bs-toggle="modal"
                        data-bs-target="#modalTambahAkademik">
                        <i class="ti ti-plus me-2"></i>Tambah Tahun Akademik
                    </button>

                    <!-- Form Search -->
                    <form action="{{ route('akademik.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-pill"
                                placeholder="Cari Tahun Akademik..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Aksi</th>
                            <th>Keterangan</th>
                            {{-- <th>No</th> --}}
                            <th>Tahun Akademik</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody id="akademik-table-body">
                        @include('tahun_akademik.partials.table', ['akademiks' => $akademiks])
                    </tbody>
                </table>
                <div id="pagination-links">
                    @include('tahun_akademik.partials.pagination', ['akademiks' => $akademiks])
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Tahun Akademik -->
    <div class="modal fade" id="modalTambahAkademik" tabindex="-1" aria-labelledby="modalTambahAkademikLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahAkademikLabel">Tambah Data Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('akademik.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tahun Akademik</label>
                                <input type="text" name="tahun_akademik" placeholder="ex: 2025/2026" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Semester</label>
                                <select name="semester" id="semester" class="form-select" required>
                                    <option value="">Pilih Semester</option>
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Keterangan</label>
                                <select name="semester_aktif" id="semester_aktif" class="form-select" required>
                                    <option value="">Pilih Keterangan</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Edit Tahun Akademik -->
    <div class="modal fade" id="modalEditAkademik" tabindex="-1" aria-labelledby="modalEditAkademikLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditAkademikLabel">Edit Data Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="formEditAkademik" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Semester</label>
                                <select name="semester" id="edit_semester" class="form-select" required>
                                    <option value="">Pilih Semester</option>
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Keterangan</label>
                                <select name="semester_aktif" id="edit_semester_aktif" class="form-select" required>
                                    <option value="">Pilih Keterangan</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const searchForm = searchInput.closest('form');
            const tableBody = document.getElementById('akademik-table-body');
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

            $(document).on('click', '.btn-edit-akademik', function() {
                const data = $(this).data();

                $('#edit_semester').val(data.semester);
                $('#edit_semester_aktif').val(data.keterangan);

                const updateUrl =
                    `/akademik-update/${data.id}`;
                $('#formEditAkademik').attr('action', updateUrl);

                $('#modalEditAkademik').modal('show');
            });


            // Handle pagination link clicks
            paginationLinks.addEventListener('click', function(event) {
                if (event.target.tagName === 'A') {
                    event.preventDefault();
                    const url = event.target.href;
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
