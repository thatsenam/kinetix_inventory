<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BankAcc extends Model
{
    protected $guarded = [];
    protected $table= "bank_acc";

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('scopeClient', function (Builder $builder) {
            $builder->where('client_id', auth()->user()->client_id ?? -1);
        });
    }

    public function account()
    {
        return $this->hasOne(BankInfo::class, 'id','bank_id');
    }
}
