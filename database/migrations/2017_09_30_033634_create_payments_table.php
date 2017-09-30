<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('transaction_id')->unsigned();
            $table->integer('transaction_status')->unsigned();
            $table->double('total_amount')->unsigned();
            $table->double('net_amount')->unsigned();
            $table->double('fee_amount')->unsigned();
            $table->integer('merchant_id')->unsigned();
            $table->timestamp('created_on');
            $table->string('payer_name');
            $table->string('payer_email');
            $table->string('payer_phone_no');
            $table->string('shipping_address');
            $table->string('check_sum');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
