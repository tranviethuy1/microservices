<?php

namespace App\Http\Middleware;

use Closure;

class CheckInputManageKpiProject
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

        if(!isset($year) || empty($year) || !is_numeric($year)){
            return response()->json(['error' => 'The input type is number'], 400);
        }
        return $next($request);
    }
}
