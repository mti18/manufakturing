<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class Pembayaran extends Model
{
    use HasFactory;
    use Uuid;

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function salesorder()
    {
        return $this->belongsTo(SalesOrder::class, 'salesorder_id', 'id');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'id');
    }

}
