<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttractiveInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attractive_informations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photo');
            $table->string('title');
            $table->bigInteger('web_site_info_id')->unsigned();
            $table->foreign('web_site_info_id')->references('id')->on('web_site_info')->onDelete('cascade');
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
        Schema::dropIfExists('attractive_informations');
    }
}
