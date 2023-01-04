<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class Pembayaran extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'salesorder_id', 'pembelian_id', 'account_id', 'accbiaya_id', 'tanggal', 
        'jenis_pembayaran', 'bayar', 'keterangan'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
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
