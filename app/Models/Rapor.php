<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    use HasFactory;

    protected $primaryKey = 'nisn';

    protected $fillable = [
        'nisn',
        'pelajaran',
        'jenis',
        'sem_1_nilai_p',
        'sem_1_nilai_k',
        'sem_2_nilai_p',
        'sem_2_nilai_k',
        'sem_3_nilai_p',
        'sem_3_nilai_k',
        'sem_4_nilai_p',
        'sem_4_nilai_k',
        'sem_5_nilai_p',
        'sem_5_nilai_k',
        'rata_nilai_p',
        'rata_nilai_k',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    // Define other relationships or model-specific methods here.
}
