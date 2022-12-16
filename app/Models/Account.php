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
    protected $appends = ['text', 'state', 'saldo_berjalan'];
    

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

    public function jurnal_item()
    {
        return $this->hasMany('\App\Models\JurnalItem', 'account_id');
    }

    public function umum()
    {
        return $this->hasMany('\App\Models\JurnalItem', 'account_id')->with('MasterJurnal')->whereHas('MasterJurnal', function ($query) {
            $query->where('type', 'umum');
        });
    }

    public function penyesuaian()
    {
        return $this->hasMany('\App\Models\JurnalItem', 'account_id')->with('MasterJurnal')->whereHas('MasterJurnal', function ($query) {
            $query->where('type', 'penyesuaian');
        });
    }

    public function getTextAttribute()
    {
        return $this->nm_account . " - " . $this->kode_account;
    }

    public function getStateAttribute()
    {
        return ['checked'=> false, 'selected'=> false, 'expanded'=> false];
    }

    public function getSaldoBerjalanAttribute()
    {
        $debit = JurnalItem::where('account_id', $this->id)->sum('debit');
        $kredit = JurnalItem::where('account_id', $this->id)->sum('kredit');

        

        return $debit - $kredit;
    }
}
