<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkPreferensi extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'id';

    protected $fillable = [
        'nisn',
        'rapor',
        'prestasi',
        'sikap',
        'total'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
