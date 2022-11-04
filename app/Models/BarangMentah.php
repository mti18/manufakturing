<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMentah extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_barangmentah', 'stok', 'barangsatuan_id' ];

    protected $with = ['kategoris'];

    
    public function barangsatuan()
    {
        return $this->belongsTo(BarangSatuan::class, 'barangsatuan_id', 'id');
    }

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'barang_kategori', 'barang_mentah_id', 'kategori_id');
    }
}

