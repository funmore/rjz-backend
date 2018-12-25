<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSoftwareInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('software_info', function (Blueprint $table) {
            $table->tinyInteger('software_type');  //A B C D
            $table->tinyInteger('software_cate');  //嵌入  非嵌  FPGA PLC
            $table->tinyInteger('software_sub_cate');  //飞行控制  信息处理  组合导航  综合控制  PLC  伺服控制 测发控  FPGA CPLD OTHERS
            $table->tinyInteger('cpu_type');         //DSP SPARC X86 X51 OTHERS
            $table->tinyInteger('code_langu');       // C OTHERS
            $table->tinyInteger('software_usage');    //地面  弹上
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
