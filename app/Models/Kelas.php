<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;
    protected $fillable = ['nama_kelas', 'tingkat'];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'id_sekolah');
    }
}
