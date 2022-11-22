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

    public function diketahui_oleh()
    {
        return $this->belongsTo(User::class, 'diketahui_oleh', 'id');
    }
    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
