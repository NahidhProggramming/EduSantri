@extends('layouts.app')
@section('title', 'Input Nilai Santri')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">
                    Input Nilai - {{ $jadwal->mataPelajaran->nama_mapel ?? '-' }} - {{ $jadwal->kelas->nama_kelas ?? '-' }}
                </h5>
                <p class="mb-4 text-muted">
                    <strong>Guru:</strong> {{ $jadwal->guru->nama_guru ?? '-' }} |
                    <strong>Tahun Akademik:</strong> {{ $jadwal->tahunAkademik->tahun_akademik ?? '-' }}
                </p>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($santriDetails->isEmpty())
                    <div class="alert alert-warning">Belum ada santri di kelas ini.</div>
                @else
                    <form action="{{ route('nilai.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id_jadwal }}">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Santri</th>
                                        <th>Sumatif</th>
                                        <th>PAS</th>
                                        <th>PAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($santriDetails as $index => $santri)
                                        @php
                                            $existingNilai = $santri->nilai
                                                ->where('jadwal_id', $jadwal->id_jadwal)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $santri->santri->nama_santri ?? '-' }}</td>
                                            <td class="text-center" style="width: 120px;">
                                                <input type="hidden" name="nilai[{{ $index }}][detail_id]"
                                                    value="{{ $santri->id_detail }}">
                                                <input type="number" name="nilai[{{ $index }}][nilai_sumatif]"
                                                    class="form-control form-control-sm text-center"
                                                    value="{{ old("nilai.$index.nilai_sumatif", $existingNilai->nilai_sumatif ?? '') }}"
                                                    min="0" max="100" placeholder="0-100">
                                            </td>
                                            <td class="text-center" style="width: 120px;">
                                                <input type="number" name="nilai[{{ $index }}][nilai_pas]"
                                                    class="form-control form-control-sm text-center"
                                                    value="{{ old("nilai.$index.nilai_pas", $existingNilai->nilai_pas ?? '') }}"
                                                    min="0" max="100" placeholder="0-100">
                                            </td>
                                            <td class="text-center" style="width: 120px;">
                                                <input type="number" name="nilai[{{ $index }}][nilai_pat]"
                                                    class="form-control form-control-sm text-center"
                                                    value="{{ old("nilai.$index.nilai_pat", $existingNilai->nilai_pat ?? '') }}"
                                                    min="0" max="100" placeholder="0-100">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                Simpan Nilai
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
