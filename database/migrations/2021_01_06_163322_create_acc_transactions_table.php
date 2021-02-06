<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('sort_by', 55)->nullable();
            $table->string('vno');
            $table->string('head')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('note')->nullable();
            $table->decimal('debit',12,2)->default(0);
            $table->decimal('credit',12,2)->default(0);
            $table->date('date');
            $table->string('type')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acc_transactions');
    }
}
