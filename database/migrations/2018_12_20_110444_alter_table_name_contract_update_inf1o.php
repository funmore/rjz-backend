<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNameContractUpdateInf1o extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_update_info', function (Blueprint $table) {
            
            $table->integer('contract_id');
            $table->string('content');
            $table->tinyInteger('contract_update_info_state');
            $table->integer('info_typer');
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
