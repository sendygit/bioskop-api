<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingGenre extends Model
{
    use HasFactory;
    protected $table = "mapping_genre_film";

    protected $guarded = [];

    protected $primaryKey = "id_mapping_genre";
}
