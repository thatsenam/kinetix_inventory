<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $guarded = [];

    protected $table= "general_settings";

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::addGlobalScope('scopeClient', function (Builder $builder) {
//            $builder->where('client_id', auth()->user()->client_id ?? -1);
//        });
//    }
}
