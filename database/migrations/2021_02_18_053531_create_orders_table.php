<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number', 50);
            $table->unsignedInteger('user_id')->nullable()->index('orders_user_id_foreign');
            $table->string('ip_address', 191)->nullable();
            $table->string('name');
            $table->string('phone_no', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('order_note', 155)->nullable();
            $table->decimal('total', 12);
            $table->decimal('delivery_charge', 11);
            $table->decimal('vat', 11)->nullable();
            $table->decimal('tax', 11)->nullable();
            $table->decimal('discount', 11)->nullable();
            $table->decimal('grand_total', 12);
            $table->string('billing_name', 191)->nullable();
            $table->string('billing_info')->nullable();
            $table->string('billing_phone', 25)->nullable();
            $table->string('shipping_name', 191)->nullable();
            $table->string('shipping_info')->nullable();
            $table->string('shipping_phone', 25)->nullable();
            $table->integer('area')->nullable();
            $table->integer('district')->nullable();
            $table->integer('upazila')->nullable();
            $table->string('payment_method', 200);
            $table->string('payment_status', 25)->nullable();
            $table->string('shipping_method', 55);
            $table->string('shipping_number', 155)->nullable();
            $table->date('order_date');
            $table->date('confirm_date')->nullable();
            $table->date('rfp_date')->nullable();
            $table->date('shipped_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->date('cancel_date')->nullable();
            $table->string('status', 51);
            $table->tinyInteger('is_paid')->nullable()->default(0);
            $table->string('aff_id', 191)->nullable();
            $table->tinyInteger('is_completed')->nullable()->default(0);
            $table->tinyInteger('is_seen_by_admin')->nullable()->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('orders');
    }
}
