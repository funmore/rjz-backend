<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Route::options('/{any}', function(){ return ''; })->where('any', '.*');


Route::group(['middleware' => ['weixin']], function () {
    Route::get('/api/employee/getInfo','Api\UserController@getInfo');


    Route::resource('/programedit', 'Api\ProgramEditController');
    Route::resource('/customprogramedit', 'Api\ProgramEditController@custom');
    Route::resource('/pre/program', 'Api\PreProgramEditController');
    Route::get('/pre/program/preshow', 'Api\PreProgramEditController@preshow');
    
    Route::resource('/employee', 'Api\EmployeeController');
    Route::resource('/softwareinfo', 'Api\SoftwareInfoController');
    Route::resource('/programteamrole', 'Api\ProgramTeamRoleController');
    Route::resource('/programteamroletask', 'Api\ProgramTeamRoleTaskController');
    Route::resource('/program', 'Api\ProgramController');
    Route::resource('/program/role', 'Api\ProgramController@role');
    Route::resource('/pvlog', 'Api\PvlogController');
    Route::resource('/workflow', 'Api\WorkflowController');
    Route::resource('/workflownote', 'Api\WorkflowNoteController');
    Route::resource('/dailynote', 'Api\DailyNoteController');
    Route::resource('/delayapply', 'Api\DelayApplyController');
    Route::resource('/nodenote', 'Api\NodeNoteController');
    Route::resource('/contact', 'Api\ContactController');
    Route::resource('/fileprogram', 'Api\FileProgramController');
    Route::resource('/filereview', 'Api\FileReviewController');
    Route::resource('/statistic/people', 'Api\StatisticPeopleController');
    Route::resource('/poll', 'Api\PollController');
    Route::resource('/showUnPollPeople', 'Api\PollController@showUnPollPeople');

    Route::resource('/pollfill', 'Api\PollFillController');
    Route::resource('/model', 'Api\ModelController');
    Route::resource('/BatchImport', 'Api\BatchImportController');
    Route::resource('/notestwork', 'Api\NoTestWorkController');
    Route::resource('/notestworklogmonth', 'Api\NoTestWorkController@month');
    Route::resource('/favor', 'Api\FavorController');
    Route::resource('/Team', 'Api\TeamController');


    //workfow end


});

Route::group(['middleware' => ['wxlogin']], function () {
    Route::get('/api/login', 'Api\UserController@login');
    Route::get('/api/logout', 'Api\UserController@logout');

});
