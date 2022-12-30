<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class PembelianDetail extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'salesorder_id', 'pembelian_id', 'permintaan_id', 'jumlah', 'harga'];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_id', 'id');
    }

    public function barang_jadi()
    {
        return $this->belongsTo(BarangJadi::class, 'barangjadi_id', 'id');
    }

    public function barang_mentah()
    {
        return $this->belongsTo(barangmentah::class, 'barangmentah_id', 'id');
    }

}

