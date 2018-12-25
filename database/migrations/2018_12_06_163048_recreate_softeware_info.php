<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateSoftewareInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_info', function (Blueprint $table) {


            $table->increments('id');
            $table->integer('program_id')->un_signed();
            $table->double('version_id',3,1);
            $table->string('name');
            $table->smallInteger('prodoucer');
            $table->string('contact');
            $table->smallInteger('domain');
            $table->smallInteger('size');
            $table->string('complier');
            $table->smallInteger('plateform');
            $table->smallInteger('runtime');
            $table->integer('reduced_code_size')->un_signed();
            $table->string('reduced_reason');
            $table->smallInteger('type');
            $table->integer('info_typer_id')->un_signed();
            //$table->foreign('info_typer_id')->references('id')->on('employees');
            $table->string('infoSource');
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
        Schema::drop('software_info');
    }
}
