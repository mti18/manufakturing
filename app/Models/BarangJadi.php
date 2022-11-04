<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangJadi extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_barang_jadi', 'stok'];
}
