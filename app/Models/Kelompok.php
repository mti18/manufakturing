<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Kelompok extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nama', 'masa', 'tarif'];
}
