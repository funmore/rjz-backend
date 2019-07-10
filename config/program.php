<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/31
 * Time: 20:32
 */

return [
    'ModelType'   =>Array(
        '0'=>'运载',
        '1'=>'战术',
        '2'=>'战略',
        '3'=>'宇航',
        '4'=>'轨道'
    ),
    'domain'   =>Array(
        '0'=>'plc',
        '1'=>'embed',
        '2'=>'unembed'
    ),
    'size'   =>Array(
        '0'=>'大',
        '1'=>'中',
        '2'=>'小'
    ),
    'Plateform'   =>Array(
        '0'=>'plateform_a',
        '1'=>'plateform_b'
    ),
    'runtime'   =>Array(
        '0'=>'runtime_a',
        '1'=>'runtime_b'
    ),
    'state'   =>Array(
        '-1'=>'意向项目',
        '0'=>'预备项目',
        '1'=>'首轮测试执行中',
        '2'=>'首轮测试结束',
        '3'=>'完成最终报告待评审',
        '4'=>'完成评审未归档',
        '5'=>'已归档',
    ),
    'dueReason'   =>Array(
        '11'=>'被测件提供晚',
        '12'=>'问题单回复晚',
        '21'=>'中心自研环境耗时多',
        '22'=>'使用开发方环境，协调难度大',
        '33'=>'项目未及时启动或人员投入少',
        '44'=>'计划提交不及时',
        '55'=>'其他'
    ),

];