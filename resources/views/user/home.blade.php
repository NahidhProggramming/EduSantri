@extends('layouts.home')

@section('title', 'Halaman Utama - Edu Santri')

@section('content')
    <!-- Hero Section -->
    <div class="bg-hero text-center py-5 text-white"
        style="background: linear-gradient(90deg,#13DEB9 0%,#31E1C4 45%,#5FEBD4 100%);background-blend-mode:screen;">
        <div class="container">
            <h1 class="fw-bold display-5 mb-3">Sistem Monitoring Santri</h1>
            <p class="lead">Pantau Perkembangan Akademik dan Pelanggaran Santri dengan Mudah</p>

            @php $user = Auth::user(); @endphp
            @if ($user && $user->hasRole('wali_santri'))
                <p class="mt-3 fw-semibold">
                    Login sebagai Wali Santri
                    <span class="badge bg-light text-dark ms-2">
                        {{ $user->santri ? $user->santri->nama_santri : 'Belum Terdaftar' }}
                    </span>
                </p>
                <button class="btn btn-light btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#akunModal">
                    <i class="bi bi-person-lines-fill me-1"></i> Lihat Akun
                </button>
            @endif
        </div>
    </div>

    <!-- Fitur Utama -->
    <div class="container my-5">
        <div class="text-center mb-4">
            <h2 class="fw-semibold">Fitur Unggulan</h2>
            <p class="text-muted">Beberapa fitur utama yang bisa Anda akses langsung dari sistem</p>
        </div>

        <div class="row gy-4">
            @php
                $features = [
                    ['icon' => 'journal-bookmark', 'title' => 'Nilai Akademik', 'route' => route('wali.nilai')],
                    ['icon' => 'exclamation-triangle', 'title' => 'Pelanggaran', 'route' => route('wali.pelanggaran')],
                    ['icon' => 'graph-up', 'title' => 'Perkembangan', 'route' => route('grafik.santri')],
                    ['icon' => 'person-lines-fill', 'title' => 'Profil Santri', 'route' => route('wali.santri')],
                ];
            @endphp

            @foreach ($features as $feature)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 text-center p-4 hover-shadow rounded-4">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light shadow-sm"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-{{ $feature['icon'] }} fs-2" style="color: #13DEB9;"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">{{ $feature['title'] }}</h5>
                        <a href="{{ $feature['route'] }}" class="btn btn-outline-success btn-sm"
                            style="--bs-btn-border-color: #13DEB9; --bs-btn-color: #13DEB9;">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Informasi Tambahan -->
        <div class="text-center mt-5">
            <p class="mb-2">
                Sistem terpadu untuk memantau perkembangan santri secara menyeluruh.
            </p>
        </div>
    </div>

    <!-- Modal Akun -->
    <div class="modal fade" id="akunModal" tabindex="-1" aria-labelledby="akunModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('profile.password') }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="akunModalLabel">
                        <i class="bi bi-person-badge me-2 text-primary"></i> Informasi Akun
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->username }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" id="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            id="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
