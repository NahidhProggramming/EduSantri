@extends('layouts.app')
@section('title', 'Data Pelanggaran Santri')

@section('content')
    {{-- Bagian Atas --}}
    <div class="container-fluid px-3 px-md-5">
        <h5 class="fw-bold mb-4">Data Pelanggaran Santri</h5>

        @role('kesiswaan')
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-success rounded-pill px-4 py-2" data-bs-toggle="modal"
                    data-bs-target="#modalTambahPelanggaran">
                    <i class="ti ti-plus"></i> Tambah Pelanggaran
                </button>

                {{-- Tombol Download Template --}}
                <a href="{{ route('pelanggaran.download.template') }}" class="btn btn-info rounded-pill px-4 py-2">
                    <i class="ti ti-download"></i> Download Template
                </a>
            </div>
        @endrole

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        @php
            $tampilkanKolomVerifikasi = $pelanggarans->contains(
                fn($p) => strtolower($p->jenisPelanggaran->tingkat ?? '') === 'berat',
            );
        @endphp

        {{-- Tabel --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Nama Santri</th>
                                <th>Jenis</th>
                                <th>Tingkat</th>
                                <th>Tanggal</th>
                                @if ($tampilkanKolomVerifikasi)
                                    <th>Status</th>
                                    <th>Surat</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pelanggarans as $no => $p)
                                <tr>
                                    <td>{{ $no + 1 }}</td>

                                    <td>
                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                            {{-- Tombol Edit --}}
                                            @role('kesiswaan')
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditPelanggaran{{ $p->id_pelanggaran }}">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <form action="{{ route('pelanggaran.destroy', $p->id_pelanggaran) }}"
                                                    method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                                </form>
                                            @endrole
                                        </div>

                                        {{-- ============ MODAL EDIT ============ --}}
                                        <div class="modal fade" id="modalEditPelanggaran{{ $p->id_pelanggaran }}"
                                            tabindex="-1" aria-labelledby="editLabel{{ $p->id_pelanggaran }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <form action="{{ route('pelanggaran.update', $p->id_pelanggaran) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    class="modal-content border-0 shadow">
                                                    @csrf @method('PUT')

                                                    {{-- HEADER --}}
                                                    <div class="modal-header text-white">
                                                        <h5 class="modal-title" id="editLabel{{ $p->id_pelanggaran }}">
                                                            <i class="ti ti-edit me-2"></i> Edit Pelanggaran
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-black"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>

                                                    {{-- BODY – cukup beri text-start pada .modal-body --}}
                                                    <div class="modal-body py-4 px-4 text-start">

                                                        {{-- Santri --}}
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Santri</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $p->santri->nama_santri }} ({{ $p->santri_nis }})"
                                                                readonly>
                                                        </div>

                                                        {{-- Jenis Pelanggaran --}}
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Jenis Pelanggaran</label>
                                                            <select name="jenis_pelanggaran_id" class="form-select" required
                                                                id="jenisSelect{{ $p->id_pelanggaran }}"
                                                                data-target="#tingkatReadonly{{ $p->id_pelanggaran }}"
                                                                onchange="toggleFileInput(this, 'wrapSurat{{ $p->id_pelanggaran }}')">
                                                                @foreach ($jenisList as $jenis)
                                                                    <option value="{{ $jenis->id_jenis }}"
                                                                        data-tingkat="{{ $jenis->tingkat }}"
                                                                        @selected($jenis->id_jenis == $p->jenis_pelanggaran_id)>
                                                                        {{ $jenis->nama_jenis }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Tingkat (readonly) --}}
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Tingkat</label>
                                                            <input type="text" class="form-control"
                                                                id="tingkatReadonly{{ $p->id_pelanggaran }}"
                                                                value="{{ $p->jenisPelanggaran->tingkat }}" readonly>
                                                        </div>

                                                        {{-- Tanggal --}}
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Tanggal</label>
                                                            <input type="date" name="tanggal" class="form-control"
                                                                value="{{ \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') }}"
                                                                required>
                                                        </div>

                                                        {{-- Deskripsi --}}
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi</label>
                                                            <textarea name="deskripsi" rows="2" class="form-control" placeholder="Keterangan …">{{ $p->deskripsi }}</textarea>
                                                        </div>

                                                        {{-- Surat --}}
                                                        <div class="mb-3" id="wrapSurat{{ $p->id_pelanggaran }}"
                                                            style="{{ strtolower($p->jenisPelanggaran->tingkat) === 'berat' ? '' : 'display:none' }}">
                                                            <label class="form-label fw-semibold">
                                                                Upload Surat <small class="text-muted">(khusus
                                                                    tingkat Berat)</small>
                                                            </label>
                                                            <input type="file" name="file_surat" class="form-control"
                                                                accept=".pdf,.jpg,.jpeg,.png">
                                                            @if ($p->file_surat)
                                                                <small class="d-block mt-1">
                                                                    Surat lama: <a
                                                                        href="{{ asset('surat_pelanggaran/' . $p->file_surat) }}"
                                                                        target="_blank">lihat</a>
                                                                </small>
                                                            @endif
                                                        </div>

                                                    </div>

                                                    {{-- FOOTER --}}
                                                    <div class="modal-footer py-3 px-4">
                                                        <button type="submit" class="btn btn-warning rounded-pill">
                                                            <i class="ti ti-device-floppy"></i> Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        @php
                                            /** @var \App\Models\Guru|null $guruLogin */
                                            $guruLogin = auth()->user()->guru;

                                            // cek: login sebagai guru?  santri punya Detail?  wali_kelas_id sama dengan id guru login?
                                            $bolehVerifikasi =
                                                $guruLogin &&
                                                optional($p->santri->detail->first()?->kelas)->wali_kelas_id ===
                                                    $guruLogin->id_guru;
                                        @endphp

                                        @if ($bolehVerifikasi)
                                            <button
                                                class="btn btn-sm btn-info d-flex align-items-center gap-1 rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalVerifikasi{{ $p->id_pelanggaran }}">
                                                <i class="ti ti-check"></i> <span>Verifikasi</span>
                                            </button>

                                            <!-- MODAL VERIFIKASI PELANGGARAN -->
                                            <div class="modal fade" id="modalVerifikasi{{ $p->id_pelanggaran }}"
                                                tabindex="-1" aria-labelledby="verifikasiLabel{{ $p->id_pelanggaran }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form
                                                        action="{{ route('pelanggaran.verifikasi.submit', $p->id_pelanggaran) }}"
                                                        method="POST" class="modal-content border-0 shadow">
                                                        @csrf

                                                        <!-- HEADER -->
                                                        <div class="modal-header text-white">
                                                            <h5 class="modal-title"
                                                                id="verifikasiLabel{{ $p->id_pelanggaran }}">
                                                                <i class="ti ti-check me-1"></i> Verifikasi Pelanggaran
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-black"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <!-- BODY -->
                                                        <div class="modal-body px-4 py-3 text-start">
                                                            <p><strong>Nama Santri:</strong>
                                                                {{ $p->santri->nama_santri }}
                                                            </p>

                                                            <div class="mb-3">
                                                                <label for="verifikasiSelect{{ $p->id_pelanggaran }}"
                                                                    class="form-label fw-semibold">Status
                                                                    Verifikasi</label>
                                                                <select name="verifikasi_surat"
                                                                    id="verifikasiSelect{{ $p->id_pelanggaran }}"
                                                                    class="form-select" required>
                                                                    <option value="">-- Pilih --</option>
                                                                    <option value="Terverifikasi">Terverifikasi
                                                                    </option>
                                                                    <option value="Ditolak">Ditolak</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- FOOTER -->
                                                        <div class="modal-footer px-4 py-3">
                                                            <button type="submit" class="btn btn-success rounded-pill">
                                                                <i class="ti ti-check"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $p->santri->nama_santri }}</td>
                                    <td>{{ $p->jenisPelanggaran->nama_jenis }}</td>
                                    <td>{{ $p->jenisPelanggaran->tingkat }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>

                                    @if ($tampilkanKolomVerifikasi)
                                        @php
                                            $statusStyle = [
                                                'Terverifikasi' => ['success', 'ti ti-check-circle'],
                                                'Ditolak' => ['danger', 'ti ti-x-circle'],
                                                'Menunggu' => ['warning', 'ti ti-clock'],
                                            ];
                                            $stat = $p->verifikasi_surat ?? 'Menunggu';
                                            [$clr, $ico] = $statusStyle[$stat] ?? ['secondary', 'ti ti-help'];
                                        @endphp
                                        @if (strtolower($p->jenisPelanggaran->tingkat) === 'berat')
                                            <td>
                                                <span class="badge bg-{{ $clr }} d-inline-flex gap-1 px-3">
                                                    <i class="{{ $ico }}"></i> {{ $stat }}
                                                </span>
                                            </td>
                                        @else
                                            <td class="text-muted">—</td>
                                        @endif

                                        <td>
                                            @if ($p->file_surat)
                                                @php
                                                    // File sekarang di public, jadi kita cek langsung di public
                                                    $filePath = public_path('surat_pelanggaran/' . $p->file_surat);
                                                @endphp

                                                @if (file_exists($filePath))
                                                    <a href="{{ asset('surat_pelanggaran/' . $p->file_surat) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center justify-content-center gap-1">
                                                        <i class="ti ti-file-download"></i> Lihat Surat
                                                    </a>
                                                @else
                                                    <span class="badge bg-warning text-dark" title="File tidak ditemukan">
                                                        <i class="ti ti-alert-triangle"></i> File Hilang
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-light text-muted">
                                                    <i class="ti ti-ban"></i> Tidak ada
                                                </span>
                                            @endif
                                        </td>
                                    @endif

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @role('kesiswaan')
        {{-- ========== MODAL TAMBAH PELANGGARAN ========== --}}
        <div class="modal fade" id="modalTambahPelanggaran" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('pelanggaran.store') }}" method="POST" enctype="multipart/form-data" <form
                    action="{{ route('pelanggaran.store') }}" method="POST" enctype="multipart/form-data"
                    class="modal-content"> @csrf {{-- HEADER --}} <div class="modal-header">
                        <h5 class="modal-title"><i class="ti ti-plus me-2"></i>Tambah Pelanggaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body">

                        {{-- ‑‑‑ pencarian santri --}}
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" id="searchSantri" class="form-control" placeholder="Cari nama santri…">
                        </div>

                        {{-- ‑‑‑ checklist --}}
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkAllSantri">
                            <label class="form-check-label fw-bold" for="checkAllSantri">Pilih Semua</label>
                        </div>

                        <div class="border rounded p-2 mb-3" style="max-height:300px;overflow-y:auto" id="santriList">
                            @foreach ($santris as $s)
                                <div class="form-check">
                                    <input class="form-check-input santri-checkbox" type="checkbox" name="santri_id[]"
                                        value="{{ $s->nis }}" id="santri_{{ $s->nis }}">
                                    <label class="form-check-label"
                                        for="santri_{{ $s->nis }}">{{ $s->nama_santri }}</label>
                                </div>
                            @endforeach
                        </div>

                        {{-- ‑‑‑ jenis pelanggaran --}}
                        <div class="mb-3">
                            <label class="form-label">Jenis Pelanggaran</label>
                            <select name="jenis_pelanggaran_id" id="jenisSelect" class="form-select" required
                                onchange="toggleFileInput(this, 'wrapSurat')">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach ($jenisList as $jenis)
                                    <option value="{{ $jenis->id_jenis }}" data-tingkat="{{ $jenis->tingkat }}">
                                        {{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ‑‑‑ tingkat tampil otomatis --}}
                        <div class="mb-3" id="wrapTingkat" style="display:none">
                            <label class="form-label">Tingkat</label>
                            <input type="text" class="form-control" id="tingkatReadonly" readonly>
                        </div>

                        {{-- ‑‑‑ tanggal --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pelanggaran</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>

                        {{-- ‑‑‑ deskripsi --}}
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="2" class="form-control"></textarea>
                        </div>

                        {{-- ‑‑‑ surat --}}
                        <div class="mb-1" id="wrapSurat" style="display:none">
                            <label class="form-label">Upload Surat <small class="text-muted">(khusus
                                    tingkat Berat)</small></label>
                            <input type="file" name="file_surat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Maksimal 2 MB</small>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endrole

    {{-- Script --}}
    <script>
        // Fungsi untuk menampilkan/menyembunyikan input file surat
        function toggleFileInput(selectElement, wrapperId) {
            const wrapper = document.getElementById(wrapperId);
            const tingkat = selectElement.selectedOptions[0].dataset.tingkat;

            if (tingkat && tingkat.toLowerCase() === 'berat') {
                wrapper.style.display = '';
            } else {
                wrapper.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {

            /* tampilkan tingkat */
            const jenisSel = document.getElementById('jenisSelect');
            const tingkatInp = document.getElementById('tingkatReadonly');
            const wrapTingkat = document.getElementById('wrapTingkat');

            jenisSel.addEventListener('change', e => {
                const opt = e.target.selectedOptions[0];
                const tkt = opt.dataset.tingkat || '';
                tingkatInp.value = tkt;
                wrapTingkat.style.display = tkt ? '' : 'none';

                // Panggil fungsi toggle untuk tambah pelanggaran
                toggleFileInput(jenisSel, 'wrapSurat');
            });

            /* panggil toggle saat halaman dimuat (untuk edit) */
            document.querySelectorAll('[id^="jenisSelect"]').forEach(sel => {
                // Panggil toggle saat halaman dimuat
                const wrapperId = 'wrapSurat' + sel.id.replace('jenisSelect', '');
                toggleFileInput(sel, wrapperId);

                // Tambahkan event listener untuk perubahan
                sel.addEventListener('change', e => {
                    toggleFileInput(sel, wrapperId);
                });
            });

            /* pilih semua santri */
            document.getElementById('checkAllSantri').addEventListener('change', e => {
                document.querySelectorAll('.santri-checkbox').forEach(cb => cb.checked = e.target.checked);
            });

            /* live search santri */
            document.getElementById('searchSantri').addEventListener('keyup', e => {
                const key = e.target.value.toLowerCase();
                document.querySelectorAll('#santriList .form-check').forEach(div => {
                    div.style.display = div.textContent.toLowerCase().includes(key) ? '' : 'none';
                });
            });

            /* inisialisasi tingkat untuk modal tambah */
            document.querySelectorAll('[id^="jenisSelect"]').forEach(sel => {
                sel.addEventListener('change', e => {
                    const opt = e.target.selectedOptions[0];
                    const tingkat = opt.dataset.tingkat ?? '-';
                    document.querySelector(sel.dataset.target).value = tingkat;
                });
            });
        });
    </script>
@endsection
