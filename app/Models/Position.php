<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Position extends Model
{
    use Uuid;

    protected $fillable = ['uuid', 'name', 'code'];
    protected $hidden = ['id', 'createded_at', 'updated_at'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
