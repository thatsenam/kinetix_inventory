<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    public function serials()
    {
        return $this->hasMany(Serial::class);
    }
}
