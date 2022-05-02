<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_payments', function (Blueprint $table) {
            $table->integer('id',true);
            $table->integer('transaction_id');
            $table->float('amount');
            $table->dateTime('paid_on');
            $table->string('payment_method');
            $table->text('details')->nullable();
            $table->timestamps();
        });
        Schema::table('transaction_payments',function(Blueprint $table){
            $table->foreign('transaction_id', 'transaction_payments_ibfk_1')->references('id')->on('transactions')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_payments');
    }
}
