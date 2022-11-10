<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangSatuanJadi extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_satuan_jadi'];

    public function barangjadi()
    {
        return $this->hasMany(BarangJadi::class, 'barangsatuanjadi_id', 'id');
    }    
}
