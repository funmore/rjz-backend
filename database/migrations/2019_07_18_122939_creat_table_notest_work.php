<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableNotestWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('not_test_work', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id');  
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->string('range_start');
            $table->string('range_end');
            $table->string('type');
            $table->string('note');
            $table->string('assit_name');
            $table->string('output');
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
        Schema::drop('not_test_work');
    }
}
