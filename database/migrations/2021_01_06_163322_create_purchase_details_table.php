<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('pur_inv', 191);
            $table->bigInteger('pid');
            $table->integer('qnt');
            $table->decimal('price', 12);
            $table->decimal('total', 12);
            $table->string('user', 191)->nullable();
            $table->string('lkey', 191)->nullable();
            $table->timestamp('created_at');
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
        Schema::dropIfExists('purchase_details');
    }
}
