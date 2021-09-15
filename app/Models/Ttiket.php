<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ttiket extends Model
{
    use HasFactory;
    protected $table = "t_tiket";

    protected $guarded = [];
}
