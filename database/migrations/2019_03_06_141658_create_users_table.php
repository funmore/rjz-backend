<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programteamrole_note', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('programteamrole_id');
            $table->string('before_day');
            $table->string('task');    
            $table->string('due_day');
            $table->string('state');
            $table->string('note');
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
        Schema::drop('programteamrole_note');
    }
}
