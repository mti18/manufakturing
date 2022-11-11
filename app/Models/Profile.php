<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Profile extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nama', 'telepon', 'npwp', 'fax', 'pimpinan', 'alamat',
     'provinsi_id', 'kab_kota_id', 'kecamatan_id', 'kelurahan_id'];
    protected $hidden = ['id', 'created_at', 'updated_at'];



}
