<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProgramteamrolePercentge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('program_team_role', function (Blueprint $table) {
            $table->string('plan_workload_per');
            $table->string('actual_workload_per');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program_team_role', function (Blueprint $table) {
            //
        });
    }
}
