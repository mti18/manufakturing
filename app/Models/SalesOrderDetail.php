<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'sales_order_detail';

    protected $fillable = ['barangmentah_id', 'barangjadi_id', 'volume', 'harga', 'diskon', 
        'jumlah', 'keterangan', 'salesorder_id'
    ];



}
