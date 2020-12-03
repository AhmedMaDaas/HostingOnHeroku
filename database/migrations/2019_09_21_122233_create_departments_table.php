<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('description')->nullable();
            $table->string('owner');
            $table->string('photo')->nullable();
            $table->string('icon')->nullable();
            $table->string('keywords')->nullable();
            $table->enum('is_active', ['active', 'inactive'])->default('inactive');
            $table->bigInteger('parent')->unsigned()->nullable();
            $table->foreign('parent')->references('id')->on('departments');
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
        Schema::dropIfExists('departments');
    }
}
