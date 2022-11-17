<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class JenisAsset extends Model
{
    use HasFactory;
    use Uuid;

    public $table = "jenisassets";
    protected $fillable = ['uuid', 'nama'];
}
