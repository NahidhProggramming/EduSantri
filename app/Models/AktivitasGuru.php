<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasGuru extends Model
{
    protected $table = 'aktivitas_guru';

    protected $fillable = [
        'guru_id',
        'deskripsi',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
