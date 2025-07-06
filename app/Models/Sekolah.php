<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;
    protected $table = 'sekolah';
    protected $primaryKey = 'id_sekolah';
    public $timestamps = false;
    protected $fillable = ['nama_sekolah'];

    public function nilai()
{
    return $this->hasManyThrough(Nilai::class, Detail::class, 'sekolah_id', 'detail_id', 'id_sekolah', 'id_detail');
}
}
