<?php

namespace App\Http\Middleware;

use Closure;

class CheckManageDepartmentApi
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
        $id_department = $request->id_department;
        $token = $request->token;

        if(!isset($token) || empty($token)){
            return response()->json(['error' => 'You do not have the role'], 401 );
        }else{
            $role = \App\Role::where('id_employee', $token)->first();
            if(!$role){
                return response()->json(['error' => 'You do not have the role'], 401 );
            }
        }

        if(!isset($id_department) || empty($id_department) || !is_numeric($id_department)){
            return response()->json(['error' => 'Something was wrong with request'], 400);
        }
        return $next($request);
    }
}
