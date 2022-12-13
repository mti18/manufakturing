<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Menu extends Model
{
    use Uuid;

    protected $fillable = ['name', 'url', 'route', 'component', 'icon', 'parent_id', 'shown', 'auth', 'level'];
    protected $casts = ['shown' => 'boolean', 'auth' => 'boolean', 'level' => AsArrayObject::class];
    protected $with = ['children'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['checked'];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function user_groups() {
        return $this->belongsToMany(UserGroup::class, 'user_menus');
    }

    public function getCheckedAttribute()
    {
        if ($user_group_uuid = request()->user_group_uuid) {
            $user_group = UserGroup::where('uuid', $user_group_uuid)->first();
            $check = UserMenu::where('user_group_id', $user_group->id)->where('menu_id', $this->id)->first();
            return isset($check) ? true : false;
        }
    }
}
