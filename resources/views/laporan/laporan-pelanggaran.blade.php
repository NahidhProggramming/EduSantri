@extends('layouts.app')
@section('title', 'Laporan Pelanggaran')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4"><i class="ti ti-file-report me-2"></i>Laporan Pelanggaran</h4>

        <form method="GET" class="card shadow-sm mb-4" id="filterForm">
            <div class="card-body row g-3 align-items-end">

                {{-- JENIS --}}
                <div class="col-md-4">
                    <label class="form-label small">Jenis Pelanggaran</label>
                    <select name="jenis" id="jenisSelect" class="form-select">
                        <option value="">– Semua Jenis –</option>
                        @foreach ($jenisList as $j)
                            <option value="{{ $j->id_jenis }}" data-tingkat="{{ $j->tingkat }}"
                                @selected($j->id_jenis == request('jenis'))>
                                {{ $j->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TINGKAT (readonly) --}}
                <div class="col-md-3">
                    <label class="form-label small">Tingkat</label>
                    <input type="text" id="tingkatReadonly" name="tingkat" class="form-control"
                        value="{{ request('tingkat') }}" placeholder="Otomatis" readonly>
                </div>

                {{-- TANGGAL DARI / SAMPAI --}}
                <div class="col-md-2">
                    <label class="form-label small">Dari</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Sampai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
                </div>

                {{-- AKSI --}}
                <div class="col-md-1 d-grid">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>

                {{-- BARIS TOMBOL RESET & DOWNLOAD --}}
                <div class="col-12 d-flex gap-2 mt-2">
                    <a href="{{ route('laporan.pelanggaran') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>

                    <a href="{{ route('laporan.pelanggaran.excel', request()->all()) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-download"></i> Download Excel
                    </a>
                </div>
            </div>
        </form>

        {{-- ====== KARTU REKAP CEPAT ====== --}}
        @php
            $totalData = $pelanggarans->total();
            $totalBerat = $pelanggarans->where('jenisPelanggaran.tingkat', 'Berat')->count();
            $totalSedang = $pelanggarans->where('jenisPelanggaran.tingkat', 'Sedang')->count();
            $totalRingan = $pelanggarans->where('jenisPelanggaran.tingkat', 'Ringan')->count();
        @endphp
        <div class="row gy-3 mb-4">
            @foreach ([['Semua', $totalData, 'flag', 'primary'], ['Ringan', $totalRingan, 'exclamation-circle', 'success'], ['Sedang', $totalSedang, 'exclamation-triangle', 'warning'], ['Berat', $totalBerat, 'alert-octagon', 'danger']] as [$lbl, $val, $ic, $clr])
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

        {{-- ====== LIST PELANGGARAN (CARD) ====== --}}
        @forelse($pelanggarans as $p)
            <div
                class="card shadow-sm mb-3 border-start border-4
             border-{{ strtolower($p->jenisPelanggaran->tingkat) == 'berat'
                 ? 'danger'
                 : (strtolower($p->jenisPelanggaran->tingkat) == 'sedang'
                     ? 'warning'
                     : 'success') }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $p->santri->nama_santri }}</h6>
                            <small class="text-muted">{{ $p->santri_nis }}</small>
                        </div>
                        <span
                            class="badge rounded-pill
                          bg-{{ strtolower($p->jenisPelanggaran->tingkat) == 'berat'
                              ? 'danger'
                              : (strtolower($p->jenisPelanggaran->tingkat) == 'sedang'
                                  ? 'warning'
                                  : 'success') }}">
                            {{ $p->jenisPelanggaran->tingkat }}
                        </span>
                    </div>
                    <p class="mt-2 mb-1">{{ $p->jenisPelanggaran->nama_jenis }}</p>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}
                        &middot; {{ $p->deskripsi ?: '‑' }}
                    </small>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">Data pelanggaran tidak ditemukan.</div>
        @endforelse

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $pelanggarans->links() }}
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const jenisSel = document.getElementById('jenisSelect');
            const tingkatInp = document.getElementById('tingkatReadonly');

            function setTingkat() {
                const opt = jenisSel.selectedOptions[0];
                tingkatInp.value = opt ? opt.dataset.tingkat || '' : '';
            }
            setTingkat();
            jenisSel.addEventListener('change', setTingkat);
        });
    </script>
@endpush
