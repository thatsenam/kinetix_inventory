<?php

namespace App\Models;

use App\AdjustmentDetail;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{

    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }


    public function getDetailsAttribute()
    {
        return AdjustmentDetail::query()->where('adjustment_id', $this->id);
    }
}
