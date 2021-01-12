<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $rinvoice
 * @property string     $sinvoice
 * @property int        $cid
 * @property string     $pid
 * @property int        $qnt
 * @property Date       $date
 * @property string     $remarks
 * @property string     $user
 * @property string     $lkey
 * @property int        $created_at
 * @property int        $updated_at
 * @property int        $client_id
 */
class SalesReturn extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_return';

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
        'rinvoice' => 'string', 'sinvoice' => 'string', 'cid' => 'int', 'pid' => 'string', 'qnt' => 'int', 'date' => 'date', 'remarks' => 'string', 'user' => 'string', 'lkey' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'client_id' => 'int'
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


//    protected static function boot()
//    {
//        parent::boot();
//
//        static::addGlobalScope('scopeClient', function (Builder $builder) {
//            $builder->where('client_id', auth()->user()->client_id ?? -1);
//        });
//    }
}
