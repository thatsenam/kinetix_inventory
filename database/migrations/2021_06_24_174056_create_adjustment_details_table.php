<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adjustment_id')->nullable();
            $table->unsignedBigInteger('pid')->nullable();
            $table->unsignedBigInteger('add_qnt')->nullable();
            $table->unsignedBigInteger('sub_qnt')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->date('date')->useCurrent();
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
        Schema::dropIfExists('adjustment_details');
    }
}
