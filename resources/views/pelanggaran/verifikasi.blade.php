@extends('layouts.app')

@section('title', 'Verifikasi Pelanggaran')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="text-center fw-bold mb-4">Verifikasi Pelanggaran</h5>

                        <p class="text-center text-muted mb-4">
                            <strong>Nama Santri:</strong> {{ $pelanggaran->santri->nama_santri }}
                        </p>

                        <form action="{{ route('pelanggaran.verifikasi.submit', $pelanggaran->id_pelanggaran) }}"
                            method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="verifikasi" class="form-label">Status Verifikasi</label>
                                <select name="verifikasi_surat" id="verifikasi" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Terverifikasi">Terverifikasi</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success rounded-pill">
                                    <i class="ti ti-check"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
