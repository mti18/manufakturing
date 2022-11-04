<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKategori extends Model
{
    use HasFactory;

    protected $table = 'barang_kategori';
    
    protected $fillable = ['barang_mentah_id', 'kategori_id'];
}
