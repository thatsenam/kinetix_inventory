<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('seller_bank_id');
            $table->integer('seller_bank_acc_id')->nullable();
            $table->string('clients_bank', 191)->nullable();
            $table->string('clients_bank_acc', 191)->nullable();
            $table->string('check_no', 55)->nullable();
            $table->date('check_date')->nullable();
            $table->date('date');
            $table->integer('cid')->nullable();
            $table->integer('sid')->nullable();
            $table->integer('eid')->nullable();
            $table->string('invoice_no', 155)->nullable();
            $table->string('voucher_no', 155)->nullable();
            $table->decimal('deposit', 12)->nullable();
            $table->decimal('withdraw', 12)->nullable();
            $table->string('type', 55)->nullable();
            $table->string('status', 55)->nullable();
            $table->string('tranxid', 191)->nullable();
            $table->string('remarks', 191)->nullable();
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
        Schema::dropIfExists('bank_transactions');
    }
}
