@extends('layouts.home')
@section('title', 'Pelanggaran Santri')

@section('content')
    <div class="container-fluid px-3 px-md-5 pt-4 mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Riwayat Pelanggaran</h5>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Tingkat</th>
                                <th>Deskripsi</th>
                                <th>Surat</th>
                                <th>Status Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggarans as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $p->jenisPelanggaran->nama_jenis }}</td>
                                    <td>{{ $p->jenisPelanggaran->tingkat }}</td>
                                    <td class="text-center">{{ $p->deskripsi ?? '-' }}</td>
                                    <td>
                                        @if ($p->file_surat && $p->verifikasi_surat === 'Terverifikasi')
                                            <a href="{{ asset('surat_pelanggaran/' . $p->file_surat) }}"
                                                class="btn btn-sm btn-secondary" target="_blank">
                                                <i class="bi bi-file-earmark-arrow-down"></i> Lihat
                                            </a>
                                        @elseif($p->file_surat)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock-history"></i> Menunggu Verifikasi
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($p->verifikasi_surat)
                                            <span
                                                class="badge bg-{{ $p->verifikasi_surat === 'Terverifikasi' ? 'success' : 'danger' }}">
                                                {{ $p->verifikasi_surat }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">Tidak ada pelanggaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
