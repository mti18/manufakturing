<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class SalesOrder extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'profile_id', 'supplier_id', 'diketahui_oleh', 'jumlah_paket', 'bukti_pesan', 
        'jenis_pembayaran', 'no_pemesanan','account_id', 'pembayaran', 'tgl_pesan', 'tgl_pengiriman', 'tempo', 'status', 
        'keterangan', 'total', 'diskon', 'uangmuka', 'pph', 'ppn', 'netto', 'user_id', 'acc_pimpinan'
    ];
    protected $with = ['barangjadi', 'barangmentah'];

    public function diketahuioleh()
    {
        return $this->belongsTo(User::class, 'diketahui_oleh', 'id');
    }
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function detail()
    {
        return $this->hasMany(SalesOrderDetail::class, 'salesorder_id',  'id');
    }

    public function barangjadi() 
    {
        // return $this->belongsToMany(BarangJadi::class,'sales_order_details', 'salesorder_id', 'barangjadi_id');
        return $this->detail()->where('barangjadi_id', '!=', null);
    }
    public function barangmentah() 
    {
        // return $this->belongsToMany(BarangMentah::class,'sales_order_details', 'salesorder_id', 'barangmentah_id');
        return $this->detail()->where('barangmentah_id', '!=', null);
    }
}
