<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiMaster extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'uuid', 'masterjurnal_id', 'file',
    ];

    public static function booted() {
        parent::boot();

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->file)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->file)));
            }
        });
    }
     public function masterjurnal()
    {
        return $this->belongsTo(MasterJurnal::class);
    }
    
}
