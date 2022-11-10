<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Supplier extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nama', 'telepon', 'kode', 'npwp', 'nppkp', 'nama_kontak', 'telp_kontak', 'alamat', 'tipe',
     'provinsi_id', 'kab_kota_id', 'kecamatan_id'];
    protected $hidden = ['id', 'createded_at', 'updated_at'];



    // public function getTextAttribute(){
    //     return $this->nama . " - " . $this->kode;
    // }
}
