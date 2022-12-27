<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;

    // protected $table = 'sales_order_details';
    protected $with = ['barangjadi', 'barangmentah'];

    protected $fillable = ['barangmentah_id', 'barangjadi_id', 'volume', 'harga', 'diskon', 
        'jumlah', 'keterangan', 'salesorder_id'
    ];

    public function barangjadi()
    {
        return $this->belongsTo(BarangJadi::class, 'barangjadi_id', 'id');
    }

    public function barangmentah()
    {
        return $this->belongsTo(barangmentah::class, 'barangmentah_id', 'id');
    }

    public function salesorder()
    {
        return $this->belongsTo(SalesOrder::class, 'salesorder_id', 'id');
    }





}
