<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNameContractUpdateInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_update_inf', function (Blueprint $table) {
            //
        });
        Schema::rename('contract_update_inf','contract_update_info');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_update_inf', function (Blueprint $table) {
            //
        });
    }
}
