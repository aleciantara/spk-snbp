<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiSiswa extends Model
{
    use HasFactory;

    protected $table = 'prestasi_siswa';

    protected $casts = [
        'nisn' => 'string',
    ];

    protected $fillable = [
        "id",
        "prestasi_id",
        "nisn",
    ];

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'prestasi_siswa', 'prestasi_id', 'nisn');
    }
}
