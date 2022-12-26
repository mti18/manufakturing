<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class PembelianDetail extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'pembelian_details';

    
    public function salesorder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}

