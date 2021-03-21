<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    protected $guarded = [];
    protected $table= "bank_info";
    public function account()
    {
        return $this->belongsTo(BankAcc::class, 'id');
    }
    public function cards()
    {
        return $this->hasMany(BankCard::class, 'bank_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('scopeClient', function (Builder $builder) {
            $builder->where('client_id', auth()->user()->client_id ?? -1);
        });
    }
}
