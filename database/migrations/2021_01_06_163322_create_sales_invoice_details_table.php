<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('invoice_no');
            $table->string('pid');
            $table->integer('qnt');
            $table->decimal('price', 12);
            $table->decimal('total', 12);
            $table->string('user');
            $table->string('lkey')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable()->useCurrent();
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
        Schema::dropIfExists('sales_invoice_details');
    }
}
