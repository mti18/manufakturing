<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
    	'uuid', 'nm_account', 'kode_account', 'account_type', 'type', 'parent_id',
    ];
    protected $with = ['nodes'];
    protected $appends = ['text', 'state'];
    

    // public function children()
    // {
    //     return $this->hasOne(Account::class, 'parent_id', 'id');
    // }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id', 'id');
    }

    public function nodes()
    {
        return $this->hasMany(Account::class, 'parent_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->nm_account . " - " . $this->kode_account;
    }

    public function getStateAttribute()
    {
        return ['checked'=> false, 'selected'=> false, 'expanded'=> false];
    }
    
}
