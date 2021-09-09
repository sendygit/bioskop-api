<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $table = "m_film";
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $primaryKey = "id_film";
}
