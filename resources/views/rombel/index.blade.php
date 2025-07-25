@extends('layouts.app')
@section('title', 'Rombongan Belajar')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold mb-4">Halaman Rombongan Belajar Berdasarkan Tahun Akademik</h5>
        {{-- 🔘 Tombol Tampilkan Panduan --}}
        <div class="mb-3">
            <button class="btn btn-info rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTutorialRombel">
                <i class="ti ti-help"></i> Lihat Tutorial Rombel
            </button>
        </div>

        {{-- Tombol Tahun Akademik --}}
        <div class="row mb-4">
            @foreach ($tahunList as $tahun)
                <div class="col-md-3">
                    <div class="card text-center shadow-sm border-0"
                        style="cursor: pointer; background-color: #13DEB9; color: #ffffff;"
                        onclick="selectTahun({{ $tahun->id_tahun_akademik }}, '{{ $tahun->tahun_akademik }}')">
                        <div class="card-body py-3">
                            <h5 class="fw-bold text-white">{{ $tahun->tahun_akademik }}</h5>
                            <span style="color: rgba(255,255,255,0.85); font-size: 14px;">Tahun Aktif</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>



        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Kontainer Rombel --}}
        {{-- Kontainer Rombel --}}
        <div id="rombel-container" class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4" id="rombel-header"
                    style="display: none;">
                    <h5 class="card-title fw-semibold" id="rombel-title"></h5>
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalRombel">
                        Tambah Rombongan Belajar
                    </button>
                </div>

                <div class="row">
                    {{-- MTs Table --}}
                    <div class="col-md-6">
                        <h6 class="fw-semibold">MTs</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Tingkat</th>
                                        <th>Jumlah Santri</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="rombel-body-mts" class="text-center">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Silakan pilih tahun akademik
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- SMP Table --}}
                    <div class="col-md-6">
                        <h6 class="fw-semibold">SMP</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Tingkat</th>
                                        <th>Jumlah Santri</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="rombel-body-smp" class="text-center">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Silakan pilih tahun akademik
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div class="modal fade" id="modalRombel" tabindex="-1" aria-labelledby="modalRombelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rombel.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun_akademik_id" value="{{ $tahun->id_tahun_akademik }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRombelLabel">Tambah Rombongan Belajar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="sekolah_id">Sekolah</label>
                            <select name="sekolah_id" id="sekolah_id" class="form-control" required>
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($sekolah as $s)
                                    <option value="{{ $s->id_sekolah }}">{{ $s->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="santri_id" class="form-label">Pilih Santri</label>

                            {{-- Checkbox Pilih Semua --}}
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="checkAllSantri">
                                <label class="form-check-label fw-bold" for="checkAllSantri">
                                    Pilih Semua
                                </label>
                            </div>

                            <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                                @foreach ($santri as $s)
                                    <div class="form-check">
                                        <input class="form-check-input santri-checkbox" type="checkbox" name="santri_id[]"
                                            value="{{ $s->nis }}" id="santri_{{ $s->nis }}">
                                        <label class="form-check-label" for="santri_{{ $s->nis }}">
                                            {{ $s->nama_santri }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <small class="text-muted">Centang beberapa santri yang ingin dimasukkan ke rombel.</small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <!-- Modal Naik Kelas -->
    <div class="modal fade" id="modalNaikKelas" tabindex="-1" aria-labelledby="modalNaikKelasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rombel.naikKelas') }}" method="POST">
                    @csrf

                    <input type="hidden" name="detail_id" id="detail_id_naikkelas">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNaikKelasLabel">Naik Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kelas_tujuan_id" class="form-label">Kelas Tujuan</label>
                            <select name="kelas_tujuan_id" id="kelas_tujuan_id" class="form-select" required>
                                @foreach ($kelas as $kelas)
                                    <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tahun_akademik_id" class="form-label">Tahun Akademik Tujuan</label>
                            <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-select" required>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun->id_tahun_akademik }}">{{ $tahun->tahun_akademik }} -
                                        {{ $tahun->semester }}</option>
                                @endforeach
                            </select>
                        </div>

                        <p class="mb-2">Pilih santri yang ingin dinaikkan:</p>
                        <div class="border rounded p-2" id="list-santri-naik"
                            style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted">Memuat data santri...</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Naikkan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!-- Modal Naik Kelas -->
    <div class="modal fade" id="modalNaikKelas" tabindex="-1" aria-labelledby="modalNaikKelasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rombel.naikKelas') }}" method="POST" id="formNaikKelas">
                    @csrf

                    <input type="hidden" name="detail_id" id="detail_id_naikkelas">
                    <input type="hidden" id="tahun_aktif_sekarang"
                        value="{{ $tahunList->first()->id_tahun_akademik ?? '' }}">
                    <input type="hidden" id="tingkat_santri_sekarang">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNaikKelasLabel">Naik Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Kelas Tujuan --}}
                        <div class="mb-3">
                            <label for="kelas_tujuan_id" class="form-label">Kelas Tujuan</label>
                            <select id="kelas_tujuan_id" name="kelas_tujuan_id" class="form-control">
                                <option value="">-- Pilih Kelas Tujuan --</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id_kelas }}" data-tingkat="{{ $item->tingkat }}">
                                        {{ $item->nama_kelas }} (Tingkat {{ $item->tingkat }})
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        {{-- Tahun Akademik Tujuan --}}
                        <div class="mb-3">

                            <label for="tahun_akademik_id" class="form-label">Tahun Akademik Tujuan</label>
                            <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-select" required>
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach ($tahunA as $tahun)
                                    <option value="{{ $tahun->id_tahun_akademik }}">
                                        {{ $tahun->tahun_akademik }} - {{ $tahun->semester }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="peringatanTahun" class="text-danger d-none">
                                Tahun akademik masih berjalan. Silakan pilih tahun lainnya.
                            </small>

                        </div>

                        {{-- Daftar Santri --}}
                        <p class="mb-2">Pilih santri yang ingin dinaikkan:</p>
                        <div class="border rounded p-2" id="list-santri-naik"
                            style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted">Memuat data santri...</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitNaik">Naikkan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Santri -->
    <div class="modal fade" id="modalHapusSantri" tabindex="-1" aria-labelledby="modalHapusSantriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rombel.hapusSantri') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="detail_id" id="detail_id_hapussantri">


                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusSantriLabel">Hapus Santri dari Rombel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">Pilih santri yang ingin dihapus dari rombel ini:</p>

                        <div class="form-check mb-2">
                            <input class="form-check-input check-all-santri" type="checkbox" id="checkAllSantri">
                            <label class="form-check-label fw-bold" for="checkAllSantri">Pilih Semua</label>
                        </div>

                        <div class="border rounded p-3" id="list-santri-hapus"
                            style="max-height: 300px; overflow-y: auto;">
                            <!-- Checkbox santri akan dimuat lewat JS -->
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Hapus Terpilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Tutorial Rombel -->
    <div class="modal fade" id="modalTutorialRombel" tabindex="-1" aria-labelledby="tutorialRombelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="tutorialRombelLabel">
                        <i class="ti ti-info-circle"></i> Panduan Penggunaan Menu Rombongan Belajar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <ol class="lh-lg">
                        <li><strong>Pilih Tahun Akademik</strong> dari daftar tombol yang tersedia.</li>
                        <li><strong>Lihat data Rombongan Belajar</strong> berdasarkan sekolah MTs atau SMP.</li>
                        <li>Klik <strong>Tambah Rombongan Belajar</strong> untuk menambahkan:
                            <ul class="mt-1">
                                <li>Pilih sekolah</li>
                                <li>Pilih kelas</li>
                                <li>Centang santri yang ingin dimasukkan</li>
                            </ul>
                        </li>
                        <li>Gunakan tombol <span class="badge bg-danger"><i class="ti ti-trash"></i></span> untuk
                            menghapus santri dari rombel tertentu.</li>
                        <li>Gunakan tombol <span class="badge bg-primary"><i class="ti ti-arrow-up-circle"></i></span>
                            untuk <strong>menaikkan kelas santri</strong> ke tahun ajaran berikutnya.</li>
                    </ol>
                    <p class="text-muted mt-3">
                        Pastikan data tahun akademik dan daftar kelas sudah tersedia sebelum memulai pembagian rombel.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Script --}}
    <script>
        let selectedTahunId = null;
        let selectedTahunText = '';

        function selectTahun(tahunId, tahunText) {
            selectedTahunId = tahunId;
            selectedTahunText = tahunText;

            // Tampilkan header
            document.getElementById('rombel-header').style.display = 'flex';

            // Set judul
            document.getElementById('rombel-title').textContent =
                `Data Rombongan Belajar Tahun Akademik ${selectedTahunText}`;

            // Tampilkan loading di tabel
            const rombelMts = document.getElementById('rombel-body-mts');
            const rombelSmp = document.getElementById('rombel-body-smp');
            rombelMts.innerHTML = '<tr><td colspan="4" class="text-center py-4">Memuat data...</td></tr>';
            rombelSmp.innerHTML = '<tr><td colspan="4" class="text-center py-4">Memuat data...</td></tr>';

            fetch(`/rombongan-belajar?tahun=${tahunId}`)
                .then(res => res.json())
                .then(data => {
                    rombelMts.innerHTML = '';
                    rombelSmp.innerHTML = '';

                    // Jika data kosong
                    if (data.length === 0) {
                        rombelMts.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="ti ti-database-off" style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data rombongan belajar</p>
                        </td>
                    </tr>
                `;
                        rombelSmp.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="ti ti-database-off" style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data rombongan belajar</p>
                        </td>
                    </tr>
                `;
                        return;
                    }

                    let noMts = 1;
                    let noSmp = 1;
                    let hasMts = false;
                    let hasSmp = false;

                    data.forEach(item => {
                        let row = '';

                        if (item.nama_sekolah === 'MTs') {
                            row = `
                        <tr>
                            <td>${noMts++}</td>
                            <td>${item.nama_kelas}</td>
                            <td>${item.tingkat}</td>
                            <td>${item.jumlah_siswa}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Santri"
                                    onclick="bukaHapusSantri(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                               <button type="button" class="btn btn-sm btn-primary" title="Naik Kelas"
                                  onclick="bukaNaikKelas(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}', ${item.tingkat})">
                                     <i class="ti ti-arrow-up-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                            rombelMts.innerHTML += row;
                            hasMts = true;
                        } else if (item.nama_sekolah === 'SMP') {
                            row = `
                        <tr>
                            <td>${noSmp++}</td>
                            <td>${item.nama_kelas}</td>
                            <td>${item.tingkat}</td>
                            <td>${item.jumlah_siswa}</td>
                            <td class="text-center">
                                 <button type="button" class="btn btn-sm btn-danger" title="Hapus Santri"
                                    onclick="bukaHapusSantri(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" title="Naik Kelas"
                                  onclick="bukaNaikKelas(${item.id_detail}, '${item.nama_kelas}', '${item.nama_sekolah}', ${item.tingkat})">
                                    <i class="ti ti-arrow-up-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                            rombelSmp.innerHTML += row;
                            hasSmp = true;
                        }
                    });

                    if (!hasMts) {
                        rombelMts.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="ti ti-school-off" style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data untuk MTs</p>
                        </td>
                    </tr>
                `;
                    }
                    if (!hasSmp) {
                        rombelSmp.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="ti ti-school-off" style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data untuk SMP</p>
                        </td>
                    </tr>
                `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    rombelMts.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-danger py-4">
                        <i class="ti ti-alert-circle" style="font-size: 48px;"></i>
                        <p class="mt-2">Terjadi kesalahan saat memuat data</p>
                        <small>${error.message}</small>
                    </td>
                </tr>
            `;
                    rombelSmp.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-danger py-4">
                        <i class="ti ti-alert-circle" style="font-size: 48px;"></i>
                        <p class="mt-2">Terjadi kesalahan saat memuat data</p>
                        <small>${error.message}</small>
                    </td>
                </tr>
            `;
                });
        }

        // function bukaNaikKelas(detailId, kelasNama, sekolahNama) {
        //     document.getElementById('detail_id_naikkelas').value = detailId;
        //     document.getElementById('list-santri-naik').innerHTML = '<p class="text-muted">Memuat data santri...</p>';

        //     fetch(`/rombongan-belajar/santri/${detailId}`)
        //         .then(res => res.json())
        //         .then(data => {
        //             let html = '';
        //             if (data.length === 0) {
        //                 html = '<p class="text-muted">Tidak ada santri dalam rombel ini.</p>';
        //             } else {
        //                 html += `
    //         <div class="form-check mb-2">
    //             <input class="form-check-input" type="checkbox" id="checkAllNaik">
    //             <label class="form-check-label" for="checkAllNaik">Pilih Semua</label>
    //         </div>
    //         `;

        //                 data.forEach(s => {
        //                     html += `
    //                 <div class="form-check">
    //                     <input class="form-check-input santri-naik-checkbox" type="checkbox" name="santri_id[]" value="${s.nis}" id="naik_${s.nis}">
    //                     <label class="form-check-label" for="naik_${s.nis}">${s.nama_santri}</label>
    //                 </div>
    //             `;
        //                 });
        //             }

        //             document.getElementById('list-santri-naik').innerHTML = html;

        //             // Event listener untuk "Pilih Semua"
        //             const checkAll = document.getElementById('checkAllNaik');
        //             const checkboxes = document.querySelectorAll('.santri-naik-checkbox');

        //             if (checkAll) {
        //                 checkAll.addEventListener('change', function() {
        //                     checkboxes.forEach(cb => cb.checked = this.checked);
        //                 });

        //                 checkboxes.forEach(cb => {
        //                     cb.addEventListener('change', function() {
        //                         checkAll.checked = document.querySelectorAll(
        //                             '.santri-naik-checkbox:checked').length === checkboxes.length;
        //                     });
        //                 });
        //             }
        //         });

        //     const modal = new bootstrap.Modal(document.getElementById('modalNaikKelas'));
        //     modal.show();
        // }
        function bukaNaikKelas(detailId, kelasNama, sekolahNama, tingkatSekarang) {
            document.getElementById('detail_id_naikkelas').value = detailId;
            document.getElementById('tingkat_santri_sekarang').value = tingkatSekarang;
            document.getElementById('list-santri-naik').innerHTML = '<p class="text-muted">Memuat data santri...</p>';

            // Filter kelas tujuan (hanya tingkat +1, tidak lebih dari 8)
            const selectKelas = document.getElementById('kelas_tujuan_id');
            const tingkatInt = parseInt(tingkatSekarang);

            console.log("Tingkat sekarang:", tingkatInt);
            Array.from(selectKelas.options).forEach(option => {
                const tingkatOption = parseInt(option.dataset.tingkat);
                console.log(
                    `Option: ${option.text}, data-tingkat: ${option.dataset.tingkat}, parsed: ${tingkatOption}`);

                const isValid = (tingkatOption === tingkatInt + 1) && tingkatOption <= 8;
                option.hidden = !isValid;
            });


            selectKelas.value = ''; // reset pilihan

            // Ambil data santri
            fetch(`/rombongan-belajar/santri/${detailId}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';

                    if (data.length === 0) {
                        html = '<p class="text-muted">Tidak ada santri dalam rombel ini.</p>';
                    } else {
                        html += `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="checkAllNaik">
                        <label class="form-check-label" for="checkAllNaik">Pilih Semua</label>
                    </div>
                `;

                        data.forEach(s => {
                            html += `
                        <div class="form-check">
                            <input class="form-check-input santri-naik-checkbox" type="checkbox" name="santri_id[]" value="${s.nis}" id="naik_${s.nis}">
                            <label class="form-check-label" for="naik_${s.nis}">${s.nama_santri}</label>
                        </div>
                    `;
                        });
                    }

                    const listContainer = document.getElementById('list-santri-naik');
                    listContainer.innerHTML = html;

                    // Checkbox "Pilih Semua"
                    const checkAll = document.getElementById('checkAllNaik');
                    const checkboxes = document.querySelectorAll('.santri-naik-checkbox');

                    if (checkAll) {
                        checkAll.addEventListener('change', function() {
                            checkboxes.forEach(cb => cb.checked = this.checked);
                        });

                        checkboxes.forEach(cb => {
                            cb.addEventListener('change', function() {
                                const totalChecked = document.querySelectorAll(
                                    '.santri-naik-checkbox:checked').length;
                                checkAll.checked = totalChecked === checkboxes.length;
                            });
                        });
                    }
                });

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('modalNaikKelas'));
            modal.show();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const tahunAktif = document.getElementById('tahun_aktif_sekarang').value;
            const selectTahun = document.getElementById('tahun_akademik_id');
            const peringatan = document.getElementById('peringatanTahun');
            const btnSubmit = document.getElementById('btnSubmitNaik');

            function checkTahunTerpilih() {
                const tahunTerpilih = selectTahun.value;
                if (tahunTerpilih === tahunAktif) {
                    peringatan.classList.remove('d-none');
                    btnSubmit.disabled = true;
                } else {
                    peringatan.classList.add('d-none');
                    btnSubmit.disabled = false;
                }
            }

            selectTahun.addEventListener('change', checkTahunTerpilih);
        });




        function bukaHapusSantri(detailId, namaKelas, namaSekolah) {
            const listContainer = document.getElementById('list-santri-hapus');
            const checkAllBox = document.querySelector('.check-all-santri');
            document.getElementById('detail_id_hapussantri').value = detailId;

            // Tampilkan loading
            listContainer.innerHTML = `<p class="text-muted">Memuat data santri...</p>`;

            fetch(`/rombongan-belajar/santri/${detailId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        listContainer.innerHTML = '<p class="text-muted">Tidak ada santri dalam rombel ini.</p>';
                        checkAllBox.disabled = true;
                        return;
                    }

                    let html = '';
                    data.forEach(s => {
                        html += `
    <div class="form-check mb-1">
        <input class="form-check-input santri-checkbox" type="checkbox" name="santri_id[]" value="${s.nis}"
            id="hapus_${s.nis}">
        <label class="form-check-label" for="hapus_${s.nis}">${s.nama_santri}</label>
    </div>
    `;
                    });

                    listContainer.innerHTML = html;

                    const checkboxes = listContainer.querySelectorAll('.santri-checkbox');

                    // Reset state
                    checkAllBox.checked = false;
                    checkAllBox.disabled = false;

                    checkAllBox.addEventListener('change', function() {
                        checkboxes.forEach(cb => cb.checked = this.checked);
                    });

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', function() {
                            const totalChecked = listContainer.querySelectorAll(
                                '.santri-checkbox:checked').length;
                            checkAllBox.checked = (totalChecked === checkboxes.length);
                        });
                    });
                });

            const modal = new bootstrap.Modal(document.getElementById('modalHapusSantri'));
            modal.show();
        }


        function confirmHapusSantri() {
            const selected = document.querySelectorAll('.santri-checkbox:checked');
            if (selected.length === 0) {
                alert('Silakan pilih minimal satu santri untuk dihapus.');
                return false;
            }
            return true;
        }



        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAllSantri');
            const checkboxes = document.querySelectorAll('.santri-checkbox');

            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', function() {
                        checkAll.checked = document.querySelectorAll('.santri-checkbox:checked')
                            .length === checkboxes.length;
                    });
                });
            }
        });

        function togglePanduan() {
            const panduan = document.getElementById('panduanRombel');
            panduan.classList.toggle('d-none');
        }
    </script>
@endsection
