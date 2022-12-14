<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class SalesOrderDetail extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'sales_order_detail';

    protected $fillable = ['uuid', 'barangmentah_id', 'barangjadi_id', 'volume', 'nm_satuan', 'harga', 'diskon', 
        'jumlah', 'keterangan', 'salesorder_id'
    ];



}
