<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdjustmentDetail extends Model
{
    protected $guarded = [];

    public function getProductAttribute()
    {
        return Products::find($this->pid);
    }

    public function getWarehouseAttribute()
    {
        return Warehouse::find($this->warehouse_id);
    }
}
