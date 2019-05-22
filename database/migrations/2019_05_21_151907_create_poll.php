<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_column', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('poll_id');
            $table->tinyInteger('index');
            $table->string('name');
            $table->string('type');  //number,text,select
            $table->string('valid_value');
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
        Schema::drop('poll_column');
    }
}
