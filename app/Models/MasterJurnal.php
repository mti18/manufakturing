<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJurnal extends Model
{
    use HasFactory;
    
    use Uuid;
    protected $fillable = ["uuid", "kd_jurnal", "tanggal", "type", "status"];
}
