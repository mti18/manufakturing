<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalItem extends Model
{
    use HasFactory;
    use Uuid;
    protected $fillable = ["account_id", "masterjurnal_id", "debit", "kredit", "uuid",  "keterangan"];

    public function account()
    {
        return $this->belongsTo('\App\Models\Account', 'account_id');
    }
    public function masterjurnal()
    {
        return $this->belongsTo('\App\Models\MasterJurnal', 'masterjurnal_id');
    }
}
