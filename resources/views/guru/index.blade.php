@extends('layouts.app')

@section('title', 'Daftar Guru')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Guru</h5>

                <div class="d-flex justify-content-between mb-4">
                    <button class="btn btn-success rounded-pill mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahGuru">
                        <i class="ti ti-plus"></i> Tambah Guru
                    </button>

                    <form action="{{ route('guru.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="search" class="form-control rounded-pill px-4 py-2"
                            style="width: 200px" placeholder="Cari Data Guru..." value="{{ request('search') }}">
                    </form>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            {{-- <th>No</th> --}}
                            <th>Aksi</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>No WhatsApp</th>
                        </tr>
                    </thead>
                    <tbody id="guru-table-body">
                        @include('guru.partials.table', ['gurus' => $gurus])
                    </tbody>
                </table>

                <div id="pagination-links">
                    @if ($gurus->hasPages())
                        {!! $gurus->links('pagination::bootstrap-5') !!}
                    @endif
                </div>
            </div>
        </div>
        <!-- Modal Tambah Guru -->
        <div class="modal fade" id="modalTambahGuru" tabindex="-1" aria-labelledby="modalTambahGuruLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalTambahSantriLabel">Tambah Data Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('guru.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">NIP</label>
                                    <input type="text" name="nip" class="form-control" required
                                        placeholder="Isi NIP">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Nama Guru</label>
                                    <input type="text" name="nama_guru" class="form-control" required
                                        placeholder="Isi Nama Guru">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenkel" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required placeholder="Ex: Desa-Kecamatan-Kabupaten"></textarea>
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

        <!-- Modal Edit Guru -->
        <div class="modal fade" id="modalEditGuru" tabindex="-1" aria-labelledby="modalEditGuruLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalEditGuruLabel">Edit Data Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <form id="formEditGuru" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">NIP</label>
                                    <input type="text" name="nip" id="edit_nip" class="form-control" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Nama Guru</label>
                                    <input type="text" name="nama_guru" id="edit_nama_guru" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir" id="edit_tgl_lahir" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenkel" id="edit_jenkel" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" id="edit_alamat" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-md-12">
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
            const tableBody = document.getElementById('guru-table-body');
            const paginationLinks = document.getElementById('pagination-links');
            let debounceTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    fetchData(
                        `${searchForm.action}?search=${encodeURIComponent(searchInput.value)}`);
                }, 300);
            });

            function fetchData(url) {
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
                    .catch(error => console.error('Pagination/Search error:', error));
            }


            $(document).on('click', '.btn-edit-guru', function() {
                const data = $(this).data();

                $('#formEditGuru').attr('action', `/guru-update/${data.id}`);
                $('#edit_nip').val(data.nip);
                $('#edit_nama_guru').val(data.nama);
                $('#edit_tgl_lahir').val(data.tgl);
                $('#edit_jenkel').val(data.jenkel);
                $('#edit_alamat').val(data.alamat);
                $('#edit_no_hp').val(data.nohp);

                $('#modalEditGuru').modal('show');
            });


            paginationLinks.addEventListener('click', function(event) {
                if (event.target.tagName === 'A') {
                    event.preventDefault();
                    const url = new URL(event.target.href);
                    const params = {
                        search: searchInput.value,
                        sekolah: searchForm.querySelector('input[name="sekolah"]') ? searchForm
                            .querySelector('input[name="sekolah"]').value : ''
                    };
                    // Merge search and sekolah params with pagination url params
                    for (const [key, value] of url.searchParams.entries()) {
                        params[key] = value;
                    }
                    const fetchUrl = buildUrl(url.pathname, params);

                    fetch(fetchUrl, {
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
