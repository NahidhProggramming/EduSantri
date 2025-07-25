@extends('layouts.app')
@section('title', 'Detail Pelanggaran')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('monitoring.pelanggaran') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <h4 class="fw-bold mb-3">{{ $santri->nama_santri }} <small class="text-muted">({{ $santri->nis }})</small></h4>

        @foreach ($pelanggarans as $p)
            <div
                class="card shadow-sm mb-3 border-start border-4
        border-{{ strtolower($p->jenisPelanggaran->tingkat) == 'berat'
            ? 'danger'
            : (strtolower($p->jenisPelanggaran->tingkat) == 'sedang'
                ? 'warning'
                : 'success') }}">
                <div class="card-body">
                    <span
                        class="badge rounded-pill
             bg-{{ strtolower($p->jenisPelanggaran->tingkat) == 'berat'
                 ? 'danger'
                 : (strtolower($p->jenisPelanggaran->tingkat) == 'sedang'
                     ? 'warning'
                     : 'success') }}">
                        {{ $p->jenisPelanggaran->tingkat }}
                    </span>
                    <p class="mt-2 mb-1 fw-semibold">{{ $p->jenisPelanggaran->nama_jenis }}</p>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</small>
                    <p class="mt-2 mb-0">{{ $p->deskripsi ?: '‑' }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection
