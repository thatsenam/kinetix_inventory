<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $pur_inv
 * @property int        $sid
 * @property string     $supp_inv
 * @property Date       $date
 * @property string     $user
 * @property string     $lkey
 * @property DateTime   $created_at
 * @property DateTime   $updated_at
 * @property int        $client_id
 */
class PurchasePrimary extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_primary';

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
        'pur_inv' => 'string', 'sid' => 'int', 'supp_inv' => 'string', 'date' => 'date', 'user' => 'string', 'lkey' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'client_id' => 'int'
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
