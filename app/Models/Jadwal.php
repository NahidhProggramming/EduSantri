<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'tahun_akademik_id',
        'kelas_id',
        'sekolah_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id', 'id_mapel');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id', 'id_tahun_akademik');
    }
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'id_sekolah');
    }
}
