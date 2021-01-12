<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $user_id
 * @property string     $name
 * @property string     $shop
 * @property string     $slug
 * @property int        $commission
 * @property string     $store_type
 * @property string     $pay_method
 * @property int        $area
 * @property int        $district
 * @property int        $upazila
 * @property string     $address
 * @property string     $profilepic
 * @property string     $logo
 * @property string     $cover
 * @property string     $trade_license
 * @property int        $created_at
 * @property int        $updated_at
 * @property int        $client_id
 */
class Settings extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

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
        'user_id' => 'int', 'name' => 'string', 'shop' => 'string', 'slug' => 'string', 'commission' => 'int', 'store_type' => 'string', 'pay_method' => 'string', 'area' => 'int', 'district' => 'int', 'upazila' => 'int', 'address' => 'string', 'profilepic' => 'string', 'logo' => 'string', 'cover' => 'string', 'trade_license' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'client_id' => 'int'
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
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
