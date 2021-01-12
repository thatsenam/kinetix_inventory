<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function acctransactions()
    {
        return $this->hasMany('App\AccTransaction', 'head');
    }
    // public function sales(){
    //     return $this->hasMany('App\Sales','cid');
    // }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('scopeClient', function (Builder $builder) {
            $builder->where('client_id', auth()->user()->client_id ?? -1);
        });
    }
}
