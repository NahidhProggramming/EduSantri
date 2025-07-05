<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';
    protected $primaryKey = 'id_pelanggaran';
    public $timestamps = false;
    protected $fillable = [
        'santri_nis',
        'jenis_pelanggaran_id',
        'tingkat_pelanggaran_id',
        'deskripsi',
        'tanggal',
        'file_surat',
        'verifikasi_surat',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_nis', 'nis');
    }

    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran_id');
    }

    public function tingkatPelanggaran()
    {
        return $this->belongsTo(TingkatPelanggaran::class, 'tingkat_pelanggaran_id');
    }
}
