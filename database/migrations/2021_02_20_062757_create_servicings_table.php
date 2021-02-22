<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicings', function (Blueprint $table) {
            $table->id();
            $table->string('vno')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product')->nullable();
            $table->unsignedBigInteger('serial_id')->nullable();
            $table->date('receive_from_cus')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('delivery_to_cus')->nullable();
            $table->string('status')->nullable();
            $table->string('problems')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicings');
    }
}
