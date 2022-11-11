<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangSatuan extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_satuan' , 'satuan'];
    protected $with = ['child'];

    public function child()
    {
        return $this->hasMany(SatuanChild::class, 'barangsatuan_id', 'id');
    }

    public function barangmentah()
    {
        return $this->hasMany(BarangMentah::class, 'barangsatuan_id', 'id');
    }    


}
