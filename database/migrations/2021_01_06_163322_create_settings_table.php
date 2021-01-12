<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('shop', 191);
            $table->string('slug', 191);
            $table->integer('commission')->default(10);
            $table->string('store_type', 55)->nullable();
            $table->string('pay_method', 55)->default('bank');
            $table->unsignedInteger('area')->nullable();
            $table->unsignedInteger('district')->nullable();
            $table->unsignedInteger('upazila')->nullable();
            $table->string('address', 191)->nullable();
            $table->string('profilepic', 191)->nullable();
            $table->string('logo', 191)->nullable();
            $table->string('cover', 191)->nullable();
            $table->string('trade_license', 191)->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('settings');
    }
}
