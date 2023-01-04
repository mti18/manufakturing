<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiHutang extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'uuid', 'hutangpiutang_id', 'bukti',
    ];

    public static function booted() {
        parent::boot();

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->bukti)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->bukti)));
            }
        });
    }
     public function hutangpiutang()
    {
        return $this->belongsTo(HutangPiutang::class);
    }
}
