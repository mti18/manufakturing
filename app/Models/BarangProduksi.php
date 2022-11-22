<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangProduksi extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'stok_jadi', 'barangjadi_id'];
    protected $with = ['barangproduksibarangjadi'];


    
    public function barang_mentah()
    {
        return $this->belongsToMany(BarangMentah::class, 'barang_produksi_barang_mentah', 'barang_produksi_id', 'barang_mentah_id', );
    }

    public function barangproduksibarangmentahs()
    {
        return $this->hasMany(BarangProduksiBarangMentah::class, 'barang_produksi_id', 'id');
    }
    
    public function barangproduksibarangjadi()
    {
        return $this->belongsTo(BarangJadi::class, 'barangjadi_id', 'id');
    }
    
}
