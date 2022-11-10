<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Kecamatan extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_kecamatan', 'kab_kota_id'];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kab_kota_id');
    }
}
