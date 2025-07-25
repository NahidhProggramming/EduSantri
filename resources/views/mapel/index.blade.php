@extends('layouts.app')

@section('title', 'Daftar Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Mata Pelajaran</h5>

                <div class="d-flex justify-content-between mb-4">
                    <button class="btn btn-success rounded-pill mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalTambahMapel">
                        <i class="ti ti-plus"></i> Tambah Mata Pelajaran
                    </button>
                    <form action="{{ route('mapel.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="search" class="form-control rounded-pill px-4 py-2"
                            style="width: 200px" placeholder="Cari Mata Pelajaran..." value="{{ request('search') }}">
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
                            {{-- <th>No</th> --}}
                            <th>Aksi</th>
                            <th>Nama Mapel</th>
                        </tr>
                    </thead>
                    <tbody id="mapel-table-body">
                        @include('mapel.partials.table', ['mapels' => $mapels])
                    </tbody>
                </table>

                <div id="pagination-links">
                    @if ($mapels->hasPages())
                        {!! $mapels->links('pagination::bootstrap-5') !!}
                    @endif
                </div>

            </div>
        </div>
        <!-- Modal Tambah Mapel -->
        <div class="modal fade" id="modalTambahMapel" tabindex="-1" aria-labelledby="modalTambahMapelLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('mapel.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahMapelLabel">Tambah Mata Pelajaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_mapel" class="form-label">Nama Mata Pelajaran</label>
                                <input type="text" name="nama_mapel" class="form-control" id="nama_mapel" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Mapel (per Mapel) -->
        @foreach ($mapels as $mapel)
            <div class="modal fade" id="modalEditMapel{{ $mapel->id_mapel }}" tabindex="-1"
                aria-labelledby="modalEditMapelLabel{{ $mapel->id_mapel }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('mapel.update', $mapel->id_mapel) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditMapelLabel{{ $mapel->id_mapel }}">Edit Mata Pelajaran
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_mapel{{ $mapel->id_mapel }}" class="form-label">Nama Mata
                                        Pelajaran</label>
                                    <input type="text" name="nama_mapel" class="form-control"
                                        id="nama_mapel{{ $mapel->id_mapel }}" value="{{ $mapel->nama_mapel }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const searchForm = searchInput.closest('form');
            const tableBody = document.getElementById('mapel-table-body');
            const paginationLinks = document.getElementById('pagination-links');
            let debounceTimeout;

            // Search input
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    fetchData(
                        `${searchForm.action}?search=${encodeURIComponent(searchInput.value)}`);
                }, 300);
            });

            // Pagination link clicks
            paginationLinks.addEventListener('click', function(event) {
                if (event.target.tagName === 'A') {
                    event.preventDefault();
                    fetchData(event.target.href);
                }
            });

            // Fetch data function
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

            // Edit Mapel click handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-edit-mapel')) {
                    const btn = e.target.closest('.btn-edit-mapel');
                    const data = btn.dataset;

                    // Set form action & values
                    document.getElementById('formEditMapel').setAttribute('action',
                        `/mapel-update/${data.id}`);
                    document.getElementById('edit_nama_mapel').value = data.nama;

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('modalEditMapel'));
                    modal.show();
                }
            });
        });
    </script>

@endsection
