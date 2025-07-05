<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'tahun_akademik';
    public $timestamps = false;
    protected $primaryKey = 'id_tahun_akademik';

    protected $fillable = ['tahun_akademik', 'semester', 'semester_aktif', 'tempat', 'tanggal'];
}
