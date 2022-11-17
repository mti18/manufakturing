<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_kategori'];

    public function barangmentahkategoris()
    {
        return $this->belongsToMany(BarangMentah::class,'barang_mentah_kategori', 'kategori_id');
    }

    public function barangjadikategoris()
    {
        return $this->belongsToMany(BarangJadi::class,'barang_jadi_kategori', 'kategori_id');
    }

}
