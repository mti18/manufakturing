<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokProduksi extends Model
{
    use HasFactory;
    
    protected $fillable = ['barang_produksi_id'];


    public function barangproduksi()
    {
        return $this->belongsTo(BarangProduksi::class, 'barang_produksi_id', 'id');
    }

}
