<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturBarang extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'jumlah', 'tanggal', 'user_id', 'salesorder_detail_id', 'status', 'keterangan', 'acc_pimpinan'];

    public function salesorderdetail()
    {
        return $this->belongsTo(SalesOrderDetail::class, 'salesorder_detail_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    
}
