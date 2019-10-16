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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('microservice/helloworld', 'Api\ConnectController@connect');

Route::get('microservice/kpi/user/{id_user}', 'Api\KpiController@evaluateKpiUser')
    ->middleware('checkevaluatekpiuser');

Route::get('microservice/kpi/users', 'Api\KpiController@evaluateKpiAllUsers');
