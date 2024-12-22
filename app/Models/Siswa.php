<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $primaryKey = 'nisn';
    protected $casts = [
        'nisn' => 'string',
    ];

    protected $fillable = [
        'nisn',
        'nama',
        'kelas_10',
        'kelas_11',
        'kelas_12',
        'peminatan',
        'sikap',
        'angkatan',
    ];

    public function rapor()
    {
        return $this->hasMany(Rapor::class, 'nisn', 'nisn');
    }

    public function prestasis() {
        return $this->belongsToMany(Prestasi::class, 'prestasi_siswa', 'nisn', 'prestasi_id');
    }

    public function spk_kriteria()
    {
        return $this->hasOne(SpkKriteria::class, 'nisn', 'nisn');
    }

    public function spk_normalisasi()
    {
        return $this->hasOne(SpkNormalisasi::class, 'nisn', 'nisn');
    }

    public function spk_preferensi()
    {
        return $this->hasOne(SpkPreferensi::class, 'nisn', 'nisn');
    } 
    
    public function surat()
    {
        return $this->hasMany(Surat::class, 'nisn', 'nisn');
    }

    // Define relationships or other model-specific methods here.
}
