<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['tipe_barang', 'barangjadi_id', 'barangmentah_id', 'kualitas', 'tanggal_keluar', 'barang_keluar', 'stok_terakhir', 'keterangan'];

    public function barang_jadi()
    {
        return $this->belongsTo(BarangJadi::class, 'barangjadi_id', 'id');
    }

    public function barang_mentah()
    {
        return $this->belongsTo(BarangMentah::class, 'barangmentah_id', 'id');
    }
}
