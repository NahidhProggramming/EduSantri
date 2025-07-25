<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'detail_id',
        'jadwal_id',
        'tahun_akademik_id',
        'nilai_sumatif',
        'nilai_pas',
        'nilai_pat',
    ];

    public function detail()
    {
        return $this->belongsTo(Detail::class, 'detail_id', 'id_detail');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id', 'id_tahun_akademik');
    }
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'id_jadwal');
    }
    public function mataPelajaran()
    {
        return $this->hasOneThrough(
             MataPelajaran::class,   // Gunakan class yang sudah di-import
            Jadwal::class,
            'id_jadwal',                 // PK Jadwal
            'id_mapel',                  // PK Mapel
            'jadwal_id',                // FK di Nilai → Jadwal
            'mata_pelajaran_id'         // FK di Jadwal → Mapel ✅
        );
    }
}
