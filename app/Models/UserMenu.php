<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class UserMenu extends Model
{
    use Uuid;

    protected $fillable = ['uuid', 'user_group_id', 'menu_id'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'user_group_id', 'menu_id'];
    protected $appends = ['user_group_uuid', 'menu_uuid'];

    public function user_group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function getUserGroupUuidAttribute()
    {
        $user_group = $this->user_group()->first();
        return $user_group->uuid;
    }

    public function getMenuUuidAttribute()
    {
        $menu = $this->menu()->first();
        return $menu->uuid;
    }
}
