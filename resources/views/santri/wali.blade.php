@extends('layouts.home')

@section('title', 'Profil Santri - Edu Santri')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background-color: #13DEB9;">
                <h4 class="mb-0">Profil Santri</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- FOTO PROFIL --}}
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('images/profile/user-1.jpg') }}" class="img-fluid rounded-circle mb-3"
                            style="width: 180px; height: 180px; object-fit: cover;" alt="Foto Santri">
                        <h5 class="mt-2">{{ $santri->nama_santri }}</h5>
                        <p class="text-muted">NIS: {{ $santri->nis }}</p>
                    </div>

                    {{-- DATA SANTRI --}}
                    <div class="col-md-8">
                        <div class="row">
                            {{-- Data Pribadi --}}
                            <div class="col-md-6">
                                <h5 class="text-success">Data Pribadi</h5>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>NISN</span>
                                        <span>{{ $santri->nisn ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Tempat Lahir</span>
                                        <span>{{ $santri->tempat_lahir ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Tanggal Lahir</span>
                                        <span>{{ \Carbon\Carbon::parse($santri->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Jenis Kelamin</span>
                                        <span>{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <span>Alamat</span><br>
                                        <div class="ms-3 text-muted" style="max-width: 80%;">
                                            {{ $santri->alamat ?? '-' }}
                                        </div>
                                    </li>

                                </ul>
                            </div>


                            {{-- === Pendidikan === --}}
                            <div class="col-md-6">
                                <h5 class="text-success">Pendidikan</h5>

                                @php
                                    $formal = $santri->detail->firstWhere(fn($d) => $d->sekolah);
                                @endphp

                                {{-- Formal --}}
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <span>Sekolah</span>
                                            <span>{{ $formal->sekolah->nama_sekolah ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Kelas</span>
                                            <span>{{ $formal->kelas->nama_kelas ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Tahun Akademik</span>
                                            <span>{{ $formal->tahunAkademik->tahun_akademik ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kontak --}}
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="text-success">Kontak & Orang Tua</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>No. HP</span>
                                            <span>{{ $santri->no_hp ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Ayah</span>
                                            <span>{{ $santri->ayah ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Ibu</span>
                                            <span>{{ $santri->ibu ?? '-' }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> {{-- end col-md-8 --}}
                    </div>
                </div>
            </div>
        </div>
    @endsection
