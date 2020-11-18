<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bill_id')->unsigned();
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('mall_id')->unsigned();
            $table->foreign('mall_id')->references('id')->on('malls')->onDelete('cascade');
            $table->bigInteger('size_id')->unsigned();
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->bigInteger('color_id')->unsigned();
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->integer('quantity');
            $table->double('product_coast');
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
        Schema::dropIfExists('bill_products');
    }
}