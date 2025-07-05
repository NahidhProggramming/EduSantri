@extends('layouts.app')

@section('title', 'Daftar Sekolah')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Sekolah</h5>

                <div class="d-flex justify-content-between mb-4">
                    <button class="btn btn-success rounded-pill mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalTambahSekolah">
                        <i class="ti ti-plus"></i> Tambah Sekolah
                    </button>

                     <form action="{{ route('sekolah.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="search" class="form-control rounded-pill px-4 py-2"
                            style="width: 200px" placeholder="Cari Sekolah..." value="{{ request('search') }}">
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
                            <th>No</th>
                            <th>Nama Sekolah</th>
                        </tr>
                    </thead>
                    <tbody id="sekolah-table-body">
                        @include('sekolah.partials.table', ['sekolahs' => $sekolahs])
                    </tbody>
                </table>
                <div id="pagination-links">
                    @include('sekolah.partials.pagination', ['sekolahs' => $sekolahs])
                </div>
            </div>
        </div>

        {{-- Modal Tambah Kelas --}}
        <div class="modal fade" id="modalTambahSekolah" tabindex="-1" aria-labelledby="modalTambahSekolahLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('sekolah.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahSekolahLabel">Tambah Sekolah</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_sekolah" class="form-label">Nama sekolah</label>
                                <input type="text" name="nama_sekolah" class="form-control" id="nama_sekolah" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit per Kelas --}}
        @foreach ($sekolahs as $sekolah)
            <div class="modal fade" id="modalEditSekolah{{ $sekolah->id_sekolah }}" tabindex="-1"
                aria-labelledby="modalEditSekolahLabel{{ $sekolah->id_sekolah }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('sekolah.update', $sekolah->id_sekolah) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditSekolahLabel{{ $sekolah->id_sekolah }}">Edit Sekolah</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah" class="form-control"
                                        value="{{ $sekolah->nama_sekolah }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-warning">Update</button>
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
            const tableBody = document.getElementById('sekolah-table-body');
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
