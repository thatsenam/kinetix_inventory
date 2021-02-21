<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_invoice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('invoice_no');
            $table->string('cid', 11)->nullable();
            $table->decimal('amount', 12);
            $table->string('method')->nullable();
            $table->string('description')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date');
            $table->string('user', 100)->nullable();
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
        Schema::dropIfExists('payment_invoice');
    }
}
