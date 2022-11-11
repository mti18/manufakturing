<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMentahKategori extends Model
{
    use HasFactory;

    protected $table = 'barang_mentah_kategori';
    
    protected $fillable = ['barang_mentah_id', 'kategori_id'];

    
}
