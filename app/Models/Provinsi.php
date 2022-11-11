<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Provinsi extends Model
{
    use Uuid;

    protected $fillable = ['uuid', 'nm_provinsi'];
}
