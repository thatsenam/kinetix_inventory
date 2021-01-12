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
            $table->integer('id', true);
            $table->bigInteger('vno');
            $table->string('head', 191);
            $table->string('sort_by', 55)->nullable();
            $table->string('description')->nullable();
            $table->string('image', 155)->nullable();
            $table->string('notes')->nullable();
            $table->decimal('debit', 12)->default(0.00);
            $table->decimal('credit', 12)->default(0.00);
            $table->date('date');
            $table->string('user', 155)->nullable();
            $table->string('lkey', 155)->nullable();
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
        Schema::dropIfExists('acc_transactions');
    }
}
