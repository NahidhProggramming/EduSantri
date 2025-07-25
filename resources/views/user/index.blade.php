@extends('layouts.app')

@section('title', 'Halaman Data User')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Data User</h5>

                <!-- Filter Role & Search -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                    <div class="d-flex gap-2 flex-wrap">
                        <!-- Filter Role -->
                        <form action="{{ route('users.index') }}" method="GET" class="d-flex align-items-center">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 rounded-pill shadow-sm px-3">
                                    <i class="ti ti-filter"></i>
                                </span>
                                <select name="role" id="role"
                                    class="form-select form-select-sm rounded-pill shadow-sm" onchange="this.form.submit()">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="wali_santri" {{ request('role') == 'wali_santri' ? 'selected' : '' }}>
                                        Wali Santri</option>
                                    <option value="kesiswaan" {{ request('role') == 'kesiswaan' ? 'selected' : '' }}>
                                        Kesiswaan</option>
                                </select>
                            </div>
                        </form>

                        <!-- Tombol Tambah -->
                        <button class="btn btn-success rounded-pill px-4 py-2" data-bs-toggle="modal"
                            data-bs-target="#modalTambahUser">
                            <i class="ti ti-plus me-2"></i>Tambah User
                        </button>
                    </div>

                    <!-- Search -->
                    <form action="{{ route('user.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-pill shadow-sm"
                                placeholder="Cari Nama User..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>



                <!-- Tabel User -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover shadow-sm">
                        <thead>
                            <tr class="text-center">
                                {{-- <th>No</th> --}}
                                 <th>Aksi</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @include('user.partials.table', ['users' => $users])
                        </tbody>
                    </table>

                    <div id="pagination-links">
                        @include('user.partials.pagination', ['users' => $users])
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Tambah User -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahUserLabel">Tambah Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" required
                                    placeholder="Nama Lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required placeholder="Username">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="Email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="guru">Guru</option>
                                    <option value="wali_santri">Wali Santri</option>
                                    <option value="kesiswaan">Kesiswaan</option>
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
    <!-- Modal Edit User -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditUserLabel">Edit Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="formEditUser" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" id="edit_username" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" id="edit_role" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="guru">Guru</option>
                                    <option value="wali_santri">Wali Santri</option>
                                    <option value="kesiswaan">Kesiswaan</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Password Baru <small class="text-muted">(Kosongkan jika tidak
                                        diubah)</small></label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Password baru (opsional)">
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
            const tableBody = document.getElementById('user-table-body');
            const paginationLinks = document.getElementById('pagination-links');
            let debounceTimeout;


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
            $(document).on('click', '.btn-edit-user', function() {
                const data = $(this).data();

                $('#edit_name').val(data.name);
                $('#edit_username').val(data.username);
                $('#edit_email').val(data.email);
                $('#edit_role').val(data.role);

                let updateUrl = `/users/${data.id}`;
                $('#formEditUser').attr('action', updateUrl);

                $('#modalEditUser').modal('show');
            });

        });
    </script>
@endsection
