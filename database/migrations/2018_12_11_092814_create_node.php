<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNode extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workflow_id')->un_signed();
            $table->integer('workflow_template_id')->un_signed();
            $table->tinyInteger('type');
            $table->integer('prevnode_id')->un_signed();
            $table->integer('nextnode_id')->un_signed();


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
        Schema::drop('node');
    }
}
