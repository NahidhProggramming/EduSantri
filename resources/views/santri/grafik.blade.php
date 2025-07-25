@extends('layouts.home')
@section('title', 'Dashboard Santri')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-5 pt-4 mt-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle"></i> Beranda
            </a>
            <h4 class="fw-bold mb-0 ms-auto">Halo, {{ auth()->user()->name }}</h4>
        </div>

        {{-- Statistik ringkas --}}
        <div class="row gy-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm border rounded-3">
                    <div class="card-body text-center">
                        <i class="bi bi-bookmark-check fs-3 text-success"></i>
                        <h5 class="mb-0">
                            {{ $nilaiMapel->count() ? $nilaiMapel->avg('rata') : '-' }}
                        </h5>
                        <small class="text-muted">Rata‑rata Nilai</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm border rounded-3">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>
                        <h5 class="mb-0">{{ array_sum($pelChart) }}</h5>
                        <small class="text-muted">Total Pelanggaran {{ $tahunChart }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-4">

            {{-- Grafik Nilai per Mapel --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header text-white fw-semibold" style="background:#13DEB9">
                        <i class="bi bi-bar-chart-line me-2"></i> Nilai per Mata Pelajaran
                    </div>
                    <div class="card-body">
                        @if ($nilaiMapel->isEmpty())
                            <div class="alert alert-info mb-0">Belum ada data nilai.</div>
                        @else
                            <canvas id="nilaiMapelChart" style="height:300px;"></canvas>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Grafik Pelanggaran --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header text-white fw-semibold" style="background:#13DEB9">
                        <i class="bi bi-graph-up-arrow me-2"></i> Pelanggaran {{ $tahunChart }}
                    </div>
                    <div class="card-body">
                        @if (!collect($pelChart)->sum())
                            <div class="alert alert-info mb-0">Belum ada data pelanggaran.</div>
                        @else
                            <canvas id="pelChart" style="height:300px;"></canvas>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ---------- Grafik Nilai per Mapel ---------- */
            @if ($nilaiMapel->isNotEmpty())
                const mapelLabels = @json($nilaiMapel->pluck('nama_mapel'));
                const mapelData = @json($nilaiMapel->pluck('rata'));
                new Chart(document.getElementById('nilaiMapelChart'), {
                    type: 'bar',
                    data: {
                        labels: mapelLabels,
                        datasets: [{
                            label: 'Rata‑rata',
                            data: mapelData,
                            backgroundColor: 'rgba(19,222,185,0.65)',
                            borderColor: 'rgba(19,222,185,1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            @endif

            /* ---------- Grafik Pelanggaran ---------- */
            @if (collect($pelChart)->sum())
                const pelLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                    'Des'
                ];
                const pelData = @json($pelChart);
                new Chart(document.getElementById('pelChart'), {
                    type: 'line',
                    data: {
                        labels: pelLabels,
                        datasets: [{
                            label: 'Pelanggaran',
                            data: pelData,
                            borderColor: 'rgb(255,99,132)',
                            backgroundColor: 'rgba(255,99,132,0.2)',
                            tension: 0.25,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }
                    }
                });
            @endif
        });
    </script>
@endpush
