<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKriteria extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'nisn',
        'rata_rapor',
        'prestasi',
        'sikap',
        // Add other fields as needed.
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    // Define other relationships or model-specific methods here.
}
