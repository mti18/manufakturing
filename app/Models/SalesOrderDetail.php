<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class SalesOrderDetail extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'barang_id', 'volume', 'nm_satuan', 'harga', 'diskon', 
        'jumlah', 'keterangan'
    ];
}
