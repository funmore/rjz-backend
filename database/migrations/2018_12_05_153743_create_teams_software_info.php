<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsSoftwareInfo extends Migration
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
            //$table->foreign('program_id')->references('id')->on('programs');
            $table->double('version_id',3,1);
            $table->string('name');
            $table->enum('producer',['producer_a','producer_b']);
            $table->string('contact');
            $table->enum('domain',['plc','embed','unembed']);
            $table->enum('size',['大','中','小']);
            $table->string('complier');
            $table->enum('plateform',['plateform_a','plateform_b']);
            $table->enum('runtime',['runtiam_a','runtime_b']);
            $table->integer('reduced_code_size')->un_signed();
            $table->string('reduced_reason');
            $table->enum('type',['type_a','type_b']);
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
