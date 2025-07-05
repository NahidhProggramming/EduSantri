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
    ];

    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class, 'santri_nis', 'nis');
    }

}
