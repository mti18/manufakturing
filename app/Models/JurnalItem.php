<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalItem extends Model
{
    use HasFactory;
    use Uuid;
    protected $fillable = ["acount_id", "jurnal_id", "debit", "kredit", "uuid",  "keterangan"];

    public function acount()
    {
        return $this->belongsTo('\App\Models\Acount', 'acount_id');
    }
    public function jurnal()
    {
        return $this->belongsTo('\App\Models\MasterJurnal', 'jurnal_id');
    }
}
