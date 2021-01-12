<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $guarded = [];
    protected $table= "bank_transactions";
}
