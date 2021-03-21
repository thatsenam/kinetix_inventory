<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";

    protected $guarded = [];

    public function categories(){
        return $this->hasMany('App\Category','parent_id');
    }
    public function products(){
        return $this->belongsTo(Products::class);
    }

    public function parent() {
        return $this->hasOne('categories', 'id', 'parent_id');
    }

    public function children() {
        return $this->hasMany('categories', 'parent_id', 'id');
    }

    public static function tree() {
        return static::with(implode('.', array_fill(0, 4, 'children')))->where('parent_id', '=', NULL)->get();
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('scopeClient', function (Builder $builder) {
            $builder->where('client_id', auth()->user()->client_id ?? -1);
        });
    }
}
