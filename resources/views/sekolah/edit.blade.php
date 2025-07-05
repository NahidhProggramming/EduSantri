@extends('layouts.app')

@section('title', 'Edit Data Santri')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">Edit Data Santri</h3>

                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('sekolah.index') }}" class="btn btn-outline-danger rounded-pill px-4">
                        <i class="ti ti-x me-2"></i> Batal
                    </a>
                </div>

                <form action="{{ route('sekolah.update', $sekolah->id_jenis) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Data Santri --}}
                    <h5 class="fw-semibold mb-3">Daftar Sekolah</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" class="form-control"
                                value="{{ $sekolah->nama_sekolah }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tipe" class="form-label">Tipe</label>
                            <select name="tipe" id="tipe" class="form-select" required>
                                <option value="">Pilih Tipe Sekolah</option>
                                <option value="formal"
                                    {{ old('tipe', $sekolah->tipe ?? '') == 'formal' ? 'selected' : '' }}>Formal</option>
                                <option value="non-formal"
                                    {{ old('tipe', $sekolah->tipe ?? '') == 'non-formal' ? 'selected' : '' }}>Non Formal
                                </option>
                            </select>
                        </div>


                        <div class="mt-5 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success rounded-pill px-5 py-2">
                                <i class="ti ti-device-floppy me-2"></i> Update Data
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
