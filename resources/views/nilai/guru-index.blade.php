@extends('layouts.app')
@section('title', 'Dashboard Guru')

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4">Selamat Datang, {{ $guru->nama_guru }}</h4>

        {{-- Statistik Ringkas Modern --}}
        <div class="row mb-4">
            {{-- Tahun Akademik Aktif --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-light-primary p-3 rounded-circle me-3">
                            <i class="ti ti-calendar-event text-primary fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Tahun Akademik Aktif</p>
                            <h5 class="fw-semibold mb-0">{{ $tahunAktif->tahun_akademik ?? 'Belum Ada' }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Jadwal Mengajar --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-light-info p-3 rounded-circle me-3">
                            <i class="ti ti-notebook text-info fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Total Jadwal Mengajar</p>
                            <h5 class="fw-semibold mb-0">{{ $jadwals->count() }} Jadwal</h5>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Jumlah Santri --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-light-success p-3 rounded-circle me-3">
                            <i class="ti ti-users text-success fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Jumlah Santri</p>
                            <h5 class="fw-semibold mb-0" id="jumlahSantri">0 Santri</h5>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">
                    <i class="ti ti-calendar-time me-2"></i> Jadwal Mengajar
                </h5>

                @if ($jadwals->isEmpty())
                    <div class="alert alert-warning text-center py-3">
                        <i class="ti ti-alert-circle me-2"></i> Belum ada jadwal mengajar yang tersedia.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Hari</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($jadwals as $i => $jadwal)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</td>
                                        <td>{{ $jadwal->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ ucfirst($jadwal->hari) }}</td>
                                        <td>{{ $jadwal->jam_mulai }}</td>
                                        <td>{{ $jadwal->jam_selesai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>



        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">
                        <i class="ti ti-history me-2 text-primary"></i> Aktivitas Terbaru
                    </h5>

                    @forelse ($aktivitas as $item)
                        <div class="d-flex mb-4 align-items-start">
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary-subtle text-primary p-3 rounded-circle">
                                    <i class="ti ti-check fs-5"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-semibold mb-1">{{ $item->deskripsi }}</h6>
                                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                <hr class="my-2">
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            Belum ada aktivitas terbaru.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-5 small text-muted">
            &copy; {{ date('Y') }} Edu Santri | Dashboard Guru
        </div>
    </div>

    {{-- JS Dummy --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("jumlahSantri").innerText = "{{ $jumlahSantri ?? '0' }}" + " Santri";
        });
    </script>
@endsection
@push('styles')
    <style>
        .timeline {
            list-style: none;
            padding-left: 0;
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 4px;
            top: 6px;
            width: 12px;
            height: 12px;
            background: #0d6efd;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }
    </style>
@endpush
