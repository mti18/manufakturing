<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMentah extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = ['uuid', 'nm_barangmentah', 'stok', 'barangsatuan_id' ];

    public function barangsatuan()
    {
        return $this->belongsTo(BarangSatuan::class, 'barangsatuan_id', 'id');
    }
}

