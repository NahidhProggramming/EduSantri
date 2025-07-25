@extends('layouts.app')
@section('title', 'Dashboard Guru')

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4">Selamat Datang, {{ $guru->nama_guru }}</h4>

        @php
            $cardsGuru = [
                ['Total Jadwal Mengajar', $totalJadwalGuru, 'calendar-event', 'primary', 5],
                ['Total Kelas Diampu', $totalKelasGuru, 'books', 'success', 4],
                ['Total Siswa Binaan', $totalSiswaGuru, 'user-plus', 'info', 6],
                // tambahkan kartu ke‑4 di sini bila ingin 4‑kolom/row
            ];
        @endphp

        <div class="row gy-3 mb-4">
            @foreach ($cardsGuru as [$label, $angka, $icon, $color, $pct])
                <div class="col-12 col-md-6 col-lg-{{ count($cardsGuru) == 4 ? 3 : 4 }}">
                    <div class="card shadow-sm border-0 overflow-hidden position-relative h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $color }} bg-opacity-10 rounded p-3 me-3">
                                    <i class="ti ti-{{ $icon }} fs-5 text-{{ $color }}"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">{{ $label }}</h6>
                                    <h3 class="fw-semibold mb-0">{{ $angka }}</h3>
                                </div>
                            </div>

                          
                        </div>

                        <i
                            class="ti ti-arrow-up-right-circle fs-6 position-absolute top-0 end-0 mt-3 me-3 text-{{ $color }}"></i>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- =================  JADWAL MENGAJAR (card)  ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">
                    <i class="ti ti-calendar-time me-2"></i> Jadwal Mengajar
                </h5>

                @if ($jadwals->isEmpty())
                    <div class="alert alert-warning text-center py-3">
                        <i class="ti ti-alert-circle me-2"></i> Belum ada jadwal mengajar tersedia.
                    </div>
                @else
                    {{-- Grid 100% lebar ⇒ col‑12 di mobile,  2 kolom di md, 3 kolom di lg --}}
                    <div class="row g-3">
                        @foreach ($jadwals as $jadwal)
                            @php
                                // warna gelombang biar variatif ‑ berdasarkan hari
                                $warna =
                                    [
                                        'senin' => 'primary',
                                        'selasa' => 'success',
                                        'rabu' => 'info',
                                        'kamis' => 'warning',
                                        'jumat' => 'danger',
                                        'sabtu' => 'secondary',
                                        'minggu' => 'dark',
                                    ][strtolower($jadwal->hari)] ?? 'primary';
                            @endphp

                            <div class="col-12 col-md-6 col-lg-4">
                                <div
                                    class="border rounded h-100 p-3 position-relative bg-{{ $warna }} bg-opacity-10">

                                    {{-- Badge hari --}}
                                    <span
                                        class="badge bg-{{ $warna }} position-absolute top-0 start-50 translate-middle-x">
                                        {{ ucfirst($jadwal->hari) }}
                                    </span>

                                    {{-- Mata pelajaran --}}
                                    <h6 class="fw-semibold mt-4 mb-1 text-{{ $warna }}">
                                        {{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}
                                    </h6>

                                    {{-- Kelas --}}
                                    <p class="mb-2 small text-muted">
                                        <i class="ti ti-users me-1"></i>
                                        {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                    </p>

                                    {{-- Jam --}}
                                    <div class="d-flex align-items-center gap-2 small">
                                        <i class="ti ti-clock text-{{ $warna }}"></i>
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai)->format('H:i') }}
                                        &nbsp;—&nbsp;
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_selesai)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
