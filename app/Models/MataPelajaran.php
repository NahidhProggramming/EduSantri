<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mapel';

    protected $fillable = ['nama_mapel'];

    public function pengampuMapel()
    {
        return $this->hasMany(MataPelajaran::class, 'mata_pelajaran_id');
    }
}
