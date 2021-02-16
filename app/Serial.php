<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
