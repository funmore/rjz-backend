<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNameContractUpdateInf213o extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_update_info', function (Blueprint $table) {
            //
        });
        Schema::rename('contract_update','contract_update_info');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_update_info', function (Blueprint $table) {
            //
        });
    }
}
