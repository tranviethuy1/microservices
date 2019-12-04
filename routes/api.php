<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route Manage Data Service
Route::get('microservice/manager/kpi/department/{id_department}', 'Api\DataController@managerDepartment')
    ->middleware('checkmanagerdepartmentapi');

Route::get('microservice/manager/kpi/user/{id_user}', 'Api\DataController@managerUserProject')
    ->middleware('checkmanageruserprojectapi');

Route::get('microservice/manager/kpi/user/projects/{id_user}', 'Api\DataController@managerUser')
    ->middleware('checkmanageruserapi');

Route::get('microservice/manager/kpi/projects/{year}', 'Api\DataController@manageProjectKPI')->middleware('CheckInputManageKpiProject');

//Route Caculate KPI of project
Route::get('microservice/kpi/project/{id_project}', 'Api\ProjectController@evaluateKpiProject')->middleware('CheckInputKpiProject');

Route::get('microservice/kpi/projects', 'Api\ProjectController@evaluateKpiAllProject');

Route::get('microservice/kpi/projects/{year}', 'Api\ProjectController@evaluateKpiAllProjectYear')->middleware('CheckInputAllKpiProject');
