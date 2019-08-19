<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePglogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pgfrom', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('item_id');
            $table->integer('from_id');
            $table->string('from_note');
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
        Schema::drop('pglog');
    }
}
