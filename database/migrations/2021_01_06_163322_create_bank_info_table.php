<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 191);
            $table->string('address')->nullable();
            $table->string('phone', 55)->nullable();
            $table->string('email', 55)->nullable();
            $table->string('contact_name', 191)->nullable();
            $table->string('contact_no', 191)->nullable();
            $table->string('user', 191)->nullable();
            $table->string('lkey', 191)->nullable();
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
        Schema::dropIfExists('bank_info');
    }
}
