<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSoftewareInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('software_info', function (Blueprint $table) {
             // $table->dropColumn(['producer','domain','plateform',
             //                     'size','runtime','type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('software_info', function (Blueprint $table) {
            //
        });
    }
}
