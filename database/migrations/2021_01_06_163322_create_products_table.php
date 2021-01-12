<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cat_id');
            $table->integer('brand_id');
            $table->string('product_name');
            $table->string('slug');
            $table->text('product_desc')->nullable();
            $table->text('product_specs')->nullable();
            $table->text('main_feature')->nullable();
            $table->double('before_price', 8, 2);
            $table->double('after_pprice', 8, 2)->nullable();
            $table->string('barcode', 155);
            $table->string('product_code')->nullable();
            $table->string('product_color')->nullable();
            $table->string('product_size')->nullable();
            $table->string('sku')->nullable();
            $table->integer('stock')->nullable();
            $table->string('product_img');
            $table->tinyInteger('is_featured');
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
        Schema::dropIfExists('products');
    }
}
