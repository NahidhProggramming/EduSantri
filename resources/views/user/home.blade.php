@extends('layouts.home')

@section('title', 'Halaman Utama - Edu Santri')
@section('content')
    <div class="bg-hero text-center">
        <div class="container">
            <h1 class="fw-bold">Sistem Monitoring Santri</h1>
            <p class="lead">Pantau Perkembangan Akademik dan Pelanggaran Santri</p>
        </div>
    </div>

    <div class="container my-5">
        <div class="highlight-box text-center">
            <div class="row gy-4">
                <div class="col-md-3">
                    <div class="icon-box">
                        <i class="bi bi-journal-bookmark fs-1" style="color: #13DEB9"></i>
                        <p class="mb-0">Nilai Akademik</p>
                        <a href="{{ route('nilai.index') }}" class="custom-button mt-2">Lihat Detail</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="icon-box">
                        <i class="bi bi-exclamation-triangle fs-1" style="color: #13DEB9"></i>
                        <p class="mb-0">Pelanggaran</p>
                        <a href="#" class="custom-button mt-2">Lihat Detail</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="icon-box">
                        <i class="bi bi-graph-up fs-1" style="color: #13DEB9"></i>
                        <p class="mb-0">Perkembangan</p>
                        <a href="#" class="custom-button mt-2">Lihat Detail</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="icon-box">
                        <i class="bi bi-person-lines-fill fs-1" style="color: #13DEB9"></i>
                        <p class="mb-0">Profil Santri</p>
                        <a href="{{ route('wali.santri') }}" class="custom-button mt-2">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <p class="mt-4">
                Sistem terpadu untuk memantau perkembangan santri secara menyeluruh <br>
                <a href="#" class="text-decoration-underline text-primary">Pelajari lebih lanjut tentang sistem
                    kami.</a>
            </p>

            <a href="{{ route('santri.index') }}" class="custom-button mt-2">Lihat Data Santri</a>
        </div>
    </div>
@endsection
