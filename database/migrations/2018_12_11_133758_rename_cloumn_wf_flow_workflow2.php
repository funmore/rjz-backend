<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCloumnWfFlowWorkflow2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workflow', function (Blueprint $table) {
            $table->dropColumn('wf_template_id');
            $table->integer('workflow_template_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workflow', function (Blueprint $table) {
            //
        });
    }
}
