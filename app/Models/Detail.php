<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Detail extends Model
{
    use HasFactory;

    protected $table = 'detail';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'kelas_id',
        'tahun_akademik_id',
        'santri_id',
        'sekolah_id'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'id_sekolah');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id', 'id_tahun_akademik');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id', 'nis');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'detail_id', 'id_detail');
    }



    public static function getPembagianKelas($tahunId = null)
    {
        $query = self::join('kelas', 'detail.kelas_id', '=', 'kelas.id_kelas')
            ->join('sekolah', 'detail.sekolah_id', '=', 'sekolah.id_sekolah')
            ->join('tahun_akademik', 'detail.tahun_akademik_id', '=', 'tahun_akademik.id_tahun_akademik')
            ->select(
                DB::raw('MIN(detail.id_detail) as id_detail'),
                'kelas.nama_kelas',
                'sekolah.nama_sekolah',
                'tahun_akademik.tahun_akademik',
                DB::raw('COUNT(detail.santri_id) as jumlah_siswa')
            )
            ->groupBy('kelas.nama_kelas', 'sekolah.nama_sekolah', 'tahun_akademik.tahun_akademik');

        // Filter berdasarkan tahun yang dipilih
        if ($tahunId) {
            $query->where('detail.tahun_akademik_id', $tahunId);
        }

        return $query->get();
    }
}
