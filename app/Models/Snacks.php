<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snacks extends Model
{
    use HasFactory;
    protected $table = "m_snacks";
    
    protected $guarded = [];

    protected $primaryKey = "id_snacks";

}
