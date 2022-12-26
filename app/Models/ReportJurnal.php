<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportJurnal extends Model
{
    use HasFactory;
    protected $fillable = ['bayar', 'kurang_bayar', "total_pajak", 'tahun', 'masterjurnal_id', "account_id"];

    public function masterjurnal()
    {
        return $this->belongsTo('\App\Models\MasterJurnal', 'masterjurnal_id');
    }
}
