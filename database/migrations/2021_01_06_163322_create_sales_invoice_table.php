<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('invoice_no');
            $table->integer('cid')->nullable();
            $table->decimal('vat', 12)->nullable()->default(0.00);
            $table->decimal('scharge', 12)->nullable();
            $table->decimal('discount', 12)->nullable()->default(0.00);
            $table->decimal('amount', 12)->default(0.00);
            $table->decimal('gtotal', 12);
            $table->decimal('payment', 12)->nullable()->default(0.00);
            $table->decimal('due', 12)->default(0.00);
            $table->string('remarks');
            $table->date('date');
            $table->string('user', 191)->nullable();
            $table->string('lkey', 191)->nullable();
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
        Schema::dropIfExists('sales_invoice');
    }
}
