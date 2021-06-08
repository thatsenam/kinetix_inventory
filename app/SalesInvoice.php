<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $invoice_no
 * @property int        $cid
 * @property string     $remarks
 * @property Date       $date
 * @property string     $user
 * @property string     $lkey
 * @property int        $created_at
 * @property int        $updated_at
 * @property int        $client_id
 */
class SalesInvoice extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_invoice';

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
//    protected $fillable = [
//        'invoice_no', 'cid', 'vat', 'scharge', 'discount', 'amount', 'gtotal', 'payment', 'due', 'remarks', 'date', 'user', 'lkey', 'created_at', 'updated_at', 'client_id'
//    ];


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
//    protected $casts = [
//        'invoice_no' => 'string', 'cid' => 'int', 'remarks' => 'string', 'date' => 'date', 'user' => 'string', 'lkey' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'client_id' => 'int'
//    ];

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


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('scopeClient', function (Builder $builder) {
            $builder->where('client_id', auth()->user()->client_id ?? -1);
        });
    }

    public function getCustomerAttribute()
    {
        return Customer::find($this->cid);
    }
}
