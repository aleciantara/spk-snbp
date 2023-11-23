<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'prestasi', 
        'juara', 
        'tingkat', 
        'penyelenggara', 
        'waktu', 
        'poin', 
        'status', 
        'file',
    ];

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'prestasi_siswa', 'prestasi_id', 'nisn');
    }
    

    public function prestasiSiswa()
    {
        return $this->hasMany(PrestasiSiswa::class,  'prestasi_id', 'id');
    }

    // Define other relationships or model-specific methods here.
}
