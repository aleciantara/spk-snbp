<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKPreferensi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'nisn',
        'spk_rata_rapor',
        'spk_prestasi',
        'spk_sikap',
        'spk_total',
        // Add other fields as needed.
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    // Define other relationships or model-specific methods here.
}
