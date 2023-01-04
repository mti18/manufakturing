<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HutangPiutang extends Model
{
    use HasFactory;
    use  Uuid;

    protected $fillable = [
    	'uuid', 'profile_id', 'account_id', 'tanggal', 'bukti', 'jumlah', 'type', 'keterangan', 'tempo', 'salesorder_id', 'pembelian_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
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
