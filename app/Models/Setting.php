<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Setting extends Model
{
    use Uuid;

    protected $fillable = ['uuid', 'name', 'background', 'logo_dark', 'logo_light', 'description', 'phone', 'fax', 'email', 'facebook_link', 'twitter_link', 'instagram_link', 'linked_link', 'address'];
    protected $appends = ['background_url', 'logo_dark_url', 'logo_light_url'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function getBackgroundUrlAttribute()
    {
        if (!$this->background || !is_file(public_path($this->background))) {
            return asset('/assets/media/bg/background.png');
        }
        return asset($this->background);
    }

    public function getLogoDarkUrlAttribute()
    {
        if (!$this->logo_dark || !is_file(public_path($this->logo_dark))) {
            return asset('/assets/media/logos/logo.png');
        }
        return asset($this->logo_dark);
    }

    public function getLogoLightUrlAttribute()
    {
        if (!$this->logo_light || !is_file(public_path($this->logo_light))) {
            return asset('/assets/media/logos/logo.png');
        }
        return asset($this->logo_light);
    }
}
