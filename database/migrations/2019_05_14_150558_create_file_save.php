<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileSave extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_review', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_program_id');   
            $table->integer('employee_id');
            $table->string('version');
            $table->string('category');
            $table->string('name');
            $table->string('path');
            $table->string('state');      
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
        Schema::drop('file_save');
    }
}
