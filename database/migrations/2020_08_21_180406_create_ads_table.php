<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('ad');
            $table->string('photo');
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->integer('discount')->nullable();

            $table->bigInteger('mall_id')->unsigned();
            $table->foreign('mall_id')->references('id')->on('malls')->onDelete('cascade');
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
        Schema::dropIfExists('ads');
    }
}
