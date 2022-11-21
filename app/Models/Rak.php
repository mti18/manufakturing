<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_rak', 'gudang_id'];

    public function rak()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id', 'id');
    }

    public function rakbarangjadi()
    {
        return $this->hasMany(BarangJadi::class, 'rak_id', 'id');
    }

    public function rakbarangmentah()
    {
        return $this->hasMany(BarangMentah::class, 'rak_id', 'id');
    }

}
