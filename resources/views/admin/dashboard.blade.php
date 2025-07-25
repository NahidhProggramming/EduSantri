@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4">Selamat Datang, {{ auth()->user()->name }}</h4>

        <!-- Statistik Ringkas -->
        <div class="row mb-4 gy-3">
            <!-- Total Pengguna -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                <i class="ti ti-users fs-5 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Pengguna</h6>
                                <h3 class="mb-0">{{ $jumlahSantri + $jumlahGuru }}</h3>
                            </div>
                        </div>

                    </div>
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        <i class="ti ti-arrow-up-right-circle fs-6 text-primary"></i>
                    </div>
                </div>
            </div>

            <!-- Total Guru -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                <i class="ti ti-user-circle fs-5 text-success"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Guru</h6>
                                <h3 class="mb-0">{{ $jumlahGuru }}</h3>
                            </div>
                        </div>

                    </div>
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        <i class="ti ti-arrow-up-right-circle fs-6 text-success"></i>
                    </div>
                </div>
            </div>

            <!-- Total Santri -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                                <i class="ti ti-user-plus fs-5 text-info"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Santri</h6>
                                <h3 class="mb-0">{{ $jumlahSantri }}</h3>
                            </div>
                        </div>

                    </div>
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        <i class="ti ti-arrow-up-right-circle fs-6 text-info"></i>
                    </div>
                </div>
            </div>

            <!-- Total Jadwal -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                                <i class="ti ti-calendar-event fs-5 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Jadwal</h6>
                                <h3 class="mb-0">{{ $jumlahJadwal }}</h3>
                            </div>
                        </div>

                    </div>
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        <i class="ti ti-arrow-up-right-circle fs-6 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>


        {{-- Grafik --}}
        <div class="row mb-4">
            {{-- Grafik Perkembangan Akademik --}}
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-semibold m-0">
                                <i class="ti ti-trending-up me-2 text-success"></i> Perkembangan Akademik
                            </h5>
                            <button id="resetFilterAkademik" class="btn btn-sm btn-outline-secondary">Reset</button>
                        </div>

                        {{-- Filter --}}
                        <div class="row g-2 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small">Tahun Akademik</label>
                                <select id="filterAkademik" class="form-select form-select-sm">
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($tahunAkademikList as $tahun)
                                        <option value="{{ $tahun->id_tahun_akademik }}"
                                            {{ $tahun->semester_aktif == 'aktif' ? 'selected' : '' }}>
                                            {{ $tahun->tahun_akademik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Sekolah</label>
                                <select id="filterSekolah" class="form-select form-select-sm">
                                    <option value="">-- Semua Sekolah --</option>
                                    @foreach ($sekolahList as $sekolah)
                                        <option value="{{ $sekolah->id_sekolah }}">{{ $sekolah->nama_sekolah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small">Mata Pelajaran</label>
                                <select id="filterMapel" class="form-select form-select-sm" disabled>
                                    <option value="">-- Semua Mapel --</option>
                                </select>
                            </div>
                        </div>

                        <canvas id="akademikChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik Pelanggaran Santri --}}
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-semibold m-0">
                                <i class="ti ti-alert-triangle me-2 text-danger"></i> Pelanggaran Santri
                            </h5>
                            @php
                                $currentYear = date('Y');
                                $years = range($currentYear, $currentYear - 5);
                            @endphp
                            <select id="filterPelanggaran" class="form-select form-select-sm w-auto">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <canvas id="pelanggaranChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            {{-- Daftar Guru Terbaru --}}
            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4 d-flex align-items-center">
                            <i class="ti ti-user-circle me-2 text-primary"></i> Guru Terbaru
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Guru</th>
                                        <th>Tanggal Bergabung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guruBaru as $index => $guru)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="mb-0">{{ $guru->nama_guru }}</h6>
                                                        <small class="text-muted">{{ $guru->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($guru->created_at)->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aktivitas Admin --}}
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4 d-flex align-items-center">
                            <i class="ti ti-activity me-2 text-success"></i> Aktivitas Terbaru
                        </h5>
                        <div class="timeline">
                            @foreach ($aktivitas as $aktif)
                                <div class="timeline-item">
                                    <div class="timeline-time">
                                        {{ $aktif->created_at->format('H:i') }}
                                    </div>
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">{{ $aktif->guru->nama_guru }}</h6>
                                        <p class="mb-0 text-muted">{{ $aktif->deskripsi }}</p>
                                        <small class="text-muted">{{ $aktif->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-5 small text-muted">
            &copy; {{ date('Y') }} Edu Santri | Dashboard Admin
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-sm {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 24px;
        }

        .timeline-dot {
            position: absolute;
            left: -20px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #5d87ff;
            border: 2px solid #fff;
            z-index: 2;
        }

        .timeline-time {
            position: absolute;
            left: -100px;
            top: 0;
            width: 80px;
            text-align: right;
            font-size: 0.8rem;
            color: #6c757d;
        }

        .timeline-content {
            padding: 12px 16px;
            background-color: #f8f9fa;
            border-radius: 8px;
            position: relative;
        }

        .timeline-content::before {
            content: "";
            position: absolute;
            left: -8px;
            top: 12px;
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-right: 8px solid #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi chart
            const akademikCtx = document.getElementById('akademikChart').getContext('2d');
            const pelanggaranCtx = document.getElementById('pelanggaranChart').getContext('2d');

            let akademikChart = null;
            let pelanggaranChart = null;

            // Fungsi untuk memuat data grafik akademik dengan filter
            function loadAkademikChart() {
                const tahun = document.getElementById('filterAkademik').value;
                const sekolah = document.getElementById('filterSekolah').value;
                const mapel = document.getElementById('filterMapel').value;

                if (!tahun && !sekolah && !mapel) {
                    if (akademikChart) {
                        akademikChart.destroy();
                        akademikChart = null;
                    }
                    return; // Jangan lanjut fetch data
                }

                const url = new URL('/admin/filter-data', window.location.origin);
                if (tahun) url.searchParams.append('tahun', tahun);
                if (sekolah) url.searchParams.append('sekolah', sekolah);
                if (mapel) url.searchParams.append('mapel', mapel);

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        initAkademikChart(data);
                    });
            }


            // Fungsi untuk memuat data mata pelajaran
            function loadMapelData() {
                const tahun = document.getElementById('filterAkademik').value;
                const sekolah = document.getElementById('filterSekolah').value;

                if (!tahun) return;

                // Buat URL dengan parameter
                const url = new URL('/admin/mapel-data', window.location.origin);
                url.searchParams.append('tahun', tahun);
                if (sekolah) url.searchParams.append('sekolah', sekolah);

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        // Reset dropdown
                        const dropdown = document.getElementById('filterMapel');
                        dropdown.innerHTML = '<option value="">-- Semua Mapel --</option>';

                        // Isi dengan data baru
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.nama;
                            dropdown.appendChild(option);
                        });

                        // Aktifkan dropdown
                        dropdown.disabled = false;
                    });
            }

            // Fungsi inisialisasi grafik akademik
            function initAkademikChart(data) {
                if (akademikChart) akademikChart.destroy();

                akademikChart = new Chart(akademikCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Kelas 7', 'Kelas 8', 'Kelas 9'],
                        datasets: [{
                            label: 'Rata-rata Nilai PAS',
                            data: data,
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(153, 102, 255, 0.5)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Nilai'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tingkat Kelas'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Rata-rata Nilai Akhir Per Tingkat'
                            }
                        }
                    }
                });
            }

            // Fungsi inisialisasi grafik pelanggaran
            function initPelanggaranChart(data) {
                if (pelanggaranChart) pelanggaranChart.destroy();

                pelanggaranChart = new Chart(pelanggaranCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt',
                            'Nov', 'Des'
                        ],
                        datasets: [{
                            label: 'Jumlah Pelanggaran',
                            data: data,
                            fill: false,
                            borderColor: 'rgb(255, 99, 132)',
                            tension: 0.1,
                            pointBackgroundColor: 'rgb(255, 99, 132)',
                            pointRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Pelanggaran'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Bulan'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Pelanggaran Santri Tahun ' + document.getElementById(
                                    'filterPelanggaran').value
                            }
                        }
                    }
                });
            }

            // Event listener untuk filter tahun akademik
            document.getElementById('filterAkademik').addEventListener('change', function() {
                if (this.value) {
                    loadMapelData();
                } else {
                    // Reset dropdown mapel
                    const mapelDropdown = document.getElementById('filterMapel');
                    mapelDropdown.innerHTML = '<option value="">-- Semua Mapel --</option>';
                    mapelDropdown.disabled = true;
                }
                loadAkademikChart();
            });

            // Event listener untuk filter sekolah
            document.getElementById('filterSekolah').addEventListener('change', function() {
                if (document.getElementById('filterAkademik').value) {
                    loadMapelData();
                }
                loadAkademikChart();
            });

            // Event listener untuk filter mapel
            document.getElementById('filterMapel').addEventListener('change', loadAkademikChart);

            // Event listener untuk filter pelanggaran
            document.getElementById('filterPelanggaran').addEventListener('change', function() {
                fetch(`/admin/data-pelanggaran/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        initPelanggaranChart(data);
                        // Update title
                        pelanggaranChart.options.plugins.title.text =
                            'Pelanggaran Santri Tahun ' + this.value;
                        pelanggaranChart.update();
                    });
            });

            document.getElementById('resetFilterAkademik').addEventListener('click', function() {
                document.getElementById('filterAkademik').value = '';
                document.getElementById('filterSekolah').value = '';

                const mapelDropdown = document.getElementById('filterMapel');
                mapelDropdown.innerHTML = '<option value="">-- Semua Mapel --</option>';
                mapelDropdown.disabled = true;

                // Ini akan trigger destroy chart jika semua filter kosong
                loadAkademikChart();
            });


            // Load awal
            // Untuk akademik, kita akan load dengan tahun aktif pertama
            const tahunAktif = document.getElementById('filterAkademik').value;
            if (tahunAktif) {
                loadMapelData();
                loadAkademikChart();
            }

            // Untuk pelanggaran, load dengan tahun sekarang
            const tahunSekarang = document.getElementById('filterPelanggaran').value;
            fetch(`/admin/data-pelanggaran/${tahunSekarang}`)
                .then(res => res.json())
                .then(data => initPelanggaranChart(data));
        });
    </script>
@endpush
