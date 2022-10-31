<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class UserGroup extends Model
{
    use Uuid;

    protected $fillable = ['uuid', 'name'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'user_menus');
    }
}
