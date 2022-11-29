<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMentah extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_barangmentah', 'stok', 'barangsatuan_id', 'gudang_id', 'kd_barang_mentah', 'harga', 'foto', 'rak_id'];
    protected $with = ['barangmentahkategoris'];

    
    public function barangsatuan()
    {
        return $this->belongsTo(BarangSatuan::class, 'barangsatuan_id', 'id');
    }

    public function barangmentahkategoris()
    {
        return $this->belongsToMany(Kategori::class, 'barang_mentah_kategori', 'barang_mentah_id', 'kategori_id');
    }

    public function barangmentahgudangs()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id', 'id');
    }

    public function barangproduksibarangmentahs()
    {
        return $this->belongsToMany(BarangProduksi::class, 'barang_produksi_barang_mentah', 'barang_mentah_id');
    }

    public function rakbarangmentah()
    {
        return $this->belongsTo(Rak::class, 'rak_id', 'id');
    }

    public function stok_masuk_mentahs()
    {
        return $this->hasMany(StokMasuk::class, 'barangmentah_id', 'id');
    }

    public function stok_keluar_mentahs()
    {
        return $this->hasMany(StokKeluar::class, 'barangmentah_id', 'id');
    }

    
    public static function booted() {
        parent::boot();

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->foto)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->foto)));
            }
        });
    }
}

