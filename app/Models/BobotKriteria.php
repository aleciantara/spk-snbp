<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotKriteria extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kriteria',
        'bobot',
        // Add other fields as needed.
    ];

    // Define any relationships or model-specific methods here.
}
