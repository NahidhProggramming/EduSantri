<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;
    protected $fillable = ['nip', 'nama_guru', 'jenkel', 'tgl_lahir', 'alamat', 'no_hp', 'user_id'];


    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'guru_id', 'id_guru');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id', 'id_guru');
    }
}
