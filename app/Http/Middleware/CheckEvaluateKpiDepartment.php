<?php

namespace App\Http\Middleware;

use Closure;

class CheckEvaluateKpiDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $department_id = $request->id_department;
        $month = $request->month;
        $year = $request->year;

        $token = $request->token;
        if(!isset($token) || empty($token)){
            return response()->json(['error' => 'You do not have the role'], 401 );
        }else{
            $role = \App\Role::where('id_employee', $token)->first();
            if(!$role){
                return response()->json(['error' => 'You do not have the role'], 401 );
            }
        }

        if(!isset($month) || empty($month)){
            return response()->json(['error' => 'Param "month" is required'], 200);
        }

        if(!isset($year) || empty($year)){
            return response()->json(['error' => 'Param "year" is required'], 200);
        }

        if($month < 1 || $month > 12){
            return response()->json(['error' => 'Something was wrong with "month" param'], 200);
        }

        if($year < 2015){
            return response()->json(['error' => 'Something was wrong with "year" param'], 200);
        }

        if(!isset($department_id) || empty($department_id)){
            return response()->json(['error' => 'Param "id_department" is required'], 200);
        }

        $department = \App\Department::find($department_id);

        if(!isset($department) || empty($department)){
            return response()->json(['error' => 'This department is not exist'],200);
        }

        return $next($request);
    }
}
