<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class PembelianDetail extends Model
{
    use HasFactory;
    use Uuid;

    
    public function salesorder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}

