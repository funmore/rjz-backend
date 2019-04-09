<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_note', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');   //所属的ptr_note
            $table->integer('node_id');
            $table->string('note');     
            $table->string('state');
            $table->boolean('is_up');
            $table->timestamp('done_day');
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
        Schema::drop('program_note');
    }
}
