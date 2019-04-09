<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatedelayApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delay_apply', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ptr_note_id');   //所属的ptr_note
            $table->string('delay_reason');     
            $table->string('delay_day');
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
        Schema::drop('delay_apply');
    }
}
