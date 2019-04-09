<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Createdailynote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_note', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ptr_note_id');   //所属的ptr_note
            $table->integer('ptr_id');        //哪位项目组成员填写的
            $table->string('assist_name');    //协助方 ，由于无法确认协助方来自哪里，自由填写姓名
            $table->string('note');           //完成事项
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
        Schema::drop('daily_note');
    }
}
