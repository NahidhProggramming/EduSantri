<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TingkatPelanggaran extends Model
{
    use HasFactory;

    protected $table = 'tingkat_pelanggaran';
    protected $primaryKey = 'id_tingkat';
    protected $fillable = ['nama_tingkat', 'poin'];

    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class, 'tingkat_pelanggaran_id');
    }
}
