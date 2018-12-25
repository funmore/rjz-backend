<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('workflow_template_name');
            $table->tinyInteger('workflow_type');
            $table->integer('creator')->un_signed();                //创建者外键
            $table->tinyInteger('is_editable')->default(1);   //是否可编辑
            $table->tinyInteger('is_dustbin')->default(0);  //0:in use 1:in dustbin  //是否在垃圾箱

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
        Schema::drop('workflow_template');
    }
}
