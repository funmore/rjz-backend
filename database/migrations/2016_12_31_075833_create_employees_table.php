<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('age')->unsigned();
            $table->boolean('sex')->default(false);  //false:male true:female
            $table->string('mobilephone');
            $table->integer('team_id')->unsigned();
            //$table->foreign('team_id')->references('id')->on('teams');
            $table->boolean('is_director')->default(false);
            $table->boolean('is_v_director')->default(false);
            $table->boolean('is_chiefdesigner')->default(false);
            $table->boolean('is_v_chiefdesigner')->default(false);
            $table->boolean('is_p_leader')->default(false);
            $table->boolean('is_p_principal')->default(false);
            $table->boolean('is_qa')->default(false);
            $table->boolean('is_cm')->default(false);
            $table->boolean('is_bd')->default(false);
            $table->boolean('is_tester')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->timestamps();

            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
