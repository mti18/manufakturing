<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class PembelianDetail extends Model
{
    use HasFactory;
    use Uuid;

    protected $filllable = ['uuid', 'salesorder_id', 'pembelian_id', 'permintaan_id', 'jumlah', 'harga'];
}

