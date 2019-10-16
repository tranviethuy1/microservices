<?php

namespace App\Http\Middleware;

use Closure;

class CheckEvaluateKpiUser
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
        $user_id = $request->id_user;
        $month = $request->month;
        $year = $request->year;

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

        if(!isset($user_id) || empty($user_id)){
            return response()->json(['error' => 'Param "user_id" is required'], 200);
        }

        $user = \App\User::find($user_id);

        if(!isset($user) || empty($user)){
            return response()->json(['error' => 'This user is not exist'],200);
        }

        return $next($request);
    }
}
