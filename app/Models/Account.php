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
    	'uuid', 'nm_account', 'kode_account', 'account_type', 'type', 'parent_id', 'bank', 'pajak','account_header'
    ];
    // protected $with = ['nodes'];
    protected $appends = ['text', 'state', 'saldo_berjalan'];
    

    public function hutang()
    {
        return $this->hasMany('\App\Models\Hutang');
    }
    public function pajak()
    {
        return $this->hasOne('\App\Models\Account');
    }
    public function bank()
    {
        return $this->hasOne('\App\Models\Account');
    }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function nodes()
    {
        return $this->hasMany(Account::class, 'parent_id', 'id')->with('nodes');
    }

    public function jurnal_item()
    {
        return $this->hasMany('\App\Models\JurnalItem', 'account_id');
    }

    public function salesorder()
    {
        return $this->hasMany(SalesOrder::class);
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
