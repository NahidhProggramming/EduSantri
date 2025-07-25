@extends('layouts.app')
@section('title', 'Monitoring Pelanggaran')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4"><i class="ti ti-activity me-2"></i>Monitoring Pelanggaran {{ $tahun }}</h4>

        {{-- ===== KARTU STATISTIK ===== --}}
        <div class="row gy-3 mb-4">
            @foreach ([['Semua', $jumlah, 'flag', 'primary'], ['Ringan', $ringan, 'slash-circle', 'success'], ['Sedang', $sedang, 'exclamation-triangle', 'warning'], ['Berat', $berat, 'exclamation-octagon', 'danger']] as [$lbl, $val, $ic, $clr])
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <div class="p-3 rounded bg-{{ $clr }} bg-opacity-10 me-3">
                                <i class="bi bi-{{ $ic }} text-{{ $clr }}"></i>
                            </div>
                            <div>
                                <span class="text-muted small d-block">{{ $lbl }}</span>
                                <h5 class="mb-0 fw-semibold">{{ $val }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== GRAFIK PELANGGARAN / BULAN ===== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-bar-chart-line-fill me-2 text-danger"></i>Pelanggaran per Bulan
                </h6>
                <canvas id="chartBulan" height="260"></canvas>
            </div>
        </div>

        {{-- ===== TOP 10 SANTRI ===== --}}
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-list-ol me-2 text-primary"></i>Top 10 Santri Terbanyak</h6>
                @forelse($topSantri as $row)
                    <div
                        class="d-flex justify-content-between border-start border-4 mb-2 ps-3
            border-{{ $row->jml >= 5 ? 'danger' : ($row->jml >= 3 ? 'warning' : 'success') }}">
                        <div>{{ $row->santri->nama_santri }} <small class="text-muted">({{ $row->santri_nis }})</small>
                        </div>
                        <span class="fw-bold">{{ $row->jml }}</span>
                    </div>
                @empty
                    <p class="text-muted">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chartBulan'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah',
                    data: @json($dataBulan),
                    backgroundColor: 'rgba(220,53,69,.55)',
                    borderColor: '#dc3545',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
