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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::get('microservice/helloworld', 'Api\ConnectController@connect');
//
//Route::get('microservice/kpi/user/{id_user}', 'Api\KpiController@evaluateKpiUser')
//    ->middleware('checkevaluatekpiuser');
//
//Route::get('microservice/kpi/users', 'Api\KpiController@evaluateKpiAllUsers');
//
//Route::get('microservice/kpi/department/{id_department}', 'Api\KpiController@evaluateKpiDepartment')
//    ->middleware('checkevaluatekpidepartment');
//
//Route::get('microservice/kpi/departments', 'Api\KpiController@evaluateKpiAllDepartments');


//---------------------------------------------------------------------------------------

//Route Manage Data Service
Route::get('microservice/manager/kpi/department/{id_department}', 'Api\DataController@managerDepartment')
    ->middleware('checkmanagerdepartmentapi');

Route::get('microservice/manager/kpi/user/{id_user}/{id_project}', 'Api\DataController@managerUser')
    ->middleware('checkmanageruserapi');

Route::get('microservice/manager/kpi/projects/{year}', 'Api\DataController@manageProjectKPI')->middleware('CheckInputManageKpiProject');

//Route Caculate KPI of project
Route::get('microservice/kpi/project/{id_project}', 'Api\ProjectController@evaluateKpiProject')->middleware('CheckInputKpiProject');

Route::get('microservice/kpi/projects', 'Api\ProjectController@evaluateKpiAllProject');
