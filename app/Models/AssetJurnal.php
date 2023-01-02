<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetJurnal extends Model
{
    use HasFactory;
    protected $with = ['Golongan', 'AssetGroup', 'PenyusutanFirst'];

    use Uuid;
    protected $fillable = [
        'uuid', 'nm_asset', 'number', 'price', 'golongan_id', 'asset_group_id', 'kredit_id', 'penyusutan_id', 'beban_id', 'akun_id','masterjurnal_id','type'
    ];

    public function Golongan()
    {
        return $this->belongsTo('\App\Models\Golongan', 'golongan_id');
    }

    public function AssetGroup()
    {
        return $this->belongsTo('\App\Models\AssetGroup', 'asset_group_id');
    }

    public function penyusutanaccount()
    {
        return $this->belongsTo('\App\Models\Account', 'penyusutan_id');
    }
    public function Beban()
    {
        return $this->belongsTo('\App\Models\Account', 'beban_id');
    }
    public function MasterJurnal()
    {
        return $this->belongsTo('\App\Models\MasterJurnal', 'masterjurnal_id');
    }
    public function Account()
    {
        return $this->belongsTo('\App\Models\Account', 'akun_id');
    }

    public function Penyusutan()
    {
        return $this->hasMany('\App\Models\Penyusutan', 'asset_id');
    }
    
    public function PenyusutanFirst()
    {
        return $this->hasOne('\App\Models\Penyusutan', 'asset_id')->orderBy('tanggal', 'desc');
    }
    
    public function PenyusutanAll()
    {
        return $this->hasMany('\App\Models\Penyusutan', 'asset_id');
    }
}
