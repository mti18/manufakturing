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

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class,'barang_kategori');
    }
}
