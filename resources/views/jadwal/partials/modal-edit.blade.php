<!-- Modal Edit Jadwal -->
<div class="modal fade" id="modalEditJadwal" tabindex="-1" aria-labelledby="modalEditJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEditJadwal" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditJadwalLabel">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body row">

                    <input type="hidden" id="edit_jadwal_id">

                    <div class="mb-3 col-md-6">
                        <label for="edit_guru_id" class="form-label">Guru</label>
                        <select name="guru_id" id="edit_guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="edit_mata_pelajaran_id" class="form-label">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="edit_mata_pelajaran_id" class="form-select" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mapelList as $mapel)
                                <option value="{{ $mapel->id_mapel }}">{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="edit_tahun_akademik_id" class="form-label">Tahun Akademik</label>
                        <select name="tahun_akademik_id" id="edit_tahun_akademik_id" class="form-select" required>
                            <option value="">-- Pilih Tahun Akademik --</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun->id_tahun_akademik }}">{{ $tahun->tahun_akademik }} - Semester
                                    {{ $tahun->semester }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="edit_sekolah_id" class="form-label">Sekolah</label>
                        <select name="sekolah_id" id="edit_sekolah_id" class="form-select" required>
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($sekolahList as $sekolah)
                                <option value="{{ $sekolah->id_sekolah }}">{{ $sekolah->nama_sekolah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="edit_kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="edit_kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="edit_hari" class="form-label">Hari</label>
                        <select name="hari" id="edit_hari" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $allowedTimes = ['09:20', '09:50', '10:20', '10:50', '12:00', '12:30', '13:00', '13:30'];
                    @endphp

                    <div class="col-md-6 mb-3">
                        <label for="edit_jam_mulai" class="form-label">Jam Mulai</label>
                        <select name="jam_mulai" id="edit_jam_mulai" class="form-select" required>
                            <option value="">-- Pilih Jam Mulai --</option>
                            @foreach ($allowedTimes as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="edit_jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="text" name="jam_selesai" id="edit_jam_selesai" class="form-control" readonly
                            required>
                    </div>



                    <div class="mb-3 col-md-6">
                        <label for="edit_status" class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
