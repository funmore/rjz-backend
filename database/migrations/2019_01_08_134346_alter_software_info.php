<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSoftwareInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('software_info', function (Blueprint $table) {
            $table->dropColumn('software_cate');
            $table->dropColumn('software_sub_cate');
            $table->dropColumn('cpu_type');
            $table->dropColumn('code_langu');
            $table->dropColumn('software_usage');
            $table->dropColumn('software_type');
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
