@extends('layouts.home')

@section('title', 'Profil Santri - Edu Santri')
@section('content')
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: #13DEB9; color: white;">
                <h4 class="mb-0">Profil Santri</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Foto Santri -->
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('images/profile/user-1.jpg') }}" class="img-fluid rounded-circle mb-3"
                            style="width: 200px; height: 200px; object-fit: cover;" alt="Foto Santri">
                        <h4 class="mt-3">{{ $santri->nama }}</h4>
                        <p class="text-muted">NIS: {{ $santri->nis }}</p>
                    </div>

                    <!-- Data Santri -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="color: #13DEB9;">Data Pribadi</h5>
                                <ul class="list-group list-group-flush mb-4">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>NISN</span>
                                        <span>{{ $santri->nisn ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>NIK</span>
                                        <span>{{ $santri->nik ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Jenis Kelamin</span>
                                        <span>{{ $santri->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <span>Alamat</span>
                                            <div class="text-end" style="max-width: 60%">
                                                <span>{{ $santri->alamat->desa ?? '-' }}</span><br>
                                                <span>{{ $santri->alamat->kecamatan ?? '-' }}</span><br>
                                                <span>{{ $santri->alamat->kabupaten ?? '-' }}</span><br>
                                                <span>{{ $santri->alamat->provinsi ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Kamar</span>
                                        <span>{{ $santri->kamar->nama_kamar ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <h5 style="color: #13DEB9;">Pendidikan</h5>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2" style="color: #13DEB9;">Formal</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Sekolah</span>
                                            <span class="fw-medium">
                                                @if ($santri->kelasFormal->first())
                                                    {{ $santri->kelasFormal->first()->jenisSekolah->nama_sekolah ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Kelas</span>
                                            <span class="fw-medium">
                                                @if ($santri->kelasFormal->first())
                                                    {{ $santri->kelasFormal->first()->nama_kelas ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2" style="color: #13DEB9;">Non-Formal</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Sekolah</span>
                                            <span class="fw-medium">
                                                @if ($santri->kelasNonFormal->first())
                                                    {{ $santri->kelasNonFormal->first()->jenisSekolah->nama_sekolah ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Kelas</span>
                                            <span class="fw-medium">
                                                @if ($santri->kelasNonFormal->first())
                                                    {{ $santri->kelasNonFormal->first()->nama_kelas ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h5 style="color: #13DEB9;">Kontak</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>No. HP</span>
                                        <span>{{ $santri->orangTua->ayah->no_whatsapp ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Email</span>
                                        <span>{{ $santri->orangTua->user->email ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Orang Tua</span>
                                        <span>{{ $santri->orangTua->ayah->nama ?? '-' }} /
                                            {{ $santri->orangTua->ibu->nama ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
