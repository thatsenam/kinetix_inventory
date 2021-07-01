<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('warehouse_id')->unsigned()->nullable()->index();
            $table->date('date')->nullable();
            $table->string('note', 1000)->nullable();
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
        Schema::drop('adjustments');
    }
}
