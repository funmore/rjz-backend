<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('usetime');
            $table->integer('type');
            $table->text('reason')->nullable();
            $table->string('passenger');
            $table->string('mobilephone');
            $table->boolean('isweekend')->default(false);
            $table->boolean('isreturn')->default(false);
            $table->text('workers')->nullable();
            $table->integer('state')->default(0);
            $table->text('remark')->nullable();
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
        Schema::drop('programs');
    }
}
