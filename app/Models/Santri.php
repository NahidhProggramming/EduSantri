<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santri';
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nis',
        'nisn',
        'nama_santri',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'foto',
        'ayah',
        'ibu',
        'no_hp',
        'wali_id'
    ];

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class, 'santri_nis', 'nis');
    }
    public function detail()
    {
        return $this->hasMany(Detail::class, 'santri_id', 'nis');
    }

    public function kelasAktif()
    {
        return $this->hasOneThrough(
            \App\Models\Kelas::class,
            \App\Models\Detail::class,
            'santri_id', // Foreign key on Detail
            'id_kelas',  // Local key on Kelas
            'nis',       // Local key on Santri
            'kelas_id'   // Foreign key on Detail
        );
    }
    public function nilai()
    {
        // Santri (nis) -> Detail (santri_id) -> Nilai (detail_id)
        return $this->hasManyThrough(
            Nilai::class,   // model tujuan
            Detail::class,  // model perantara
            'santri_id',    // FK di table detail yang mengarah ke santri (local key)
            'detail_id',    // FK di table nilai   yang mengarah ke detail (foreign key on Nilai)
            'nis',          // PK/Key pada Santri
            'id_detail'     // PK pada Detail
        );
    }
    public function sekolahAktif()
    {
        // Santri (nis) ➜ Detail (santri_id) ➜ Sekolah (sekolah_id)
        return $this->hasOneThrough(
            Sekolah::class,   // model tujuan
            Detail::class,    // model perantara
            'santri_id',      // FK di detail → santri
            'id_sekolah',     // PK di sekolah
            'nis',            // PK santri
            'sekolah_id'      // FK di detail → sekolah
        );
    }
}
