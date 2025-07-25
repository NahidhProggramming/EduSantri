@extends('layouts.app')
@section('title', 'Dashboard Kesiswaan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .timeline::before {
            content: "";
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6
        }

        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 20px
        }

        .timeline-item .dot {
            position: absolute;
            left: 6px;
            top: 2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dc3545
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4">Selamat Datang, {{ auth()->user()->name }}</h4>

        {{-- ===== KARTU STATISTIK ===== --}}
        @php
            // tanggal hari ini
            $tglUpdate = now()->translatedFormat('d M Y');
            // hitung % kenaikan contoh (silakan ganti logika sesuai kebutuhan)
            $kenaikan = fn($now) => '+' . round(($now / 100) * 5) . ' bulan ini';
        @endphp

        <div class="row mb-4 gy-3">
            @foreach ([['Total Santri', $jumlahSantri, 'users', 'success'], ['Total Pelanggaran', $jumlahPelanggaran, 'flag', 'danger'], ['Pelanggaran Berat', $jumlahPelanggaranBerat, 'alert-triangle', 'warning']] as [$label, $angka, $icon, $color])
                <div class="col-12 col-md-6 col-lg-4"><!-- ubah lg‑4→lg‑3 kalau mau 4 kartu/row -->
                    <div class="card shadow-sm border-0 overflow-hidden position-relative">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                {{-- ikon lingkaran --}}
                                <div class="bg-{{ $color }} bg-opacity-10 rounded p-3 me-3">
                                    <i class="ti ti-{{ $icon }} fs-5 text-{{ $color }}"></i>
                                </div>

                                <div>
                                    <h6 class="text-muted mb-1">{{ $label }}</h6>
                                    <h3 class="fw-semibold mb-0">{{ $angka }}</h3>
                                </div>
                            </div>

                          
                        </div>

                        {{-- panah dekoratif pojok kanan‑atas --}}
                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <i class="ti ti-arrow-up-right-circle fs-6 text-{{ $color }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>




        {{-- GRAFIK KIRI & KANAN --}}
        <div class="row g-4 mb-4">
            {{-- BAR – pelanggaran per‑bulan --}}
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-semibold mb-0">
                                <i class="bi bi-bar-chart-fill text-danger me-2"></i>Pelanggaran Per‑Bulan
                            </h6>
                            <select id="tahunSelect" class="form-select form-select-sm w-auto">
                                @foreach ($daftarTahun as $th)
                                    <option value="{{ $th }}" @selected($th == $tahun)>{{ $th }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <canvas id="chartBulan" height="240"></canvas>
                    </div>
                </div>
            </div>

            {{-- DOUGHNUT – distribusi tingkat --}}
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="fw-semibold mb-3 text-center">
                            <i class="bi bi-pie-chart-fill text-primary me-2"></i>Distribusi Tingkat Pelanggaran
                        </h6>
                        <canvas id="chartTingkat" height="260"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- TIMELINE TERBARU --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-clock-history me-2 text-danger"></i>Pelanggaran Terbaru</h6>
                @forelse($pelanggaranTerbaru as $p)
                    <div class="timeline position-relative">
                        <div class="timeline-item">
                            <span class="dot"></span>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</small>
                            <div class="fw-semibold">{{ $p->santri->nama_santri }}</div>
                            <div class="text-muted">{{ $p->jenisPelanggaran->nama_jenis }} –
                                {{ $p->jenisPelanggaran->tingkat }}</div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">Belum ada pelanggaran terbaru.</div>
                @endforelse
            </div>
        </div>

        <div class="text-center small text-muted mt-5">&copy; {{ date('Y') }} Edu Santri | Dashboard Kesiswaan</div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        /* ---------- SETUP ---------- */
        const colors = ['#20c997', '#ffca2c', '#dc3545']; // ringan, sedang, berat
        const ctxBln = document.getElementById('chartBulan');
        const ctxTkt = document.getElementById('chartTingkat');

        /* ---------- BAR CHART ---------- */
        let chartBln = new Chart(ctxBln, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
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

        /* ---------- DOUGHNUT CHART ---------- */
        const totalTingkat = @json($dataTingkat).reduce((a, b) => a + b, 0);

        let chartTkt = new Chart(ctxTkt, {
            type: 'doughnut',
            data: {
                labels: ['Ringan', 'Sedang', 'Berat'],
                datasets: [{
                    data: @json($dataTingkat),
                    backgroundColor: colors,
                    hoverOffset: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const val = ctx.parsed;
                                const pct = totalTingkat ? (val / totalTingkat * 100).toFixed(1) : 0;
                                return `${ctx.label}: ${val} (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            },
            plugins: [{
                /* tulis total di tengah */
                id: 'center-text',
                afterDraw(chart, args, opts) {
                    const {
                        ctx,
                        chartArea: {
                            width,
                            height
                        }
                    } = chart;
                    ctx.save();
                    ctx.font = 'bold 16px sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.textAlign = 'center';
                    ctx.fillText(totalTingkat + ' Pelanggaran', chart.getDatasetMeta(0).data[0].x, height /
                        2);
                }
            }]
        });

        /* ---------- GANTI TAHUN ---------- */
        document.getElementById('tahunSelect').addEventListener('change', e => {
            fetch(`{{ route('kesiswaan.chart', '') }}/${e.target.value}`)
                .then(r => r.json())
                .then(res => {
                    /* update bar */
                    chartBln.data.datasets[0].data = res.bulan;
                    chartBln.update();

                    /* update doughnut */
                    chartTkt.data.datasets[0].data = res.tingkat;
                    chartTkt.update();

                    /* ubah teks tengah */
                    const newTotal = res.tingkat.reduce((a, b) => a + b, 0);
                    totalTingkat = newTotal; // update closure var
                    chartTkt.draw();
                });
        });
    </script>
@endpush
