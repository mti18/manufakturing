<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangJadiKategori extends Model
{
    use HasFactory;

    protected $table = 'barang_jadi_kategori';

    protected $fillable = ['barang_jadi_id', 'kategori_id'];
}
