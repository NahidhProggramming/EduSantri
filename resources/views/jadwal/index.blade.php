@extends('layouts.app')
@section('title', 'Jadwal Kelas')

@section('content')
    <div class="container-fluid">
        <h5 class="fw-bold mb-4">Halaman Jadwal Berdasarkan Tahun Akademik</h5>

        {{-- Tombol Tahun Akademik --}}
        <div class="row mb-4">
            @foreach ($tahunList as $tahun)
                <div class="col-md-3">
                    <div class="card text-center shadow-sm border-0"
                        style="cursor: pointer; background-color: #13DEB9; color: #ffffff;"
                        onclick="selectTahun({{ $tahun->id_tahun_akademik }}, '{{ $tahun->tahun_akademik }}')">
                        <div class="card-body py-3">
                            <h5 class="fw-bold text-white">{{ $tahun->tahun_akademik }}</h5>
                            <span style="color: rgba(255,255,255,0.85); font-size: 14px;">Pilih Tahun</span>
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
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tombol Sekolah --}}
        <div id="sekolah-container" class="mb-4 d-none">
            <h5 class="fw-semibold mb-3">Pilih Sekolah:</h5>
            <div class="d-flex gap-3 flex-wrap" id="sekolah-buttons">
                <!-- Tombol sekolah akan dimuat -->
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <div class="d-flex gap-2">
                <button class="btn btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                    <i class="ti ti-plus"></i> Tambah Jadwal
                </button>

                <button class="btn btn-info rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTutorialJadwal">
                    <i class="ti ti-help"></i> Lihat Tutorial
                </button>
            </div>
        </div>

        {{-- Tabel Jadwal (Tampil Kosong Saat Pertama) --}}
        <div id="jadwal-container" class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4" id="jadwal-title">Jadwal Kelas</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Aksi</th>
                                {{-- <th>No</th> --}}
                                <th>Status</th>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody id="jadwal-body" class="text-center">
                            <tr>
                                <td colspan="9" class="text-muted py-5">
                                    <i class="ti ti-calendar-off fs-5"></i>
                                    <p class="mb-0 mt-2">Silakan pilih tahun akademik dan sekolah</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tutorial -->
    <div class="modal fade" id="modalTutorialJadwal" tabindex="-1" aria-labelledby="tutorialJadwalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="tutorialJadwalLabel"><i class="ti ti-info-circle"></i> Tutorial Penggunaan
                        Menu Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <ol class="lh-lg">
                        <li><strong>Pilih Tahun Akademik</strong> dari daftar tombol yang tersedia di atas.</li>
                        <li><strong>Pilih Sekolah</strong> yang muncul setelah memilih tahun akademik.</li>
                        <li><strong>Lihat Jadwal</strong> untuk sekolah tersebut, akan tampil di bawah.</li>
                        <li>Klik tombol <strong>Tambah Jadwal</strong> untuk menambahkan jadwal baru.</li>
                        <li>Klik ikon <strong>Edit</strong> untuk mengubah jadwal yang sudah ada.</li>
                        <li>Klik ikon <strong>Hapus</strong> untuk menghapus jadwal (akan muncul konfirmasi).</li>
                        <li><strong>Jam selesai</strong> akan otomatis diisi 30 menit setelah jam mulai.</li>
                    </ol>
                    <p class="text-muted mt-3">
                        Pastikan jadwal tidak bentrok dengan kelas dan sekolah lain pada waktu yang sama.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @include('jadwal.partials.modal-create')
    @include('jadwal.partials.modal-edit')

    {{-- Script --}}
    <script>
        let selectedTahunId = null;
        let selectedTahunText = '';

        function selectTahun(tahunId, tahunText) {
            selectedTahunId = tahunId;
            selectedTahunText = tahunText;

            // Reset tampilan sebelumnya
            document.getElementById('jadwal-body').innerHTML = '';
            document.getElementById('sekolah-container').classList.add('d-none');
            document.getElementById('sekolah-buttons').innerHTML = '';

            // Tampilkan loading indicator
            document.getElementById('jadwal-body').innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data sekolah...</p>
                    </td>
                </tr>
            `;

            fetch(`/jadwal/tahun/${tahunId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const sekolahContainer = document.getElementById('sekolah-container');
                    const sekolahButtons = document.getElementById('sekolah-buttons');

                    if (data.length === 0) {
                        document.getElementById('jadwal-body').innerHTML = `
                            <tr>
                                <td colspan="9" class="text-muted py-4">
                                    <i class="ti ti-alert-circle fs-5"></i>
                                    <p class="mb-0 mt-2">Tidak ada sekolah untuk tahun akademik ini</p>
                                </td>
                            </tr>
                        `;
                        sekolahButtons.innerHTML = '<div class="text-muted">Tidak ada sekolah untuk tahun ini.</div>';
                        sekolahContainer.classList.remove('d-none');
                        return;
                    }

                    sekolahButtons.innerHTML = '';
                    data.forEach(group => {
                        const button = document.createElement('button');
                        button.className = 'btn btn-outline-success rounded-pill px-4';
                        button.textContent = group.sekolah;
                        button.onclick = () => showJadwal(group.sekolah);
                        sekolahButtons.appendChild(button);
                    });

                    sekolahContainer.classList.remove('d-none');

                    // Reset tabel ke keadaan awal
                    document.getElementById('jadwal-body').innerHTML = `
                        <tr>
                            <td colspan="9" class="text-muted py-5">
                                <i class="ti ti-calendar-off fs-5"></i>
                                <p class="mb-0 mt-2">Silakan pilih sekolah</p>
                            </td>
                        </tr>
                    `;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('jadwal-body').innerHTML = `
                        <tr>
                            <td colspan="9" class="text-danger py-4">
                                <i class="ti ti-alert-circle fs-5"></i>
                                <p class="mb-0 mt-2">Gagal memuat data sekolah</p>
                                <small>${error.message}</small>
                            </td>
                        </tr>
                    `;
                });
        }

        function showJadwal(namaSekolah) {
            // Tampilkan loading indicator
            document.getElementById('jadwal-body').innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat jadwal...</p>
                    </td>
                </tr>
            `;

            fetch(`/jadwal/tahun/${selectedTahunId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const jadwalGroup = data.find(group => group.sekolah === namaSekolah);
                    const tbody = document.getElementById('jadwal-body');
                    const title = document.getElementById('jadwal-title');
                    const csrfToken = '{{ csrf_token() }}';

                    // Update title tabel
                    title.textContent = `Jadwal ${namaSekolah} - Tahun Akademik ${selectedTahunText}`;

                    // Kosongkan tabel
                    tbody.innerHTML = '';

                    if (!jadwalGroup || jadwalGroup.jadwals.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="9" class="text-muted py-4">
                                    <i class="ti ti-calendar-off fs-5"></i>
                                    <p class="mb-0 mt-2">Belum ada jadwal untuk sekolah ini</p>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    jadwalGroup.jadwals.forEach((j, i) => {
                        tbody.innerHTML += `
                    <tr>
                        <td class="text-center">
                            <button onclick="editJadwal(${j.id_jadwal})" class="btn btn-sm btn-warning rounded-pill mb-1">
                                <i class="ti ti-edit"></i>
                                </button>
                                <form method="POST" action="/jadwal/${j.id_jadwal}" class="d-inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger rounded-pill"
                                    onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                    <i class="ti ti-trash"></i>
                                    </button>
                                    </form>
                                    </td>
                                    <td>
                                        <span class="badge bg-${j.status === 'aktif' ? 'success' : 'danger'}">${j.status}</span>
                                    </td>
                        <td>${j.guru}</td>
                        <td>${j.mapel}</td>
                        <td>${j.kelas}</td>
                        <td>${j.hari}</td>
                        <td>${j.jam_mulai}</td>
                        <td>${j.jam_selesai}</td>
                    </tr>
                `;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-danger py-4">
                                <i class="ti ti-alert-circle fs-5"></i>
                                <p class="mb-0 mt-2">Gagal memuat jadwal</p>
                                <small>${error.message}</small>
                            </td>
                        </tr>
                    `;
                });
        }

        function editJadwal(id) {
            fetch(`/jadwal/${id}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Set form action
                    document.getElementById('formEditJadwal').action = `/jadwal/${id}`;

                    // Set value input lainnya
                    document.getElementById('edit_guru_id').value = data.guru_id;
                    document.getElementById('edit_mata_pelajaran_id').value = data.mata_pelajaran_id;
                    document.getElementById('edit_tahun_akademik_id').value = data.tahun_akademik_id;
                    document.getElementById('edit_sekolah_id').value = data.sekolah_id;
                    document.getElementById('edit_kelas_id').value = data.kelas_id;
                    document.getElementById('edit_hari').value = data.hari;
                    document.getElementById('edit_status').value = data.status;

                    const editJamMulai = document.getElementById('edit_jam_mulai');
                    const editJamSelesai = document.getElementById('edit_jam_selesai');

                    // Set selected value di select <option>
                    [...editJamMulai.options].forEach(option => {
                        option.selected = option.value === data.jam_mulai;
                    });

                    // Hitung dan set jam_selesai otomatis dari jam_mulai
                    if (data.jam_mulai) {
                        const [hour, minute] = data.jam_mulai.split(':').map(Number);
                        const waktu = new Date();
                        waktu.setHours(hour);
                        waktu.setMinutes(minute + 30);

                        const hasilJam = waktu.getHours().toString().padStart(2, '0');
                        const hasilMenit = waktu.getMinutes().toString().padStart(2, '0');
                        editJamSelesai.value = `${hasilJam}:${hasilMenit}`;
                    } else {
                        editJamSelesai.value = '';
                    }

                    // Tambahkan event listener untuk perubahan saat edit
                    editJamMulai.addEventListener('change', function() {
                        const selected = this.value;
                        if (!selected) {
                            editJamSelesai.value = '';
                            return;
                        }

                        const [hour, minute] = selected.split(':').map(Number);
                        const waktu = new Date();
                        waktu.setHours(hour);
                        waktu.setMinutes(minute + 30);

                        const hasilJam = waktu.getHours().toString().padStart(2, '0');
                        const hasilMenit = waktu.getMinutes().toString().padStart(2, '0');
                        editJamSelesai.value = `${hasilJam}:${hasilMenit}`;
                    });

                    // Tampilkan modal edit
                    new bootstrap.Modal(document.getElementById('modalEditJadwal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data jadwal: ' + error.message);
                });
        }

        // Event listener untuk input jam mulai (tambah jadwal baru)
        document.getElementById('jam_mulai')?.addEventListener('change', function() {
            const jamMulai = this.value;
            if (!jamMulai) return;

            const [hour, minute] = jamMulai.split(':').map(Number);
            const mulaiDate = new Date();
            mulaiDate.setHours(hour, minute + 30); // tambahkan 30 menit

            const jamSelesai = mulaiDate.toTimeString().slice(0, 5); // Format HH:mm

            document.getElementById('jam_selesai').value = jamSelesai;
        });
    </script>
@endsection
