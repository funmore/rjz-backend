<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_note', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workflow_id');
            $table->integer('employee_id');
            $table->integer('from_node_id');
            $table->integer('to_node_id');
            $table->string('note');
            $table->string('note_type');
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
        Schema::drop('workflow_note');
    }
}
