<?php

namespace App\Http\Middleware;

use Closure;

class CheckManagerUserProjectApi
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
        $token = $request->token;
        $id_user = $request->id_user;
        $id_project = $request->id_project;

        if(!isset($token) || empty($token)){
            return response()->json(['error' => 'You do not have the role'], 401 );
        }else{
            $role = \App\Role::where('id_employee', $token)->first();
            if(!$role){
                return response()->json(['error' => 'You do not have the role'], 401 );
            }
        }

        if(!isset($id_user) || empty($id_user)){
            return response()->json(['error' => 'Something was wrong with request'], 400);
        }
        if(!isset($id_project) || empty($id_project)){
            return response()->json(['error' => 'Something was wrong with request'], 400);
        }
        return $next($request);
    }
}
