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

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'id_jadwal');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id', 'id_tahun_akademik');
    }
}
