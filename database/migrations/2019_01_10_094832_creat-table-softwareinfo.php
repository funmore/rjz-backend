<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableSoftwareinfo extends Migration
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
            $table->string('size');
            $table->string('complier');
            $table->string('runtime');
            $table->string('reduced_code_size');
            $table->string('reduced_reason');
            $table->string('software_type');
            $table->string('software_usage');
            $table->string('code_langu');
            $table->string('cpu_type');
            $table->string('software_cate');
            $table->string('software_sub_cate');
            $table->integer('info_typer_id');
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
        Schema::drop('software_info');
    }
}
