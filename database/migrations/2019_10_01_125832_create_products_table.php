<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('photo')->nullable();
            $table->string('optimized_photo')->nullable();
            $table->longtext('content')->nullable();

            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->bigInteger('trade_id')->unsigned()->nullable();
            $table->foreign('trade_id')->references('id')->on('trade_marks')->onDelete('cascade');

            $table->bigInteger('manu_id')->unsigned()->nullable();
            $table->foreign('manu_id')->references('id')->on('manufacturers')->onDelete('cascade');

            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');

            $table->bigInteger('size_id')->unsigned()->nullable();
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->string('size')->nullable();

            $table->float('evaluation')->default(0.0);

            $table->bigInteger('weight_id')->unsigned()->nullable();
            $table->foreign('weight_id')->references('id')->on('weights')->onDelete('cascade');
            $table->string('product_weight')->nullable();
            $table->integer('stock')->default(0);

            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();

            $table->date('offer_start_at')->default('1890-01-01 00:00:00');
            $table->date('offer_end_at')->default('1890-01-01 00:00:00');
            $table->float('price_offer')->default(0);

            $table->float('price')->default(0);
            $table->enum('status', ['pending', 'refused', 'active'])->default('pending');
            $table->longtext('reason')->nullable();

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
        Schema::dropIfExists('products');
    }
}
