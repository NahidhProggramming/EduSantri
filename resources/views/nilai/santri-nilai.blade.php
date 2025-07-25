    @extends('layouts.home')
    @section('title', 'Nilai Santri')

    @section('content')
        <div class="container-fluid px-3 px-md-5 pt-4 mt-4">
            <h5 class="fw-bold mb-4">Nilai Santri</h5>

            @php
                /* hitung rata‑rata + predikat */
                $predikat = fn($s, $p, $t) => is_null($s) || is_null($p) || is_null($t)
                    ? ['-', '-']
                    : (function ($r) {
                        return match (true) {
                            $r >= 90 => [$r, 'A'],
                            $r >= 80 => [$r, 'B'],
                            $r >= 70 => [$r, 'C'],
                            $r >= 60 => [$r, 'D'],
                            default => [$r, 'E'],
                        };
                    })(round(($s + $p + $t) / 3, 2));

                /* badge warna berdasarkan huruf */
                $badgeColor = fn($h) => match ($h) {
                    'A' => 'success',
                    'B' => 'primary',
                    'C' => 'info',
                    'D' => 'warning',
                    'E' => 'danger',
                    default => 'secondary',
                };
            @endphp
            <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left-circle"></i> Kembali ke Beranda
            </a>

            @forelse($santriList as $s)
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header text-white d-flex justify-content-between align-items-start flex-column flex-md-row"
                        style="background:#13DEB9;">
                        <div>
                            <div class="fw-semibold">
                                <i class="bi bi-person-badge"></i> {{ $s->nama_santri }} (NIS: {{ $s->nis }})
                            </div>
                            <small>
                                Kelas: {{ $s->kelasAktif?->nama_kelas ?? '-' }} |
                                Sekolah:
                                {{ $s->kelasAktif?->sekolah?->nama_sekolah ?? ($s->sekolahAktif?->nama_sekolah ?? '-') }}
                            </small>
                        </div>
                        <button class="btn btn-sm btn-light mt-2 mt-md-0" data-bs-toggle="collapse"
                            data-bs-target="#nilai{{ $s->nis }}">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>


                    <div id="nilai{{ $s->nis }}" class="collapse show">
                        @php
                            $kelompok = $s->nilai->groupBy(
                                fn($n) => $n->tahunAkademik->semester . '|' . $n->tahunAkademik->tahun_akademik,
                            );
                        @endphp

                        @foreach ($kelompok as $key => $list)
                            @php
                                [$sem, $th] = explode('|', $key);
                                $detailId = $list->first()->detail_id ?? null;
                            @endphp

                            {{-- bar semester + tombol download --}}
                            <div class="d-flex justify-content-between align-items-center px-3 py-2"
                                style="background:#F2FAF8;border-top:1px solid #dee2e6">
                                <div>
                                    <span class="fw-semibold"><i class="bi bi-calendar-week"></i> Semester
                                        {{ $sem }}</span>
                                    <span class="text-muted ms-2">Tahun {{ $th }}</span>
                                </div>
                                <a href="{{ route('rapor.cetak', $detailId) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill">
                                    <i class="bi bi-download"></i> Download Rapor
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle mb-0">
                                    <thead style="background:#13DEB9;color:#fff;">
                                        <tr>
                                            <th>Mata Pelajaran</th>
                                            <th class="text-center">Sumatif</th>
                                            <th class="text-center">PAS</th>
                                            <th class="text-center">PAT</th>
                                            <th class="text-center">Rata‑rata</th>
                                            <th class="text-center">Predikat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                            $cnt = 0;
                                        @endphp

                                        @foreach ($list as $n)
                                            @php
                                                // Hitung rata-rata dan huruf
                                                $nilaiValid = collect([
                                                    $n->nilai_sumatif,
                                                    $n->nilai_pas,
                                                    $n->nilai_pat,
                                                ])->filter(fn($v) => !is_null($v));

                                                if ($nilaiValid->count() > 0) {
                                                    $avg = round($nilaiValid->avg(), 2);

                                                    $huruf = match (true) {
                                                        $avg >= 90 => 'A',
                                                        $avg >= 80 => 'B',
                                                        $avg >= 70 => 'C',
                                                        $avg >= 60 => 'D',
                                                        default => 'E',
                                                    };

                                                    $total += $avg;
                                                    $cnt++;
                                                } else {
                                                    $avg = '‑';
                                                    $huruf = '‑';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $n->jadwal?->mataPelajaran?->nama_mapel ?? '-' }}</td>
                                                <td class="text-center">{{ $n->nilai_sumatif ?? '-' }}</td>
                                                <td class="text-center">{{ $n->nilai_pas ?? '-' }}</td>
                                                <td class="text-center">{{ $n->nilai_pat ?? '-' }}</td>
                                                <td class="text-center">
                                                    @if ($avg !== '‑')
                                                        {{ $avg }}
                                                        <div class="progress mt-1" style="height:4px;">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width:{{ $avg }}%"></div>
                                                        </div>
                                                    @else
                                                        ‑
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($huruf !== '‑')
                                                        <span
                                                            class="badge bg-{{ $badgeColor($huruf) }}">{{ $huruf }}</span>
                                                    @else
                                                        ‑
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr class="table-light fw-semibold">
                                            <td colspan="4" class="text-end">Rata‑rata Kelas</td>
                                            <td class="text-center">{{ $cnt ? number_format($total / $cnt, 2) : '‑' }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">Tidak ada santri yang terkait akun Anda.</div>
            @endforelse
        </div>
    @endsection
