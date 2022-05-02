<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('id',true);
            $table->integer('category_id');
            $table->integer('sub_category_id')->nullable();
            $table->float('amount');
            $table->integer('user_id');
            $table->dateTime('due_to');
            $table->integer('vat_precentage')->nullable()->default(0);
            $table->boolean('is_vat')->nullable()->default(0);
            $table->float('total');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('transactions',function(Blueprint $table){
            $table->foreign('category_id', 'transactions_ibfk_1')->references('id')->on('categories')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
        Schema::table('transactions',function(Blueprint $table){
            $table->foreign('user_id', 'transactions_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
