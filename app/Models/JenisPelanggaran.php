<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPelanggaran extends Model
{
    use HasFactory;

    protected $table = 'jenis_pelanggaran';
    protected $primaryKey = 'id_jenis';
    public $timestamps = false;
    protected $fillable = ['nama_jenis', 'tingkatan', 'poin'];

    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class, 'jenis_pelanggaran_id');
    }
}
