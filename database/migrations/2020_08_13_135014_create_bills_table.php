<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('address');
            $table->double('total_coast');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'opened', 'new'])->default('opened');
            $table->enum('payment', ['cash', 'paypal', 'credit_card']);
            $table->double('shipping_coast')->default(0);
            $table->tinyInteger('visible')->default(1);
            $table->tinyInteger('new')->default(1);
            $table->string('id_payment')->nullable();
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
        Schema::dropIfExists('bills');
    }
}