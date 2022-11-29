<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokProduksi extends Model
{
    use HasFactory;
    
    protected $fillable = ['stok_jadi', 'barangjadi_id', 'barang_produksi_id', 'barang_mentah_id', 'stok_digunakan'];

    public function barangproduksibarangmentahs()
    {
        return $this->belongsTo(BarangProduksiBarangMentah::class);
    }

    public function barangproduksi()
    {
        return $this->hasMany(BarangProduksi::class, 'barangjadi_id', 'id');
    }

}
