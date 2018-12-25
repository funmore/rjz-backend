<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_team', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id');
            //0:p_leader 1；tester 2:qa 3:cm
            $table->tinyInteger('role'); 
            $table->integer('u_id');
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
        Schema::drop('program_team');
    }
}
