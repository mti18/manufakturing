<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Asset extends Model
{
    use HasFactory;
    use Uuid;
    protected $table = "asset";

    protected $fillable = [
        'uuid', 'nm_asset', 'number', 'price', 'golongan_id', 'asset_group_id'
    ];

    public function Golongan()
    {
        return $this->belongsTo('\App\Models\Golongan', 'golongan_id');
    }

    public function AssetGroup()
    {
        return $this->belongsTo('\App\Models\AssetGroup', 'asset_group_id');
    }
}
