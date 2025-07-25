@extends('layouts.app')
@section('title', 'Monitoring Akademik')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4">
            <i class="ti ti-school me-2"></i>Monitoring Nilai Akademik
        </h4>

        {{-- ============ FILTER NILAI AKADEMIK ============ --}}
        <form id="filterAkademik" method="GET" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row gy-2 gx-3 align-items-end">

                    {{-- Tahun Akademik --}}
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1">Tahun Akademik</label>
                        <select name="tahun" class="form-select shadow-none" id="tahunSelect">
                            <option value="">– Pilih Tahun –</option>
                            @foreach ($daftarTahun as $t)
                                <option value="{{ $t->id_tahun_akademik }}"
                                    @selected($t->id_tahun_akademik == $tahunId)>
                                    {{ $t->tahun_akademik }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sekolah --}}
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1">Sekolah</label>
                        <select name="sekolah" class="form-select shadow-none" id="sekolahSelect">
                            <option value="">– Semua –</option>
                            @foreach ($daftarSekolah as $s)
                                <option value="{{ $s->id_sekolah }}"
                                    @selected($s->id_sekolah == $sekolahId)>
                                    {{ $s->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kelas --}}
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1">Kelas</label>
                        <select name="kelas" class="form-select shadow-none" id="kelasSelect">
                            <option value="">– Semua –</option>
                            @foreach ($daftarKelas as $k)
                                <option value="{{ $k->id_kelas }}"
                                    data-sekolah="{{ $k->sekolah_id }}"
                                    @selected($k->id_kelas == $kelasId)>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 d-grid">
                        <button class="btn btn-primary" title="Terapkan Filter" id="submitFilter">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('monitoring.akademik') }}"
                                class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </a>

                            <a href="{{ route('monitoring.akademik.export', request()->all()) }}"
                                class="btn btn-success btn-sm flex-fill flex-md-grow-0 {{ !$kelasId ? 'disabled' : '' }}"
                                {{ !$kelasId ? 'aria-disabled="true"' : '' }}>
                                <i class="bi bi-download"></i> Download Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if(!$tahunId)
            {{-- Tampilan awal sebelum filter dipilih --}}
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-graph-up-arrow fs-1 text-muted mb-3"></i>
                    <h5 class="fw-semibold">Silakan Pilih Tahun Akademik</h5>
                    <p class="text-muted">Pilih tahun akademik dan filter lainnya untuk melihat data monitoring</p>
                </div>
            </div>
        @else
            {{-- ===== KARTU RINGKAS ===== --}}
            <div class="row gy-3 mb-4">
                @php
                    $cards = [
                        ['Total Mapel', $totalMapel, 'book', 'primary'],
                        ['Rata‑Rata Semua', number_format($rataKeseluruhan, 2), 'graph-up', 'success'],
                    ];
                @endphp
                @foreach ($cards as [$lbl, $val, $ic, $clr])
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body d-flex align-items-center">
                                <div class="p-3 bg-{{ $clr }} bg-opacity-10 rounded me-3">
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

            {{-- ===== CHART NILAI PER MAPEL ===== --}}
            {{-- Tampilkan hanya jika kelas dipilih --}}
            @if($kelasId)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-bar-chart-fill me-2 text-info"></i>
                            Rata‑Rata Nilai per Mapel
                        </h6>

                        @if($rataMapel->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-circle fs-1 text-warning"></i>
                                <p class="fw-medium mt-3">Tidak ada data nilai untuk filter yang dipilih</p>
                            </div>
                        @else
                            <div style="position: relative; height:260px;">
                                <canvas id="chartMapel"></canvas>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-bar-chart fs-1 text-muted mb-3"></i>
                        <h5 class="fw-semibold">Silakan Pilih Kelas</h5>
                        <p class="text-muted">Pilih kelas untuk melihat grafik nilai per mata pelajaran</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ============ AUTO FILTER KELAS BERDASARKAN SEKOLAH ============
            const sekolahSelect = document.getElementById('sekolahSelect');
            const kelasSelect = document.getElementById('kelasSelect');

            const filterKelas = () => {
                const sekolahId = sekolahSelect.value;
                const options = kelasSelect.querySelectorAll('option');

                // Sembunyikan semua opsi kecuali default
                options.forEach(option => {
                    if (option.value === '') return;
                    option.style.display = 'none';
                    option.disabled = true;
                });

                // Tampilkan opsi yang sesuai
                options.forEach(option => {
                    if (option.value === '') return;

                    const sekolahKelas = option.getAttribute('data-sekolah');
                    if (sekolahId === '' || sekolahKelas === sekolahId) {
                        option.style.display = 'block';
                        option.disabled = false;
                    }
                });

                // Reset pilihan jika kelas tidak tersedia
                if (kelasSelect.value && kelasSelect.options[kelasSelect.selectedIndex].disabled) {
                    kelasSelect.value = '';
                }
            };

            // Inisialisasi pertama kali
            filterKelas();
            sekolahSelect.addEventListener('change', filterKelas);

            // ============ CHART NILAI PER MAPEL ============
            @if($tahunId && $kelasId && !$rataMapel->isEmpty())
                const ctx = document.getElementById('chartMapel');
                const srcData = @json($rataMapel->pluck('rata'));
                const srcLabel = @json($rataMapel->pluck('nama_mapel'));

                let chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: srcLabel,
                        datasets: [{
                            label: 'Rata-Rata Nilai',
                            data: srcData,
                            backgroundColor: 'rgba(13,110,253,.5)',
                            borderColor: '#0d6efd',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Nilai: ${context.parsed.y}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Nilai Rata-Rata'
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                title: {
                                    display: true,
                                    text: 'Mata Pelajaran'
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            @endif

            // ============ TANDA FILTER AKTIF ============
            const tahunSelect = document.getElementById('tahunSelect');
            const submitBtn = document.getElementById('submitFilter');

            // Beri warna border merah jika tahun belum dipilih
            if (tahunSelect.value === '') {
                tahunSelect.classList.add('border', 'border-danger');
            }

            tahunSelect.addEventListener('change', function() {
                if (this.value === '') {
                    this.classList.add('border', 'border-danger');
                } else {
                    this.classList.remove('border', 'border-danger');
                }
            });

            // Validasi sebelum submit
            document.getElementById('filterAkademik').addEventListener('submit', function(e) {
                if (tahunSelect.value === '') {
                    e.preventDefault();
                    tahunSelect.classList.add('border', 'border-danger');
                    tahunSelect.focus();

                    // Animasi getar
                    tahunSelect.animate([
                        { transform: 'translateX(0)' },
                        { transform: 'translateX(-5px)' },
                        { transform: 'translateX(5px)' },
                        { transform: 'translateX(0)' }
                    ], {
                        duration: 300,
                        iterations: 2
                    });
                }
            });
        });
    </script>
@endpush
