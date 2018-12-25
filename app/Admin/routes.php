<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');
$router->resource('users', UserController::class);
$router->resource('tasks', TaskController::class);
$router->resource('companys', CompanyController::class);
$router->resource('departments', DepartmentController::class);
$router->resource('employee', EmployeeController::class);
