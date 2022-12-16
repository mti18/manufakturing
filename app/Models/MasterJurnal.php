<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJurnal extends Model
{
    use HasFactory;
    
    use Uuid;
    protected $fillable = ["uuid", "kd_jurnal", "tanggal", "type", ];
    protected $hidden = [ 'created_at', 'updated_at'];
    protected $appends = ['file_bukti_master'];

    
    public function jurnal_item()
    {
        return $this->hasMany(JurnalItem::class, "masterjurnal_id", "id");
    }
    public function BuktiMaster()
    {
        return $this->hasMany(BuktiMaster::class, "masterjurnal_id", "id");
    }

    public function getFileBuktiMasterAttribute() {
        $files = [];
        foreach ($this->BuktiMaster as $bukti) {
            $files[] = asset($bukti->file);
        }

        return $files;
    }


}
