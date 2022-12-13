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
        'jenis_pembayaran', 'account_id', 'pembayaran', 'tgl_pesan', 'tgl_pengiriman', 'tempo', 'status', 
        'keterangan', 'total', 'diskon', 'uangmuka', 'pph', 'ppn', 'netto'
    ];
    protected $with = ['details'];

    public function diketahui_oleh()
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

    public function details() 
    {
        return $this->belongsToMany(SalesOrderDetail::class,'sales_order_detail', 'salesorder_id', 'barangjadi_id', 'barangmentah_id');
    }

    public function barangjadikategoris()
    {
        return $this->belongsToMany(Kategori::class, 'sales_order_detail', 'barang_jadi_id', 'kategori_id');
    }
}
