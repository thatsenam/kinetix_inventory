<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_heads', function (Blueprint $table) {
            $table->id();
            $table->string('cid', 30)->nullable();
            $table->string('parent_head')->nullable();
            $table->string('sub_head')->nullable();
            $table->string('head')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('client_id');
            $table->string('lkey')->nullable();
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
        Schema::dropIfExists('acc_heads');
    }
}
