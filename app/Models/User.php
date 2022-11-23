<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Traits\Uuid;

class User extends Authenticatable implements JWTSubject
{
    use Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid', 'name', 'email', 'password', 'level', 'whatsapp', 'nip', 'address', 'avatar', 'level', 'is_active', 'birth_date', 'status', 'position_id', 'user_group_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token', 'created_at', 'updated_at', 'user_group_id', 'position_id', 'otp', 'otp_expire'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $appends = ['user_group_uuid', 'position_uuid'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function user_group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function getUserGroupUuidAttribute()
    {   
        $user_group = $this->user_group()->first();
        return isset($user_group) ? $user_group->uuid : null;
    }

    public function getPositionUuidAttribute()
    {
        $position = $this->position()->first();
        return isset($position) ? $position->uuid : null;
    }

    public static function booted() {
        parent::boot();

        self::deleted(function ($model) {
            if (file_exists(storage_path('app/public/' . str_replace('storage/', '', $model->avatar)))) {
                unlink(storage_path('app/public/' . str_replace('storage/', '', $model->avatar)));
            }
        });
    }
}
