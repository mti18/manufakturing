<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyusutan extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'uuid', 'asset_id', 'masterjurnal_id', 'tanggal'
    ];

    public function Asset()
    {
        return $this->belongsTo('\App\Models\AssetJurnal', 'asset_id');
    }

    public function MasterJurnal()
    {
        return $this->belongsTo('\App\Models\MasterJurnal', 'masterjurnal_id');
    }
}
