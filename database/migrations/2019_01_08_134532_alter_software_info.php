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
            $table->string('software_cate');
            $table->string('software_sub_cate');
            $table->string('cpu_type');
            $table->string('code_langu');
            $table->string('software_usage');
            $table->string('software_type');
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
