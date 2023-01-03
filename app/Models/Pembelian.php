<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Pembelian extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'salesorder_id', 'profile_id', 'supplier_id', 'no_surat', 'tgl_permintaan', 'bukti_permintaan', 
        'no_surat_pembelian', 'diketahui_oleh', 'tgl_po', 'jenis_pembayaran', 'no_po_pembelian', 'account_id', 'no_surat_jalan',
        'tempo', 'keterangan', 'jml_penjualan', 'diskon', 'uangmuka', 'pajak', 'ppn', 'netto', 'nomor', 'tipe', 'acc_pimpinan',
        'permintaan_id'
    ];
    protected $hidden = ['updated_at'];

    protected $with = ['details', 'barangjadi', 'barangmentah'];

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

    public function barangjadi() 
    {
        return $this->hasMany(PermintaanBarang::class, 'pembelian_id', 'id')->where('barangjadi_id', '<>', null);
    }
    public function barangmentah() 
    {
        return $this->hasMany(PermintaanBarang::class, 'pembelian_id', 'id')->where('barangmentah_id', '<>', null);
    }

    public function details() {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id', 'id');
    }

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
        return $this->belongsTo(BarangMentah::class, 'barangmentah_id', 'id');
    }
}
