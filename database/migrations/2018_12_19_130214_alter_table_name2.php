<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableName2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('employees', 'employee');
        Schema::rename('programs', 'program');
        Schema::rename('program_team_roles', 'program_team_role');
        Schema::rename('team_roles', 'team_role');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
