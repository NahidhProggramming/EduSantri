<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                        <label for="guru_id" class="form-label">Guru</label>
                        <select name="guru_id" id="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="mata_pelajaran_id" class="form-label">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mapelList as $mapel)
                                <option value="{{ $mapel->id_mapel }}">{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tahun_akademik_id" class="form-label">Tahun Akademik</label>
                        <select name="tahun_akademik_id" id="tahun_akademik_id" class="form-select" required>
                            <option value="">-- Pilih Tahun Akademik --</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun->id_tahun_akademik }}">{{ $tahun->tahun_akademik }} - Semester
                                    {{ $tahun->semester }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sekolah_id" class="form-label">Sekolah</label>
                        <select name="sekolah_id" id="sekolah_id" class="form-select" required>
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($sekolahList as $sekolah)
                                <option value="{{ $sekolah->id_sekolah }}">{{ $sekolah->nama_sekolah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <select name="jam_mulai" id="jam_mulai" class="form-select" required>
                            <option value="">-- Pilih Jam Mulai --</option>
                            @php
                                $allowedTimes = [
                                    '09:20',
                                    '09:50',
                                    '10:20',
                                    '10:50',
                                    '12:00',
                                    '12:30',
                                    '13:00',
                                    '13:30',
                                ];
                            @endphp
                            @foreach ($allowedTimes as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-md-6 mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="text" name="jam_selesai" id="jam_selesai" class="form-control" readonly
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
