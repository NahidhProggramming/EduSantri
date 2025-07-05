@extends('layouts.app')
@section('title', 'Data Pelanggaran')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold mb-4">Data Pelanggaran Santri</h5>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggaran">
                    <i class="ti ti-plus"></i> Tambah Pelanggaran
                </button>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Santri</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Tingkat</th>
                                <th>Tanggal</th>
                                <th>Status Verifikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pelanggarans as $no => $p)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $p->santri->nama_santri }}</td>
                                    <td>{{ $p->jenisPelanggaran->nama_jenis }}</td>
                                    <td>{{ $p->tingkatPelanggaran->nama_tingkat }}</td>
                                    <td>{{ $p->tanggal }}</td>
                                    <td>
                                        <span
                                            class="badge
                                        @if ($p->verifikasi_surat === 'Terverifikasi') bg-success
                                        @elseif($p->verifikasi_surat === 'Ditolak') bg-danger
                                        @else bg-warning text-dark @endif">
                                            {{ $p->verifikasi_surat }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pelanggaran.edit', $p->id_pelanggaran) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <form action="{{ route('pelanggaran.destroy', $p->id_pelanggaran) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>

                                        @if ($p->tingkatPelanggaran->nama_tingkat === 'Berat')
                                            <a href="{{ route('pelanggaran.verifikasi.form', $p->id_pelanggaran) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="ti ti-check"></i> Verifikasi
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">Belum ada data pelanggaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahPelanggaran" tabindex="-1" aria-labelledby="modalTambahLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('pelanggaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Pelanggaran Santri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" id="searchSantri" class="form-control" placeholder="Cari nama santri...">
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkAllSantri">
                            <label class="form-check-label fw-bold" for="checkAllSantri">
                                Pilih Semua
                            </label>
                        </div>

                        <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;" id="santriList">
                            @foreach ($santris as $s)
                                <div class="form-check">
                                    <input class="form-check-input santri-checkbox" type="checkbox" name="santri_id[]"
                                        value="{{ $s->nis }}" id="santri_{{ $s->nis }}">
                                    <label class="form-check-label" for="santri_{{ $s->nis }}">
                                        {{ $s->nama_santri }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <small class="text-muted">Centang beberapa santri yang ingin diberi pelanggaran.</small>

                        <div class="mb-3">
                            <label for="jenis_pelanggaran_id" class="form-label">Jenis Pelanggaran</label>
                            <select name="jenis_pelanggaran_id" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach ($jenisList as $jenis)
                                    <option value="{{ $jenis->id_jenis }}">{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tingkat_pelanggaran_id" class="form-label">Tingkat Pelanggaran</label>
                            <select name="tingkat_pelanggaran_id" class="form-select" required>
                                <option value="">-- Pilih Tingkat --</option>
                                @foreach ($tingkatList as $tk)
                                    <option value="{{ $tk->id_tingkat }}">{{ $tk->nama_tingkat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Pelanggaran</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Tulis keterangan pelanggaran..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="file_surat" class="form-label">Upload Surat (opsional)</label>
                            <input type="file" name="file_surat" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="santri_id" class="form-label">Pilih Santri</label>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('checkAllSantri').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.santri-checkbox').forEach(cb => cb.checked = checked);
        });

        document.getElementById('searchSantri').addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('#santriList .form-check').forEach(item => {
                const label = item.querySelector('label').textContent.toLowerCase();
                item.style.display = label.includes(keyword) ? '' : 'none';
            });
        });
    </script>

@endsection
