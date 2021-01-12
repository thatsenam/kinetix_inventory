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
            $table->integer('id', true);
            $table->string('cid', 30)->nullable();
            $table->string('parent_head', 155)->nullable();
            $table->string('sub_head', 155)->nullable();
            $table->string('head');
            $table->string('user');
            $table->string('lkey', 155)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('acc_heads');
    }
}
