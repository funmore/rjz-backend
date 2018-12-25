<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowTmplateEditLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_template_edit_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workflow_template_id')->un_signed();
            $table->integer('u_id')->un_signed();
            $table->string('u_data')->default(null);
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
        Schema::drop('workflow_template_edit_log');
    }
}
