<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToBankTransactionTable extends Migration
{
    public function up()
    {
        Schema::table('bank_transactions', function (Blueprint $table) {
           $table->string('vno')->nullable();
        });
    }

    public function down()
    {
        Schema::table('bank_transaction', function (Blueprint $table) {
            //
        });
    }
}
