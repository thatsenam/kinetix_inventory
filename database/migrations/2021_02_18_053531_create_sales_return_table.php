<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('rinvoice', 155);
            $table->string('sinvoice', 155)->nullable();
            $table->integer('cid')->nullable();
            $table->string('pid', 155);
            $table->integer('qnt');
            $table->decimal('uprice', 12);
            $table->decimal('tprice', 12);
            $table->decimal('total', 12);
            $table->decimal('cash_return', 12)->nullable();
            $table->date('date');
            $table->string('remarks')->nullable();
            $table->string('user', 191)->nullable();
            $table->string('lkey', 191)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
        Schema::dropIfExists('sales_return');
    }
}
