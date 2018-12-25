<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProgramProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('program', function (Blueprint $table) {
            $table->string('name');            //项目名称
            $table->string('program_identity');//项目标识
            $table->tinyInteger('program_stage');//项目阶段  方案/初样/试样/定型
            $table->tinyInteger('dev_type');     //研制类型  一类 二类，三类，四类
            $table->tinyInteger('classification'); //密级     机密 秘密 内部  公开
            $table->tinyInteger('program_type');   //测试类型  配置项  定型  回归
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program', function (Blueprint $table) {
            //
        });
    }
}
