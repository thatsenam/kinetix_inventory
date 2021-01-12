<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('date');
            $table->string('pur_inv', 191)->nullable();
            $table->bigInteger('pid');
            $table->integer('qnt');
            $table->integer('price');
            $table->decimal('total', 12);
            $table->integer('sid');
            $table->string('user', 191)->nullable()->default('0');
            $table->string('lkey', 191)->nullable()->default('0');
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
        Schema::dropIfExists('purchase_returns');
    }
}
