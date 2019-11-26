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
        if(!isset($id_department) || empty($id_department) || !is_numeric($id_department)){
            return response()->json(['error' => 'Something was wrong with request'], 400);
        }
        return $next($request);
    }
}
