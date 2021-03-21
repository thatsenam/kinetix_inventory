<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $guarded = [];
    public function attributes(){
        return $this->hasMany('App\ProductAttributes','product_id');
    }
    public function images(){
        return $this->hasMany('App\ProductImages','product_id');
    }
    public function cat(){
        return $this->hasOne(Category::class,'id');
    }
    public function categories()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
    public function serials()
    {
        return $this->hasMany(Serial::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('scopeClient', function (Builder $builder) {
            $builder->where('client_id', auth()->user()->client_id ?? -1);
        });
    }
}
