<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanJadiChild extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_satuan_jadi_children', 'barangsatuanjadi_id', 'nilai'];


    public function barangsatuanjadi()
    {
        return $this->belongsTo(BarangSatuanJadi::class, 'barangsatuanjadi_id', 'id');
    }

    public function satuanproduksis()
    {
        return $this->hasMany(BarangProduksi::class, 'satuanjadichildren_id', 'id');
    }

}
