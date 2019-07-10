<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_program', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id');   //所属的ptr_note
            $table->string('test_round');
            $table->string('problem_num'); 
            $table->string('code_problem_num');      
            $table->string('class12_problem_num'); 
            $table->string('plan_type'); 
            $table->string('plan_complete_type'); 
            $table->string('cmtool_info'); 
            $table->string('dec817'); 
            $table->string('is_cut'); 
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
        Schema::drop('post_program');
    }
}
