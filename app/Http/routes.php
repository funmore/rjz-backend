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

Route::group(['middleware' => ['cors','weixin']], function () {
    Route::get('/api/employee/getInfo','Api\UserController@getInfo');


    //Route::get('api/')


    Route::get('/api/grant', 'Api\UserController@grant');
    Route::get('/api/order', 'Api\OrderController@index');
    Route::get('/api/order/create', 'Api\OrderController@create');
    Route::get('/api/order/show', 'Api\OrderController@show');
    Route::get('/api/order/approval', 'Api\OrderController@approval');
    Route::get('/api/order/deal', 'Api\OrderController@deal');
    Route::get('/api/order/cancel', 'Api\OrderController@cancel');
    Route::get('/api/order/review', 'Api\OrderController@review');
    Route::get('/api/order/confirm', 'Api\OrderController@confirm');
    Route::get('/api/order/approvalShow', 'Api\OrderController@approvalShow');
    Route::get('/api/order/adminShow', 'Api\OrderController@adminShow');
    Route::get('/api/order/adminAppoint', 'Api\OrderController@adminAppoint');
    Route::get('/api/order/adminCompete', 'Api\OrderController@adminCompete');
    Route::get('/api/order/adminRetreat', 'Api\OrderController@adminRetreat');
    Route::get('/api/order/adminApproval', 'Api\OrderController@adminApproval');

    Route::get('/api/order/companyShow', 'Api\OrderController@companyShow');
    Route::get('/api/order/companyAdminValid', 'Api\OrderController@companyAdminValid');
    Route::get('/api/order/showOrderOne', 'Api\OrderController@showOrderOne');
    Route::get('/api/company/carShow', 'Api\CompanyController@carShow');
    Route::get('/api/company/driverShow', 'Api\CompanyController@driverShow');
    Route::get('/api/company/companyAccept', 'Api\CompanyController@companyAccept');
    Route::get('/api/company/companyAccept39', 'Api\CompanyController@companyAccept39');
    Route::get('/api/company/orderSettle', 'Api\CompanyController@orderSettle');
    Route::get('/api/company/getCompany', 'Api\CompanyController@getCompany');

    Route::get('/api/employee/getManager', 'Api\EmployeeController@getManager');
});

Route::group(['middleware' => ['cors','wxlogin']], function () {
    Route::get('/api/login', 'Api\UserController@login');
    Route::get('/api/logout', 'Api\UserController@logout');

});
