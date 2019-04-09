<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePvlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pvlog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pvstate_id');
            $table->integer('program_id');
            $table->integer('changer_id');
            $table->string('change_note');
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
        Schema::drop('pvlog');
    }
}
