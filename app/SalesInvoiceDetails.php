<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $invoice_no
 * @property string     $pid
 * @property int        $qnt
 * @property string     $user
 * @property string     $lkey
 * @property int        $created_at
 * @property int        $updated_at
 * @property int        $client_id
 */
class SalesInvoiceDetails extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_invoice_details';

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
        'invoice_no' => 'string', 'pid' => 'string', 'qnt' => 'int', 'user' => 'string', 'lkey' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'client_id' => 'int'
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
