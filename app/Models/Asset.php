<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Asset extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_assets', 'tahun', 'kelompok_id', 'jumlah', 'profile_id', 'jenisasset_id'];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }

    public function jenisasset()
    {
        return $this->belongsTo(JenisAsset::class, 'jenisasset_id', 'id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }
}
