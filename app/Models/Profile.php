<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Profile extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'logo', 'kop', 'ttd', 'nama', 'telepon', 'npwp', 'fax', 'pimpinan', 'alamat',
     'provinsi_id', 'kab_kota_id', 'kecamatan_id', 'kelurahan_id'];
    protected $hidden = [ 'created_at', 'updated_at'];

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'profile_id');
    }

    public function assetdetail()
    {
        return $this->hasMany('\App\Models\Asset', 'profile_id');
    }

    public function jenisasset()
    {
        return $this->hasMany(JenisAsset::class, 'id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kab_kota_id');
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function salesorder()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public static function booted() {
        parent::boot();

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->logo)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->logo)));
            }
        });

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->kop)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->kop)));
            }
        });

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->ttd)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->ttd)));
            }
        });
    }


}
