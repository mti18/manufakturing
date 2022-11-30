<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJurnal extends Model
{
    use HasFactory;
    
    use Uuid;
    protected $fillable = ["uuid", "kd_jurnal", "tanggal", "type", "upload"];
    protected $hidden = [ 'created_at', 'updated_at'];

    
    public function jurnal_item()
    {
        return $this->hasMany(JurnalItem::class, "masterjurnal_id", "id");
    }

    public static function booted() {
        parent::boot();

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->upload)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->upload)));
            }
        });
    }

}
