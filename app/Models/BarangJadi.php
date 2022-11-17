<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangJadi extends Model
{
    use HasFactory;
    use Uuid;
    

    protected $fillable = ['uuid', 'nm_barang_jadi', 'stok', 'barangsatuanjadi_id', 'gudang_id'];
    protected $with = ['barangjadikategoris'];

    public function barangsatuanjadi()
    {
        return $this->belongsTo(BarangSatuanJadi::class, 'barangsatuanjadi_id', 'id');
    }

    public function barangjadikategoris()
    {
        return $this->belongsToMany(Kategori::class, 'barang_jadi_kategori', 'barang_jadi_id', 'kategori_id');
    }

    public function barangjadigudangs()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id', 'id');
    }
}
