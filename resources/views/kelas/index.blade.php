@extends('layouts.app')

@section('title', 'Daftar Kelas')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Kelas</h5>

                <div class="d-flex justify-content-between mb-4">
                    <button class="btn btn-success rounded-pill mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalTambahKelas">
                        <i class="ti ti-plus"></i> Tambah Kelas
                    </button>
                    <form action="{{ route('kelas.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="search" class="form-control rounded-pill px-4 py-2"
                            style="width: 200px" placeholder="Cari Kelas..." value="{{ request('search') }}">
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
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            {{-- <th>No</th> --}}
                            <th>Aksi</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                        </tr>
                    </thead>
                    <tbody id="kelas-table-body">
                        @include('kelas.partials.table', ['kelass' => $kelass])
                    </tbody>
                </table>

                <div id="pagination-links">
                    @if ($kelass->hasPages())
                        {!! $kelass->links('pagination::bootstrap-5') !!}
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal Tambah Kelas --}}
        <div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-labelledby="modalTambahKelasLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('kelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKelasLabel">Tambah Kelas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                <input type="text" name="nama_kelas" class="form-control" id="nama_kelas" required>
                            </div>
                            <div class="mb-3">
                                <label for="tingkat" class="form-label">Tingkat</label>
                                <input type="text" name="tingkat" class="form-control" id="tingkat" required>
                            </div>
                            <div class="mb-3">
                                <label for="wali_kelas_id" class="form-label">Wali Kelas (Guru)</label>
                                <select name="wali_kelas_id" class="form-select" id="wali_kelas_id">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach ($guruList as $guru)
                                        <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                                    @endforeach
                                </select>
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
        @foreach ($kelass as $kelas)
            <div class="modal fade" id="modalEditKelas{{ $kelas->id_kelas }}" tabindex="-1"
                aria-labelledby="modalEditKelasLabel{{ $kelas->id_kelas }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('kelas.update', $kelas->id_kelas) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditKelasLabel{{ $kelas->id_kelas }}">Edit Kelas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kelas</label>
                                    <input type="text" name="nama_kelas" class="form-control"
                                        value="{{ $kelas->nama_kelas }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tingkat</label>
                                    <input type="text" name="tingkat" class="form-control"
                                        value="{{ $kelas->tingkat }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="wali_kelas_id" class="form-label">Wali Kelas (Guru)</label>
                                    <select name="wali_kelas_id" class="form-select">
                                        <option value="">-- Pilih Wali Kelas --</option>
                                        @foreach ($guruList as $guru)
                                            <option value="{{ $guru->id_guru }}"
                                                {{ $kelas->wali_kelas_id == $guru->id_guru ? 'selected' : '' }}>
                                                {{ $guru->nama_guru }}
                                            </option>
                                        @endforeach
                                    </select>
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
            const tableBody = document.getElementById('kelas-table-body');
            const paginationLinks = document.getElementById('pagination-links');
            let debounceTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    fetchData(
                        `${searchForm.action}?search=${encodeURIComponent(searchInput.value)}`);
                }, 300);
            });

            paginationLinks.addEventListener('click', function(event) {
                if (event.target.tagName === 'A') {
                    event.preventDefault();
                    fetchData(event.target.href);
                }
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
        });
    </script>
@endsection
