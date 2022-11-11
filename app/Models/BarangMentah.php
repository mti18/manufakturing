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

    protected $fillable = ['uuid', 'nm_barangmentah', 'stok', 'barangsatuan_id', 'gudang_id', 'kd_barang_mentah', 'harga'];
    protected $with = ['barangmentahkategoris'];

    
    public function barangsatuan()
    {
        return $this->belongsTo(BarangSatuan::class, 'barangsatuan_id', 'id');
    }

    public function barangmentahkategoris()
    {
        return $this->belongsToMany(Kategori::class, 'barang_mentah_kategori', 'barang_mentah_id', 'kategori_id');
    }

    public function barangmentahgudangs()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id', 'id');
    }
}

