<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id');
            //0:teamleader 1；v_teamleader 2:tester 
            $table->tinyInteger('role');
            //存放用户id 
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
        Schema::drop('team_roles');
    }
}
