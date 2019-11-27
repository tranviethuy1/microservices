<?php

namespace App\Http\Middleware;

use Closure;

class CheckManagerUserApi
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
        $id_user = $request->id_user;
        $id_project = $request->id_project;
        if(!isset($id_user) || empty($id_user)){
            return response()->json(['error' => 'Something was wrong with request'], 400);
        }
        if(!isset($id_project) || empty($id_project)){
            return response()->json(['error' => 'Something was wrong with request'], 400);
        }
        return $next($request);
    }
}
