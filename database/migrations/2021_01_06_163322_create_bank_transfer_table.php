<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transfer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tf_bank');
            $table->integer('tf_acc');
            $table->integer('tt_bank');
            $table->integer('tt_acc');
            $table->string('check_no', 155)->nullable();
            $table->decimal('amount', 12);
            $table->date('date');
            $table->string('user', 155)->nullable();
            $table->string('lkey', 155)->nullable();
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
        Schema::dropIfExists('bank_transfer');
    }
}
