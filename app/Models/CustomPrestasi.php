<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPrestasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'tingkat',
        'juara',
        'poin',
        // Add other fields as needed.
    ];

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'custom_prestasi_id', 'id');
    }

    // Define other relationships or model-specific methods here.
}
