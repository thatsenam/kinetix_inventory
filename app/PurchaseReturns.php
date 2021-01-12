<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Date       $date
 * @property string     $pur_inv
 * @property int        $qnt
 * @property int        $price
 * @property int        $sid
 * @property string     $user
 * @property string     $lkey
 * @property int        $created_at
 * @property int        $updated_at
 * @property int        $client_id
 */
class PurchaseReturns extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_returns';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date', 'pur_inv' => 'string', 'qnt' => 'int', 'price' => 'int', 'sid' => 'int', 'user' => 'string', 'lkey' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'client_id' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
