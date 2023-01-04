<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HutangPiutangDetail extends Model
{
    use HasFactory;
    use  Uuid;

    protected $fillable = [
    	'uuid', 'hutangpiutang_id', 'tanggal', 'bukti', 'jumlah', 'user_id', 'keterangan'
    ];

    public function hutangpiutang()
    {
        return $this->belongsTo(HutangPiutang::class, 'hutangpiutang_id', 'id');
    }

}
