@extends('layouts.app')

@section('title', 'Tanggal Cetak')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">Tanggal Cetak</h3>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('tanggal-cetak.update') }}" method="POST">
                    @csrf
                    @method("PUT")

                    <div class="mb-3">
                        <label for="tempat" class="form-label">Tempat Cetak</label>
                        <input type="text" name="tempat" id="tempat" class="form-control"
                            value="{{ old('tempat', $akademikAktif->tempat) }}" required>

                    </div>

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Cetak</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                            value="{{ old('tanggal', $akademikAktif->tanggal ? \Carbon\Carbon::parse($akademikAktif->tanggal)->format('Y-m-d') : '') }}">

                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
