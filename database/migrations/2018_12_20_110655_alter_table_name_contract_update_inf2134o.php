<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNameContractUpdateInf2134o extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_update_info', function (Blueprint $table) {
            $table->dropColumn('info_typer');
            $table->integer('info_typer_id');
        });
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
