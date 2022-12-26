<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class PermintaanBarang extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'tanggal', 'tanggal_permintaan', 'no_bukti_permintaan', 'status', 'tipe', 
        'volume', 'harga', 'jumlah', 'keterangan', 'tipe_barang', 'barangjadi_id', 'barangmentah_id'
    ];

    public function barang_jadi()
    {
        return $this->belongsTo(BarangJadi::class, 'barangjadi_id', 'id');
    }

    public function barang_mentah()
    {
        return $this->belongsTo(BarangMentah::class, 'barangmentah_id', 'id');
    }

}
