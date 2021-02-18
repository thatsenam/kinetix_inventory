<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePrimaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_primary', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('pur_inv', 155);
            $table->integer('sid');
            $table->string('supp_inv', 155);
            $table->decimal('discount', 11);
            $table->decimal('amount', 12);
            $table->decimal('total', 12);
            $table->decimal('payment', 12);
            $table->date('date');
            $table->string('user', 155)->nullable();
            $table->string('lkey', 191)->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_primary');
    }
}
