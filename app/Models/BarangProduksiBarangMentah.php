<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangProduksiBarangMentah extends Model
{
    use HasFactory;

    protected $table = 'barang_produksi_barang_mentah';

    protected $fillable = ['barang_produksi_id', 'barang_mentah_id', 'stok_digunakan', 'satuan_id'];

}
