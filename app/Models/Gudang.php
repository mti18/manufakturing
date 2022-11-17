<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_gudang', 'kode'];

    public function barangmentahgudangs()
    {
        return $this->hasMany(BarangMentah::class, 'gudang_id', 'id');
    }

    public function barangjadigudangs()
    {
        return $this->hasMany(BarangJadi::class, 'gudang_id', 'id');
    }

}
