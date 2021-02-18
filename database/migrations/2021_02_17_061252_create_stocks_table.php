<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('product_id');
            $table->integer('in_qnt')->default('0');
            $table->integer('out_qnt')->default('0');
            $table->string('particulars',255);
            // $table->bigInteger('warehouse')->nullable();
            $table->string('remarks',255);
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('stocks');
    }
}
