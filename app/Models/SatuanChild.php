<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanChild extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_satuan_children', 'barangsatuan_id', 'parent_id', 'nilai'];

    public function children()
    {
        return $this->hasOne(SatuanChild::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(SatuanChild::class, 'parent_id', 'id');
    }

    public function barangsatuan()
    {
        return $this->belongsTo(BarangSatuan::class, 'barangsatuan_id', 'id');
    }
}
