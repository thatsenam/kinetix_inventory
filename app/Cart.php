<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $product_id
 * @property string     $product_name
 * @property string     $image
 * @property string     $product_code
 * @property string     $product_color
 * @property string     $weight
 * @property string     $price
 * @property int        $quantity
 * @property string     $user_email
 * @property string     $session_id
 * @property int        $created_at
 * @property int        $updated_at
 * @property int        $client_id
 */
class Cart extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cart';

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
        'product_id' => 'int', 'product_name' => 'string', 'image' => 'string', 'product_code' => 'string', 'product_color' => 'string', 'weight' => 'string', 'price' => 'string', 'quantity' => 'int', 'user_email' => 'string', 'session_id' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'client_id' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Scopes...

    // Functions ...

    // Relations ...
}
